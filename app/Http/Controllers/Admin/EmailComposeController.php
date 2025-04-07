<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GroupList;
use App\Models\EmailCompose;
use App\Models\SmtpSetup;
use App\Models\Customer_master;
use App\Models\Customer_contact;
use App\Models\Customer_address;
use App\Models\Customer_group;
use App\Models\Price_list;
use App\Models\Country;
use App\Models\State_master;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\ScheduleEmail;
use App\Models\Failed_jobs;
use App\Models\Job_batch;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Egulias\EmailValidator\EmailValidator;
use Illuminate\Support\Facades\Bus;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmailComposeController extends Controller
{
    public function index()
    {
        try{
            $data['title'] = "Email Message";
            $data['groupList'] = GroupList::get();
            $data['smtp'] = SmtpSetup::latest()->get();
            return view('admin.emailCompose.index',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
        
    }

    public function add(Request $request)
    {
        
        try
        {
            $validator = Validator::make($request->all(), [
                'email_from' => 'required|string|email|max:255',
                // 'email_from' => ['required', 'string', 'email', 'max:255', 'not_equal:choose from email'],
                'email_to' => 'required|array',
                'email_msg' => 'required|string',
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                if($request->hasFile('attachment')) 
                {
                    //delete exists attachment file
                    $arrayAttach = json_decode($request->no_attachment);
                    if(!empty($arrayAttach))
                    {
                        for($i = 0; $i< count($arrayAttach); $i++)
                        {
                            $image_path = public_path()."/emailFile/".$arrayAttach[$i];
                            if(file_exists($image_path))
                            {
                                @unlink($image_path);
                            } 
                        }
                    }

                    for($i = 0; $i< count($request->attachment); $i++)
                    {
                        $image = $request->file('attachment')[$i];
                        $name = date('d-m-Y-H').time().$i.'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('/emailFile');
                        $image->move($destinationPath, $name);
                        $allName[] = $name;

                        //this file only for testing email
                        $attachmentPath[] = public_path("emailFile/".$name); 
                        $attachmentName[] = $name;
                    }  
                    
                    $attachment = json_encode($allName);
                }
                else
                {
                    $arrayAttach = json_decode($request->no_attachment);
                    $attachment = $request->no_attachment;
                    if(!empty($arrayAttach))
                    {
                        for($i=0; $i<count($arrayAttach); $i++)
                        {
                            $attachmentPath[] = public_path("emailFile/".$arrayAttach[$i]); 
                        }
                    }
                    else
                    {
                        $attachmentPath = "";
                    }
                    $attachmentName =  $arrayAttach;
                }

                if($request->email_cc == [null])
                {
                    $email_cc = [];
                }
                else
                {
                    $explodeCc = explode(",",$request->email_cc[0]);
                    for($i=0; $i<count($explodeCc); $i++)
                    {
                        if(!empty($explodeCc[$i]))
                        {
                            $email_cc[] = trim($explodeCc[$i]," ");
                        }
                    }
                }

                if($request->email_to == [null])
                {
                    $email_to = "";
                }
                else
                {
                    $email_to = json_encode($request->email_to);
                }
            }
            
            if(!empty($request->test_email))
            {
                session()->put("email_from",$request->email_from);
                session()->put("email_to",$email_to);
                session()->put("email_cc",$email_cc);
                session()->put("email_subject",$request->email_subject);
                session()->put("email_msg",$request->email_msg);
                session()->put("test_email",$request->test_email);
                session()->put("attachment",$attachment);
            

                $requestMsg = $request->email_msg;
                $pattern = '/\bUIC_\w+/i';
                preg_match_all($pattern, $requestMsg, $matches);
                $cusPriceListName = $matches[0];
                for($i=0; $i<count($cusPriceListName); $i++)
                {
                    $priceListExplode = explode('_',$cusPriceListName[$i]);
                    $priceListShortNameFind[] = $priceListExplode[1];
                }

                // return $cusPriceListName;
                $cusMasterShortName = ['bp_code','bp_name',"mobile_phone",'email_id','credit_limit','account_balance_in_sc','default_shipping','default_billing','bp_type'];
                for($i=0; $i<count($cusMasterShortName); $i++)
                {
                    // return $request->email_msg;
                    preg_match('~\\b' . $cusMasterShortName[$i] . '\\b~i', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusMasterShortNameFind[] = $matches[0];
                    }
                }
                // return $cusMasterShortNameFind;

                $cusContactShortName = ['contact_person_name','con_mobile_phone','con_email_id'];
                for($i=0; $i<count($cusContactShortName); $i++)
                {
                    preg_match('~\\b' . $cusContactShortName[$i] . '\\b~i', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        if($matches[0] == "con_mobile_phone")
                        {
                            $cusContactShortNameFind[] = "mobile_phone";
                        }
                        elseif($matches[0] == "con_email_id")
                        {
                            $cusContactShortNameFind[] = "email_id";
                        }
                        else
                        {
                            $cusContactShortNameFind[] = $matches[0];
                        }
                    }
                }

                // return $cusContactShortNameFind;

                $cusAddressShortName = ['address','row_num','street','block','location_city','location_state','location_country','location_postal_code','address_type','gstin'];
                for($i=0; $i<count($cusAddressShortName); $i++)
                {
                    preg_match('/'. $cusAddressShortName[$i] . '/', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusAddressShortNameFind[] = $matches[0];
                    }
                }
              
                //if shortcut name available than replace with data
                $group_code = [];
                foreach($request->email_to as $groupId)
                {
                    $groupData = GroupList::where("id",$groupId)->first();
                    $arrayBpCode = json_decode($groupData->customer_id);
                    for($i=0; $i<count($arrayBpCode); $i++)
                    {
                        $GpData = Customer_master::where("bp_code",$arrayBpCode[$i])->first();
                        if(!empty($GpData))
                        {
                            $group_code[$arrayBpCode[$i]] = $GpData->group_code;
                        }
                    }
                }
                $allCusId = array_keys(array_unique($group_code));
                
                
                if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                {
                    $allShortNameData = [];
                    //customer master data
                    if (!empty($cusMasterShortNameFind))
                    {   
                        // return $cusMasterShortNameFind[0];
                        $cusMasEmptyArray = [];
                        for ($i = 0; $i < count($cusMasterShortNameFind); $i++)
                        {
                            $allShortNameData[] = $cusMasterShortNameFind[$i];

                            for ($j = 0; $j < count($allCusId); $j++)
                            {
                                if (!isset($cusMasEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusMasEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusMasData = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusMasFindingName = $cusMasterShortNameFind[$i];
                                if(!empty($cusMasData->$cusMasFindingName)) 
                                {
                                    $innerCusMasArray = $cusMasData->$cusMasFindingName;
                                } 
                                else 
                                {
                                    $innerCusMasArray = "";
                                }

                                $cusMasEmptyArray[$allCusId[$j]][$cusMasFindingName] = $innerCusMasArray;
                            }
                        }
                        // return $cusMasEmptyArray;
                    } 
              
                    //customer contact data
                    if(!empty($cusContactShortNameFind)) 
                    {
                        $cusConEmptyArray = [];
                    
                        for($i = 0; $i < count($cusContactShortNameFind); $i++) 
                        {
                            if($cusContactShortNameFind[$i] == "mobile_phone")
                            {
                                $allShortNameData[] = "con_mobile_phone";
                                $searchName = "con_mobile_phone";
                            }
                            elseif($cusContactShortNameFind[$i] == "email_id")
                            {
                                $allShortNameData[] = "con_email_id";
                                $searchName = "con_email_id";
                            }
                            else
                            {
                                $allShortNameData[] = $cusContactShortNameFind[$i];
                                $searchName = $cusContactShortNameFind[$i];
                            }
                           
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusConEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusConEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                // $cusBpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusConData = Customer_contact::where('bp_code', $allCusId[$j])->first();
                                $cusConFindingName = $cusContactShortNameFind[$i];
                                if(!empty($cusConData->$cusConFindingName)) 
                                {
                                    // $cusConData->$cusConFindingName;
                                    $innerArray = $cusConData->$cusConFindingName;
                                } 
                                else 
                                {
                                    $innerArray = "";
                                }
                               
                                $cusConEmptyArray[$allCusId[$j]][$searchName] = $innerArray;
                                
                            }
                        }
                        // return $cusConEmptyArray;
                    }
                    
                    // return $cusAddressShortNameFind;
                    //customer address data
                    if (!empty($cusAddressShortNameFind)) 
                    {
                        $cusAddEmptyArray = [];
                    
                        for($i = 0; $i < count($cusAddressShortNameFind); $i++) 
                        {
                            $allShortNameData[] = $cusAddressShortNameFind[$i];
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusAddEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusAddEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusAddBpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusAddData = Customer_address::where('address', $cusAddBpCode->default_billing)->first();
                               
                                $cusAddFindingName = $cusAddressShortNameFind[$i];
                                if(!empty($cusAddData->$cusAddFindingName)) 
                                {
                                    $state = State_master::where('Code',$cusAddData->location_state)->first();
                                    $country = Country::where('sortname',$cusAddData->location_country)->first();

                                    if($cusAddFindingName == 'location_state')
                                    {
                                        $innerAddArray = $state->name;
                                    }
                                    elseif($cusAddFindingName == 'location_country')
                                    {
                                        $innerAddArray = $country->name; 
                                    }
                                    else
                                    {
                                        $innerAddArray = $cusAddData->$cusAddFindingName;
                                    }
                                } 
                                else 
                                {
                                    $innerAddArray = "";
                                }

                                $cusAddEmptyArray[$allCusId[$j]][$cusAddFindingName] = $innerAddArray;
                            }
                        }
                        // return $cusAddEmptyArray;
                    }


                    //price list data
                    if (!empty($cusPriceListName)) 
                    {
                        // return $priceListShortNameFind;
                        $cusPriceEmptyArray = [];
                    
                        for($i = 0; $i < count($cusPriceListName); $i++) 
                        {
                            $allShortNameData[] = $cusPriceListName[$i];
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusPriceEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusPriceEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusPriceGpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusPriceGpNo = Customer_group::where('group_no', $cusPriceGpCode->group_code)->first();
                                $cusPriceListData = Price_list::where('U_CGroup', $cusPriceGpNo->group_name)->where("U_ItemCode",$priceListShortNameFind[$i])->first();
                                $cusPriceFindingName = $priceListShortNameFind[$i];
                                if(!empty($cusPriceListData)) 
                                {
                                    $tag = "<a href='".$cusPriceListData->U_Link."' target='_blank'>".$cusPriceListData->U_ListName."</a>";
                                    $innerPriceArray = $tag;
                                } 
                                else 
                                {
                                    $innerPriceArray = "";
                                }

                                $cusPriceEmptyArray[$allCusId[$j]][$cusPriceListName[$i]] = $innerPriceArray;
                            }
                        }
                        // return $cusPriceEmptyArray;
                    }


                    if (!empty($cusPriceEmptyArray) || !empty($cusAddEmptyArray) || !empty($cusConEmptyArray) || !empty($cusMasEmptyArray)) 
                    {
                        $allFetchedData = [];
                    
                        foreach ($allCusId as $key => $customerId) 
                        {
                            // return $customerId;
                            $allFetchedData[$customerId] = [];
                    
                    
                            if (!empty($cusPriceEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusPriceEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusAddEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusAddEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusConEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusConEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusMasEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusMasEmptyArray[$customerId];
                            }
                        }
                        
                  
                        //replacing short name with data
                        for($k=0; $k< count($allFetchedData); $k++)
                        {
                            $cusMsg = $request->email_msg;
                            $perCusData = $allFetchedData[$allCusId[$k]];

                            for ($l = 0; $l < count($perCusData); $l++)
                            {
                                $search = $allShortNameData[$l];
                                $replacement = $perCusData[$search];

                                $cusMsg = preg_replace_callback(
                                    "/\b" . preg_quote($search, '/') . "\b/i", 
                                    function ($matches) use ($replacement, $search) {
                                        return $matches[0] === $search ? $replacement : $matches[0];
                                    }, 
                                    $cusMsg
                                );
                            }

                        
                            //if cc mail available send to cc mail
                            if(!empty($request->email_cc))
                            {
                                $explodeCc = explode(",",$request->email_cc[0]);
                                for($i=0; $i<count($explodeCc); $i++)
                                {
                                    if(!empty($explodeCc[$i]))
                                    {
                                        $ccMail = $explodeCc[$i];
                                        $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg], function($message) use($request,$ccMail,$attachmentPath,$attachmentName){
                                            $message->to($ccMail); 
                                            $message->subject($request->email_subject);
                                            if(!empty($attachmentPath) && !empty($attachmentName))
                                            {
                                                for($m = 0; $m< count($attachmentPath); $m++)
                                                {
                                                    $message->attach($attachmentPath[$m], array(
                                                        'as' => $attachmentName[$m],)
                                                    );
                                                }
                                            }
                                        });
                                    }
                                }
                                
                            }

                            //sending email to test email id
                            $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg,"attachment" => $attachment], function($message) use($request,$attachmentPath,$attachmentName){
                                $message->to($request->test_email);
                                $message->subject($request->email_subject);
                                if(!empty($attachmentPath) && !empty($attachmentName))
                                {
                                    for($m = 0; $m< count($attachmentPath); $m++)
                                    {
                                        $message->attach($attachmentPath[$m], array(
                                            'as' => $attachmentName[$m],)
                                        );
                                    }
                                }
                                
                            });
                        }
                        return back()->with("success","Test email sent successfully!");
                    }  
                }
                else
                {
                    for($i=0; $i<count($allCusId); $i++)
                    {
                        //if cc mail available send to cc mail
                        if(!empty($request->email_cc))
                        {
                            $explodeCc = explode(",",$request->email_cc[0]);
                            for($j=0; $j<count($explodeCc); $j++)
                            {
                                if(!empty($explodeCc[$j]))
                                {
                                    $ccMail = $explodeCc[$j];
                                    $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $request->email_msg], function($message) use($request,$ccMail,$attachmentPath,$attachmentName){
                                        $message->to($ccMail);
                                        if(!empty($attachmentPath) && !empty($attachmentName))
                                        {
                                            for($m = 0; $m< count($attachmentPath); $m++)
                                            {
                                                $message->attach($attachmentPath[$m], array(
                                                    'as' => $attachmentName[$m],)
                                                );
                                            }
                                        }
                                        $message->subject($request->email_subject);
                                    });
                                }
                            } 
                        }

                        //sending email to test email id
                        $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $request->email_msg], function($message) use($request,$attachmentPath,$attachmentName){
                        $message->to($request->test_email);
                        if(!empty($attachmentPath) && !empty($attachmentName))
                        {
                            for($m = 0; $m< count($attachmentPath); $m++)
                            {
                                $message->attach($attachmentPath[$m], array(
                                    'as' => $attachmentName[$m],)
                                );
                            }
                        }
                        $message->subject($request->email_subject);
                    });
                        
                    }
                    if($sendmail)
                    {
                        return back()->with("success","Test email sent successfully!");
                    }
                }
            }
            else
            {
                $validator = Validator::make($request->all(), [
                    'schedule_time' => 'required|string'
                ]);
                if ($validator->fails())
                { 
                    return back()->withErrors($validator)->withInput();
                }
                else
                {

                    foreach($request->email_to as $toData)
                    {
                        $groupList[] = GroupList::where("id",$toData)->first();
                    }

                    foreach($groupList as $key => $grpList)
                    {
                        $valid = [];
                        $inValid = [];
                        $grp_id[$key] = $grpList->id;
                        $grpAllData = json_decode($grpList->customer_id);
                        for($i=0; $i<count($grpAllData); $i++)
                        {
                            $allCusData = Customer_master::where("bp_code",$grpAllData[$i])->first();
                            $cus_email = $allCusData->email_id;
                            if(!empty($cus_email))
                            {
                                $emailValidator = new EmailValidator();
                                if($emailValidator->isValid($cus_email, new RFCValidation())) 
                                {
                                    $valid[] = 1;
                                } 
                                else 
                                {
                                    $inValid[] = 0;  
                                }
                            }
                            else{
                                $inValid[] = 0; 
                            }
                        }

                        if(count($inValid) > 0)
                        {
                            $allInvalid = count($inValid);
                        }
                    }

                    if(!empty($allInvalid))
                    {
                        return back()->with("error","Please correct customer email!");
                    }

                    $emailCompose = new EmailCompose;
                    $emailCompose->from = $request->email_from;
                    $emailCompose->to = $email_to;
                    $emailCompose->cc = json_encode($email_cc);
                    $emailCompose->subject = $request->email_subject;
                    $emailCompose->message = $request->email_msg;
                    $emailCompose->attachment = $attachment;
                    $emailCompose->schedule_time = $request->schedule_time;
                    $emailCompose->add_by = Auth::user()->id;
                    if($emailCompose->save())
                    {
                        session()->forget("email_from");
                        session()->forget("email_to");
                        session()->forget("email_cc");
                        session()->forget("email_subject");
                        session()->forget("email_msg");
                        session()->forget("test_email");
                        session()->forget("attachment");
                        return back()->with("success","Email compose scheduled successfully !");
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }  
    }

    public function view()
    {
        try{
            
            $data['title'] = "Scheduled Email";
            $data['totalList'] = EmailCompose::count();
            $allCompose = EmailCompose::get();
            
            for($i=0; $i< count($allCompose); $i++)
            {
                $allGroupId = json_decode($allCompose[$i]->to);
                for($j=0; $j< count($allGroupId); $j++)
                {
                    $gpName = GroupList::where("id", $allGroupId[$j])->select("group_name")->first();
                    if(!empty($gpName->group_name))
                    {
                        $allGpName[] = $gpName->group_name;
                    }
                    else
                    {
                        $allGpName[] = "";
                    }
                }

                EmailCompose::where('id',$allCompose[$i]->id)->update([
                    "to_group_name" => $allGpName
                ]);

                $allGpName = [];
            }

            //Start - checking failed email status
            $emailDatas = EmailCompose::where('add_by',Auth::user()->id)->get();
            if(count($emailDatas) != 0)
            {
                foreach($emailDatas as $emailData)
                {
                    $jobData = Job_batch::where('id',$emailData->job_batch_id)->first();
                    if(empty($jobData))
                    {
                        EmailCompose::where("id",$emailData->id)->update([
                            'job_batch_id' => 0,
                            'total_cus' => 0,
                            'total_jobs' => 0,
                            'pending_jobs' => 0,
                            'failed_jobs' => 0,
                        ]);
                    }
                    else
                    {
                        //fjId = failed job id
                        $fjId = json_decode($jobData->failed_job_ids);
                        if(count($fjId) != 0)
                        {
                            for($j=0; $j<count($fjId); $j++)
                            {
                                $fj = Failed_jobs::where('uuid',$fjId[$j])->first();
                                if(!empty($fj))
                                {
                                    $ffj[] = $fj;
                                }
                            }

                            if(!empty($ffj))
                            {
                                EmailCompose::where("id",$emailData->id)->update([
                                    'total_jobs' => $jobData->total_jobs,
                                    'pending_jobs' => $jobData->pending_jobs,
                                    'failed_jobs' => $jobData->failed_jobs,
                                ]);
                            }
                            else
                            {
                                EmailCompose::where("id",$emailData->id)->update([
                                    'status' => 2,
                                    'total_jobs' => $jobData->total_jobs,
                                    'pending_jobs' => 0,
                                    'failed_jobs' => 0,
                                ]);
                            }
                        }
                        else
                        {
                            if($jobData->pending_jobs == 0)
                            {
                                EmailCompose::where("id",$emailData->id)->update([
                                    'status' => 2,
                                    'total_jobs' => $jobData->total_jobs,
                                    'pending_jobs' => $jobData->pending_jobs,
                                    'failed_jobs' => 0,
                                ]);
                            }
                            else
                            {
                                EmailCompose::where("id",$emailData->id)->update([
                                    'total_jobs' => $jobData->total_jobs,
                                    'pending_jobs' => $jobData->pending_jobs,
                                    'failed_jobs' => $jobData->failed_jobs,
                                ]);
                            } 
                        }
                    }
                }
            }
            //End - checking failed email status

            $data['list'] = EmailCompose::latest()->paginate(10);
            return view('admin.emailCompose.view',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    //creating job batches
    public function schedule()
    {
        try
        {
          
            $dateTime = Carbon::now();
            $cTime = $dateTime->toDateTimeString();
            $scheduleEmail = EmailCompose::where('schedule_time', '<=', $cTime)->where("status",0)->where("sending_status",1)->get();
            for($i=0; $i<count($scheduleEmail); $i++)
            {
                
                $fromEmail = $scheduleEmail[$i]->from;
                $emailComId = $scheduleEmail[$i]->id;
                $cc = $scheduleEmail[$i]->cc;
                $subject = $scheduleEmail[$i]->subject;
                $msg = $scheduleEmail[$i]->message;
                
                $arrayAttachFile = json_decode($scheduleEmail[$i]->attachment);
                if(!empty($arrayAttachFile))
                {
                    for($j=0; $j<count($arrayAttachFile); $j++)
                    {
                        $attachmentPath[] = public_path("emailFile/".$arrayAttachFile[$j]); 
                        $attachmentName[] = $arrayAttachFile[$j];
                    }
                }
                else
                {
                    $attachmentPath = [];
                    $attachmentName = [];
                }


                $requestMsg = $msg;
                $pattern = '/\bUIC_\w+/i';
                preg_match_all($pattern, $requestMsg, $matches);
                $cusPriceListName = $matches[0];
                for($j=0; $j<count($cusPriceListName); $j++)
                {
                    $priceListExplode = explode('_',$cusPriceListName[$j]);
                    $priceListShortNameFind[] = $priceListExplode[1];
                }

                $cusMasterShortName = ['bp_code','bp_name',"mobile_phone",'email_id','credit_limit','account_balance_in_sc','default_shipping','default_billing','bp_type'];
                for($j=0; $j<count($cusMasterShortName); $j++)
                {
                    preg_match('~\\b' . $cusMasterShortName[$j]. '\\b~i', $msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusMasterShortNameFind[] = $matches[0];
                    }
                }

            
                $cusContactShortName = ['contact_person_name','con_mobile_phone','con_email_id'];
                for($j=0; $j<count($cusContactShortName); $j++)
                {
                    preg_match('~\\b' .$cusContactShortName[$j]. '\\b~i', $msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        if($matches[0] == "con_mobile_phone")
                        {
                            $cusContactShortNameFind[] = "mobile_phone";
                        }
                        elseif($matches[0] == "con_email_id")
                        {
                            $cusContactShortNameFind[] = "email_id";
                        }
                        else
                        {
                            $cusContactShortNameFind[] = $matches[0];
                        }
                    }
                }


                $cusAddressShortName = ['address','row_num','street','block','location_city','location_state','location_country','location_postal_code','address_type','gstin'];
                for($j=0; $j<count($cusAddressShortName); $j++)
                {
                    preg_match("/".$cusAddressShortName[$j].'/', $msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusAddressShortNameFind[] = $matches[0];
                    }
                }

                //if shortcut name available than replace with data
                $allTo = json_decode($scheduleEmail[$i]->to);
                for($j=0; $j < count($allTo); $j++)
                {
                    $groupData = GroupList::where("id",$allTo[$j])->first();
                    $allGpData[] = json_decode($groupData->customer_id);
                }
                
                //finding multiple group or single
                $array_merged = [];
                for($j=0; $j<count($allGpData); $j++)
                {
                    $array_merged = array_merge($array_merged,$allGpData[$j]);
                }
                
                $allCusId = array_values(array_unique($array_merged));

                if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                {
                    
                    $allShortNameData = [];

                    //customer master data
                    if (!empty($cusMasterShortNameFind))
                    {   
                        $cusMasEmptyArray = [];
                        for($j = 0; $j < count($cusMasterShortNameFind); $j++)
                        {
                            $allShortNameData[] = $cusMasterShortNameFind[$j];

                            for($k = 0; $k < count($allCusId); $k++) 
                            {
                                if (!isset($cusMasEmptyArray[$allCusId[$k]])) 
                                {
                                    $cusMasEmptyArray[$allCusId[$k]] = [];
                                }
                    
                                $cusMasData = Customer_master::where('bp_code', $allCusId[$k])->first();
                                $cusMasFindingName = $cusMasterShortNameFind[$j];
                                if(!empty($cusMasData->$cusMasFindingName)) 
                                {
                                    $innerCusMasArray = $cusMasData->$cusMasFindingName;
                                } 
                                else 
                                {
                                    $innerCusMasArray = "";
                                }

                                $cusMasEmptyArray[$allCusId[$k]][$cusMasFindingName] = $innerCusMasArray;
                            }
                        }
                    } 

                    //customer contact data
                    if (!empty($cusContactShortNameFind)) 
                    {
                        $cusConEmptyArray = [];
                    
                        for($j = 0; $j < count($cusContactShortNameFind); $j++) 
                        {
                            if($cusContactShortNameFind[$j] == "mobile_phone")
                            {
                                $allShortNameData[] = "con_mobile_phone";
                                $searchName = "con_mobile_phone";
                            }
                            elseif($cusContactShortNameFind[$j] == "email_id")
                            {
                                $allShortNameData[] = "con_email_id";
                                $searchName = "con_email_id";
                            }
                            else
                            {
                                $allShortNameData[] = $cusContactShortNameFind[$j];
                                $searchName = $cusContactShortNameFind[$j];
                            }
                            
                            for($k = 0; $k < count($allCusId); $k++) 
                            {
                                if(!isset($cusConEmptyArray[$allCusId[$k]])) 
                                {
                                    $cusConEmptyArray[$allCusId[$k]] = [];
                                }
                    
                                // $cusBpCode = Customer_master::where('bp_code', $allCusId[$k])->first();
                                $cusConData = Customer_contact::where('bp_code', $allCusId[$k])->first();
                                $cusConFindingName = $cusContactShortNameFind[$j];
                                if(!empty($cusConData->$cusConFindingName)) 
                                {
                                    $innerArray = $cusConData->$cusConFindingName;
                                } 
                                else 
                                {
                                    $innerArray = "";
                                }

                                $cusConEmptyArray[$allCusId[$k]][$searchName] = $innerArray; 
                            }
                        }
                    }
                    

                    //customer address data
                    if (!empty($cusAddressShortNameFind)) 
                    {
                        $cusAddEmptyArray = [];
                    
                        for($j = 0; $j < count($cusAddressShortNameFind); $j++) 
                        {
                            $allShortNameData[] = $cusAddressShortNameFind[$j];
                            for($k = 0; $k < count($allCusId); $k++) 
                            {
                                if(!isset($cusAddEmptyArray[$allCusId[$k]])) 
                                {
                                    $cusAddEmptyArray[$allCusId[$k]] = [];
                                }
                    
                                $cusAddBpCode = Customer_master::where('bp_code', $allCusId[$k])->first();
                                $cusAddData = Customer_address::where('address', $cusAddBpCode->default_billing)->first();
                                $cusAddFindingName = $cusAddressShortNameFind[$j];
                                if(!empty($cusAddData->$cusAddFindingName)) 
                                {
                                    $state = State_master::where('Code',$cusAddData->location_state)->first();
                                    $country = Country::where('sortname',$cusAddData->location_country)->first();

                                    if($cusAddFindingName == 'location_state')
                                    {
                                        $innerAddArray = $state->name;
                                    }
                                    elseif($cusAddFindingName == 'location_country')
                                    {
                                        $innerAddArray = $country->name; 
                                    }
                                    else
                                    {
                                        $innerAddArray = $cusAddData->$cusAddFindingName;
                                    }
                                } 
                                else 
                                {
                                    $innerAddArray = "";
                                }

                                $cusAddEmptyArray[$allCusId[$k]][$cusAddFindingName] = $innerAddArray;
                            }
                        }
                    }


                    //price list data
                    if (!empty($cusPriceListName)) 
                    {
                        $cusPriceEmptyArray = [];
                    
                        for($j = 0; $j < count($cusPriceListName); $j++) 
                        {
                            $allShortNameData[] = $cusPriceListName[$j];
                            for($k = 0; $k < count($allCusId); $k++) 
                            {
                                if(!isset($cusPriceEmptyArray[$allCusId[$k]])) 
                                {
                                    $cusPriceEmptyArray[$allCusId[$k]] = [];
                                }
                    
                                $cusPriceGpCode = Customer_master::where('bp_code', $allCusId[$k])->first();
                                $cusPriceGpNo = Customer_group::where('group_no', $cusPriceGpCode->group_code)->first();
                                $cusPriceListData = Price_list::where('U_CGroup', $cusPriceGpNo->group_name)->where("U_ItemCode",$priceListShortNameFind[$j])->first();
                                $cusPriceFindingName = $priceListShortNameFind[$j];
                                if(!empty($cusPriceListData)) 
                                {
                                    $tag = "<a href='".$cusPriceListData->U_Link."' target='_blank'>".$cusPriceListData->U_ListName."</a>";
                                    $innerPriceArray = $tag;
                                } 
                                else 
                                {
                                    $innerPriceArray = "";
                                }

                                $cusPriceEmptyArray[$allCusId[$k]][$cusPriceListName[$j]] = $innerPriceArray;
                            }
                        }
                    }



                    if(!empty($cusPriceEmptyArray) || !empty($cusAddEmptyArray) || !empty($cusConEmptyArray) || !empty($cusMasEmptyArray)) 
                    {
                        $allFetchedData = [];
                        foreach($allCusId as $customerId) 
                        {
                            $allFetchedData[$customerId] = [];
                            
                            if (!empty($cusPriceEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusPriceEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusAddEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusAddEmptyArray[$customerId];
                            }
                            
                            if (!empty($cusConEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusConEmptyArray[$customerId];
                            }
                            
                            if (!empty($cusMasEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusMasEmptyArray[$customerId];
                            }
                        }
                        
                        //Chunking file
                        $allCusIdChunks = array_chunk($allCusId, 100);
                        $allFetchedDataChunks = array_chunk($allFetchedData, 100, true);
                        $batch = Bus::batch([])->dispatch();

                        for($m=0; $m<count($allCusIdChunks); $m++) 
                        {
                            $batch->add(new ScheduleEmail($allCusIdChunks[$m],$allFetchedDataChunks[$m],$emailComId,$allShortNameData,$msg,$cc,$subject,$fromEmail,$attachmentPath,$attachmentName));
                        }

                        EmailCompose::where('id', $emailComId)->update(['job_batch_id' => $batch->id,'total_cus' => count($allCusId)]);
                        
                    }  
                }
                else
                {
                    //Chunking file
                    $allCusIdChunks = array_chunk($allCusId, 100);
                    $batch = Bus::batch([])->dispatch();
                    $allFetchedDataChunks = [];
                    $allShortNameData = [];
                    for($m=0; $m<count($allCusIdChunks); $m++) 
                    {
                        $batch->add(new ScheduleEmail($allCusIdChunks[$m],$allFetchedDataChunks,$emailComId,$allShortNameData,$msg,$cc,$subject,$fromEmail,$attachmentPath,$attachmentName));
                    }
                    
                    EmailCompose::where('id', $emailComId)->update(['job_batch_id' => $batch->id, 'total_cus' => count($allCusId)]);
                }
                
                //doing empty array when first loop completed, 
                $allGpData = [];
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    //showing all information about of sending email 
    public function sentDetails($id)
    {
        try{
            $data['title'] = "Email Details";
            $emailData = EmailCompose::where('id',$id)->where('add_by',Auth::user()->id)->first();
            if(!empty($emailData))
            {
                $jobData = Job_batch::where('id',$emailData->job_batch_id)->first();
                if(empty($jobData))
                {
                    EmailCompose::where("id",$id)->update([
                        'job_batch_id' => 0,
                        'total_cus' => 0,
                        'total_jobs' => 0,
                        'pending_jobs' => 0,
                        'failed_jobs' => 0,
                    ]);
                }
                else
                {
                    //fjId = failed job id
                    $fjId = json_decode($jobData->failed_job_ids);
                    if(count($fjId) != 0)
                    {
                        for($j=0; $j<count($fjId); $j++)
                        {
                            $fj = Failed_jobs::where('uuid',$fjId[$j])->first();
                            if(!empty($fj))
                            {
                                $ffj[] = $fj;
                            }
                        }

                        if(!empty($ffj))
                        {
                            EmailCompose::where("id",$id)->update([
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => $jobData->pending_jobs,
                                'failed_jobs' => $jobData->failed_jobs,
                            ]);
                        }
                        else
                        {
                            EmailCompose::where("id",$id)->update([
                                'status' => 2,
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => 0,
                                'failed_jobs' => 0,
                            ]);
                        }
                    }
                    else
                    {
                        if($jobData->pending_jobs == 0)
                        {
                            EmailCompose::where("id",$id)->update([
                                'status' => 2,
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => $jobData->pending_jobs,
                                'failed_jobs' => 0,
                            ]);
                        }
                        else
                        {
                            EmailCompose::where("id",$id)->update([
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => $jobData->pending_jobs,
                                'failed_jobs' => $jobData->failed_jobs,
                            ]);
                        }
                    }
                }

                $data['list'] = EmailCompose::where('id',$id)->first();
                return view("admin.emailCompose.emailDetails",$data);

            }
            else
            {
                return back()->with("error","Invalid Access!");   
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        try{
            $data['title'] = "Edit Scheduled Email";
            $emailData = EmailCompose::where('id',$id)->where('add_by',Auth::user()->id)->first();
            if(!empty($emailData))
            {
                $data['groupList'] = GroupList::get();
                $data['smtp'] = SmtpSetup::latest()->get();
                $data['list'] = $emailData;
                return view("admin.emailCompose.edit",$data);   
            }
            else
            {
                return back()->with("error","Invalid Access!");   
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function update(Request $request,$id)
    {
        try
        { 
            $validator = Validator::make($request->all(), [
                'email_from' => 'required|string|email|max:255',
                'email_to' => 'required|array',
                'email_msg' => 'required|string',
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                if($request->hasFile('attachment')) 
                {
                    if(!empty($request->no_attachment))
                    {
                        $dbAttachFile = json_decode($request->no_attachment);
                        for($i = 0; $i< count($dbAttachFile); $i++)
                        {
                            //delete exists attachment file
                            $image_path = public_path()."/emailFile/".$dbAttachFile[$i];
                            if(file_exists($image_path))
                            {
                                @unlink($image_path);
                            } 
                        }
                    }

                    for($i = 0; $i< count($request->attachment); $i++)
                    {
                        $image = $request->file('attachment')[$i];
                        $name = date('d-m-Y-H').time().$i.'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('/emailFile');
                        $image->move($destinationPath, $name);
                        $allName[] = $name;

                        //this file only for testing email
                        $attachmentPath[] = public_path("emailFile/".$name); 
                        $attachmentName[] = $name;
                    }   

                    $attachment = json_encode($allName);
                }
                else
                {
                    if(!empty($request->no_attachment))
                    {
                        $arrayAttachment = json_decode($request->no_attachment);
                        for($i=0; $i<count($arrayAttachment); $i++)
                        {
                            //this file only for testing email
                            $attachmentPath[] = public_path("emailFile/".$arrayAttachment[$i]); 
                            $attachmentName[] = $arrayAttachment[$i];
                        }

                        $attachment =  $request->no_attachment;
                    }
                    else
                    {
                        $attachment = "";
                        $attachmentPath = ""; 
                        $attachmentName = "";
                    }
                }

                if($request->email_cc == [null])
                {
                    $email_cc = [];
                }
                else
                {
                    $explodeCc = explode(",",$request->email_cc[0]);
                    for($i=0; $i<count($explodeCc); $i++)
                    {
                        if(!empty($explodeCc[$i]))
                        {
                            $email_cc[] = trim($explodeCc[$i]," ");
                        }
                    }
                }

                if($request->email_to == [null])
                {
                    $email_to = "";
                }
                else
                {
                    $email_to = json_encode($request->email_to);
                }
            }

            if(!empty($request->test_email))
            {
                session()->put("update_email_from",$request->email_from);
                session()->put("update_email_to",$email_to);
                session()->put("update_email_cc",$email_cc);
                session()->put("update_email_subject",$request->email_subject);
                session()->put("update_email_msg",$request->email_msg);
                session()->put("update_test_email",$request->test_email);
                session()->put("update_attachment",$attachment);


                $requestMsg = $request->email_msg;
                $pattern = '/\bUIC_\w+/i';
                preg_match_all($pattern, $requestMsg, $matches);
                $cusPriceListName = $matches[0];
                for($i=0; $i<count($cusPriceListName); $i++)
                {
                    $priceListExplode = explode('_',$cusPriceListName[$i]);
                    $priceListShortNameFind[] = $priceListExplode[1];
                }

                $cusMasterShortName = ['bp_code','bp_name',"mobile_phone",'email_id','credit_limit','account_balance_in_sc','default_shipping','default_billing','bp_type'];
                for($i=0; $i<count($cusMasterShortName); $i++)
                {
                    // return $request->email_msg;
                    preg_match('~\\b' . $cusMasterShortName[$i] . '\\b~i', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusMasterShortNameFind[] = $matches[0];
                    }
                }

                $cusContactShortName = ['contact_person_name','con_mobile_phone','con_email_id'];
                for($i=0; $i<count($cusContactShortName); $i++)
                {
                    preg_match('~\\b' . $cusContactShortName[$i] . '\\b~i', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        if($matches[0] == "con_mobile_phone")
                        {
                            $cusContactShortNameFind[] = "mobile_phone";
                        }
                        elseif($matches[0] == "con_email_id")
                        {
                            $cusContactShortNameFind[] = "email_id";
                        }
                        else
                        {
                            $cusContactShortNameFind[] = $matches[0];
                        }
                    }
                }

                $cusAddressShortName = ['address','row_num','street','block','location_city','location_state','location_country','location_postal_code','address_type','gstin'];
                for($i=0; $i<count($cusAddressShortName); $i++)
                {
                    // /your_regexp_here/i
                    preg_match("/".$cusAddressShortName[$i]."/", $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusAddressShortNameFind[] = $matches[0];
                    }
                }
                
                //if shortcut name available than replace with data
                $group_code = [];
                foreach($request->email_to as $groupId)
                {
                    $groupData = GroupList::where("id",$groupId)->first();
                    $arrayBpCode = json_decode($groupData->customer_id);
                    for($i=0; $i<count($arrayBpCode); $i++)
                    {
                        $GpData = Customer_master::where("bp_code",$arrayBpCode[$i])->first();
                        if(!empty($GpData))
                        {
                            $group_code[$arrayBpCode[$i]] = $GpData->group_code;
                        }
                    }
                }
              
                $allCusId = array_keys(array_unique($group_code));
               
                if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                {
                    $allShortNameData = [];
                    // customer master data
                    if (!empty($cusMasterShortNameFind))
                    {   
                        // return $cusMasterShortNameFind[0];
                        $cusMasEmptyArray = [];
                        for ($i = 0; $i < count($cusMasterShortNameFind); $i++)
                        {
                            $allShortNameData[] = $cusMasterShortNameFind[$i];

                            for ($j = 0; $j < count($allCusId); $j++)
                            {
                                if (!isset($cusMasEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusMasEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusMasData = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusMasFindingName = $cusMasterShortNameFind[$i];
                                if(!empty($cusMasData->$cusMasFindingName)) 
                                {
                                    $innerCusMasArray = $cusMasData->$cusMasFindingName;
                                } 
                                else 
                                {
                                    $innerCusMasArray = "";
                                }

                                $cusMasEmptyArray[$allCusId[$j]][$cusMasFindingName] = $innerCusMasArray;
                            }
                        }
                        // return $cusMasEmptyArray;
                    } 


                    //customer contact data
                    if (!empty($cusContactShortNameFind)) 
                    {
                        $cusConEmptyArray = [];
                    
                        for($i = 0; $i < count($cusContactShortNameFind); $i++) 
                        {
                            if($cusContactShortNameFind[$i] == "mobile_phone")
                            {
                                $allShortNameData[] = "con_mobile_phone";
                                $searchName = "con_mobile_phone";
                            }
                            elseif($cusContactShortNameFind[$i] == "email_id")
                            {
                                $allShortNameData[] = "con_email_id";
                                $searchName = "con_email_id";
                            }
                            else
                            {
                                $allShortNameData[] = $cusContactShortNameFind[$i];
                                $searchName = $cusContactShortNameFind[$i];
                            }
                           
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusConEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusConEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                // $cusBpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusConData = Customer_contact::where('bp_code', $allCusId[$j])->first();
                                $cusConFindingName = $cusContactShortNameFind[$i];
                                if(!empty($cusConData->$cusConFindingName)) 
                                {
                                    $cusConData->$cusConFindingName;
                                    $innerArray = $cusConData->$cusConFindingName;
                                } 
                                else 
                                {
                                    $innerArray = "";
                                }
                               
                                $cusConEmptyArray[$allCusId[$j]][$searchName] = $innerArray;
                                
                            }
                        }
                        // return $cusConEmptyArray;
                    }

                    //customer address data
                    if (!empty($cusAddressShortNameFind)) 
                    {
                        $cusAddEmptyArray = [];
                    
                        for($i = 0; $i < count($cusAddressShortNameFind); $i++) 
                        {
                            $allShortNameData[] = $cusAddressShortNameFind[$i];
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusAddEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusAddEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusAddBpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusAddData = Customer_address::where('address', $cusAddBpCode->default_billing)->first();
                                $cusAddFindingName = $cusAddressShortNameFind[$i];
                                if(!empty($cusAddData->$cusAddFindingName)) 
                                {
                                    $state = State_master::where('Code',$cusAddData->location_state)->first();
                                    $country = Country::where('sortname',$cusAddData->location_country)->first();

                                    if($cusAddFindingName == 'location_state')
                                    {
                                        $innerAddArray = $state->name;
                                    }
                                    elseif($cusAddFindingName == 'location_country')
                                    {
                                        $innerAddArray = $country->name; 
                                    }
                                    else
                                    {
                                        $innerAddArray = $cusAddData->$cusAddFindingName;
                                    }
                                } 
                                else 
                                {
                                    $innerAddArray = "";
                                }

                                $cusAddEmptyArray[$allCusId[$j]][$cusAddFindingName] = $innerAddArray;
                            }
                        }
                        // return $cusAddEmptyArray;
                    }


                    //price list data
                    if (!empty($cusPriceListName)) 
                    {
                        // return $priceListShortNameFind;
                        $cusPriceEmptyArray = [];
                    
                        for($i = 0; $i < count($cusPriceListName); $i++) 
                        {
                            $allShortNameData[] = $cusPriceListName[$i];
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusPriceEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusPriceEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusPriceGpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusPriceGpNo = Customer_group::where('group_no', $cusPriceGpCode->group_code)->first();
                                $cusPriceListData = Price_list::where('U_CGroup', $cusPriceGpNo->group_name)->where("U_ItemCode",$priceListShortNameFind[$i])->first();
                                $cusPriceFindingName = $priceListShortNameFind[$i];
                                if(!empty($cusPriceListData)) 
                                {
                                    $tag = "<a href='".$cusPriceListData->U_Link."' target='_blank'>".$cusPriceListData->U_ListName."</a>";
                                    $innerPriceArray = $tag;
                                } 
                                else 
                                {
                                    $innerPriceArray = "";
                                }

                                $cusPriceEmptyArray[$allCusId[$j]][$cusPriceListName[$i]] = $innerPriceArray;
                            }
                        }
                        // return $cusPriceEmptyArray;
                    }
                

                    if (!empty($cusPriceEmptyArray) || !empty($cusAddEmptyArray) || !empty($cusConEmptyArray) || !empty($cusMasEmptyArray)) 
                    {
                        $allFetchedData = [];
                    
                        foreach ($allCusId as $key => $customerId) 
                        {
                            $allFetchedData[$customerId] = [];
                    
                            if (!empty($cusPriceEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusPriceEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusAddEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusAddEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusConEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusConEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusMasEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusMasEmptyArray[$customerId];
                            }
                        }
                        
                        //replacing short name with data
                        for($k=0; $k< count($allFetchedData); $k++)
                        {
                            $cusMsg = $request->email_msg;
                            $perCusData = $allFetchedData[$allCusId[$k]];

                            for ($l = 0; $l < count($perCusData); $l++)
                            {
                                $search = $allShortNameData[$l];
                                $replacement = $perCusData[$search];

                                $cusMsg = preg_replace_callback(
                                    "/\b" . preg_quote($search, '/') . "\b/i", 
                                    function ($matches) use ($replacement, $search) {
                                        return $matches[0] === $search ? $replacement : $matches[0];
                                    }, 
                                    $cusMsg
                                );
                            }


                            //if cc mail available send to cc mail
                            if(!empty($request->email_cc))
                            {
                                $explodeCc = explode(",",$request->email_cc[0]);
                                for($i=0; $i<count($explodeCc); $i++)
                                {
                                    if(!empty($explodeCc[$i]))
                                    {
                                        $ccMail = trim($explodeCc[$i]." ");
                                        $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg], function($message) use($request,$ccMail,$attachmentPath,$attachmentName){
                                            $message->to($ccMail); 
                                            $message->subject($request->email_subject);
                                            if(!empty($attachmentPath) && !empty($attachmentName))
                                            {
                                                for($m = 0; $m< count($attachmentPath); $m++)
                                                {
                                                    $message->attach($attachmentPath[$m], array(
                                                        'as' => $attachmentName[$m],)
                                                    );
                                                }
                                            }
                                        });
                                    }
                                }
                            }

                            //sending email to test email id
                            $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg,"attachment" => $attachment], function($message) use($request,$attachmentPath,$attachmentName){
                                $message->to($request->test_email);
                                if(!empty($attachmentPath) && !empty($attachmentName))
                                {
                                    for($m = 0; $m< count($attachmentPath); $m++)
                                    {
                                        $message->attach($attachmentPath[$m], array(
                                            'as' => $attachmentName[$m],)
                                        );
                                    }
                                }
                                $message->subject($request->email_subject);
                            });
                        }
                        
                        return back()->with("success","Test email sent successfully!");
                    }  
                }
                else
                {
                    for($i=0; $i<count($allCusId); $i++)
                    {
                        //if cc mail available send to cc mail
                        if(!empty($request->email_cc))
                        {
                            $explodeCc = explode(",",$request->email_cc[0]);
                            for($j=0; $j<count($explodeCc); $j++)
                            {
                                if(!empty($explodeCc[$j]))
                                {
                                    $ccMail = trim($explodeCc[$j]." ");
                                    $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $request->email_msg], function($message) use($request,$ccMail,$attachmentPath,$attachmentName){
                                        $message->to($ccMail);
                                        if(!empty($attachmentPath) && !empty($attachmentName))
                                        {
                                            for($m = 0; $m< count($attachmentPath); $m++)
                                            {
                                                $message->attach($attachmentPath[$m], array(
                                                    'as' => $attachmentName[$m],)
                                                );
                                            }
                                        }
                                        $message->subject($request->email_subject);
                                    });
                                }
                            } 
                        }

                        //sending email to test email id
                        $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $request->email_msg], function($message) use($request,$attachmentPath,$attachmentName){
                            $message->to($request->test_email);
                            if(!empty($attachmentPath) && !empty($attachmentName))
                            {
                                for($m = 0; $m< count($attachmentPath); $m++)
                                {
                                    $message->attach($attachmentPath[$m], array(
                                        'as' => $attachmentName[$m],)
                                    );
                                }
                            }
                            $message->subject($request->email_subject);
                        });
                        
                    }
                    return back()->with("success","Test email sent successfully!"); 
                }
            }
            else
            {
                $validator = Validator::make($request->all(), [
                    'schedule_time' => 'required|string'
                ]);
                if ($validator->fails())
                { 
                    return back()->withErrors($validator)->withInput();
                }
                else
                {
                    foreach($request->email_to as $toData)
                    {
                        $groupList[] = GroupList::where("id",$toData)->first();
                    }

                    foreach($groupList as $key => $grpList)
                    {
                        $valid = [];
                        $inValid = [];
                        $grp_id[$key] = $grpList->id;
                        $grpAllData = json_decode($grpList->customer_id);
                        for($i=0; $i<count($grpAllData); $i++)
                        {
                            $allCusData = Customer_master::where("bp_code",$grpAllData[$i])->first();
                            $cus_email = $allCusData->email_id;
                            if(!empty($cus_email))
                            {
                                $emailValidator = new EmailValidator();
                                if($emailValidator->isValid($cus_email, new RFCValidation())) 
                                {
                                    $valid[] = 1;
                                } 
                                else 
                                {
                                    $inValid[] = 0;  
                                }
                            }else
                            {
                                $inValid[] = 0;  
                            }
                        }

                        if(count($inValid) > 0)
                        {
                            $allInvalid = count($inValid);
                        }
                    }
                    if(!empty($allInvalid))
                    {
                        return back()->with("error","Please correct customer email!");
                    }

                    $update = EmailCompose::where("id",$id)->where("add_by",Auth::user()->id)->update([
                        'from' => $request->email_from,
                        'to' => $email_to,
                        'cc' => json_encode($email_cc),
                        'subject' => $request->email_subject,
                        'message' => $request->email_msg,
                        'attachment' => $attachment,
                        'schedule_time' => $request->schedule_time,
                        'add_by' => Auth::user()->id,
                        'status' => 0,
                        'sending_status' => 1
                    ]);
                    if($update)
                    {
                        session()->forget("update_email_from");
                        session()->forget("update_email_to");
                        session()->forget("update_email_cc");
                        session()->forget("update_email_subject");
                        session()->forget("update_email_msg");
                        session()->forget("update_test_email");
                        session()->forget("update_attachment");
                        return back()->with("success","Email schedule updated successfully !");
                    }
                    else
                    {
                        return back()->with("error","Invalid access!");
                    }
                }
            }
            
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function duplicate($id)
    {
        try{
            $data['title'] = "Duplicate Scheduled Email";
            $emailData = EmailCompose::where('id',$id)->first();
            if(!empty($emailData))
            {
                $data['groupList'] = GroupList::get();
                $data['smtp'] = SmtpSetup::latest()->get();
                $data['list'] = $emailData;
                return view("admin.emailCompose.duplicate",$data);   
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function updateDuplicate(Request $request,$id)
    {
        try
        { 
            
            $validator = Validator::make($request->all(), [
                'email_from' => 'required|string|email|max:255',
                'email_to' => 'required|array',
                'email_msg' => 'required|string',
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                if($request->hasFile('attachment')) 
                {
                    if(!empty($request->no_attachment))
                    {
                        $dbAttachFile = json_decode($request->no_attachment);
                        for($i = 0; $i< count($dbAttachFile); $i++)
                        {
                            //delete exists attachment file
                            $image_path = public_path()."/emailFile/".$dbAttachFile[$i];
                            if(file_exists($image_path))
                            {
                                @unlink($image_path);
                            } 
                        }
                    }

                    for($i = 0; $i< count($request->attachment); $i++)
                    {
                        $image = $request->file('attachment')[$i];
                        $name = date('d-m-Y-H').time().$i.'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('/emailFile');
                        $image->move($destinationPath, $name);
                        $allName[] = $name;

                        //this file only for testing email
                        $attachmentPath[] = public_path("emailFile/".$name); 
                        $attachmentName[] = $name;
                    }   

                    $attachment = json_encode($allName);
                }
                else
                {
                    if(!empty($request->no_attachment))
                    {
                        $arrayAttachment = json_decode($request->no_attachment);
                        for($i=0; $i<count($arrayAttachment); $i++)
                        {
                            //this file only for testing email
                            $attachmentPath[] = public_path("emailFile/".$arrayAttachment[$i]); 
                            $attachmentName[] = $arrayAttachment[$i];
                        }

                        $attachment =  $request->no_attachment;
                    }
                    else
                    {
                        $attachment = "";
                        $attachmentPath = ""; 
                        $attachmentName = "";
                    }
                }

                if($request->email_cc == [null])
                {
                    $email_cc = [];
                }
                else
                {
                    $explodeCc = explode(",",$request->email_cc[0]);
                    for($i=0; $i<count($explodeCc); $i++)
                    {
                        if(!empty($explodeCc[$i]))
                        {
                            $email_cc[] = trim($explodeCc[$i]," ");
                        }
                    }
                }

                if($request->email_to == [null])
                {
                    $email_to = "";
                }
                else
                {
                    $email_to = json_encode($request->email_to);
                }
            }

            if(!empty($request->test_email))
            {
                session()->put("duplicate_email_from",$request->email_from);
                session()->put("duplicate_email_to",$email_to);
                session()->put("duplicate_email_cc",$email_cc);
                session()->put("duplicate_email_subject",$request->email_subject);
                session()->put("duplicate_email_msg",$request->email_msg);
                session()->put("duplicate_test_email",$request->test_email);
                session()->put("duplicate_attachment",$attachment);


                $requestMsg = $request->email_msg;
                $pattern = '/\bUIC_\w+/i';
                preg_match_all($pattern, $requestMsg, $matches);
                $cusPriceListName = $matches[0];
                for($i=0; $i<count($cusPriceListName); $i++)
                {
                    $priceListExplode = explode('_',$cusPriceListName[$i]);
                    $priceListShortNameFind[] = $priceListExplode[1];
                }

                $cusMasterShortName = ['bp_code','bp_name',"mobile_phone",'email_id','credit_limit','account_balance_in_sc','default_shipping','default_billing','bp_type'];
                for($i=0; $i<count($cusMasterShortName); $i++)
                {
                    // return $request->email_msg;
                    preg_match('~\\b' . $cusMasterShortName[$i] . '\\b~i', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusMasterShortNameFind[] = $matches[0];
                    }
                }

                $cusContactShortName = ['contact_person_name','con_mobile_phone','con_email_id'];
                for($i=0; $i<count($cusContactShortName); $i++)
                {
                    preg_match('~\\b' . $cusContactShortName[$i] . '\\b~i', $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        if($matches[0] == "con_mobile_phone")
                        {
                            $cusContactShortNameFind[] = "mobile_phone";
                        }
                        elseif($matches[0] == "con_email_id")
                        {
                            $cusContactShortNameFind[] = "email_id";
                        }
                        else
                        {
                            $cusContactShortNameFind[] = $matches[0];
                        }
                    }
                }

                $cusAddressShortName = ['address','row_num','street','block','location_city','location_state','location_country','location_postal_code','address_type','gstin'];
                for($i=0; $i<count($cusAddressShortName); $i++)
                {
                    // /your_regexp_here/i
                    preg_match("/".$cusAddressShortName[$i]."/", $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                    if(!empty($matches))
                    {
                        $cusAddressShortNameFind[] = $matches[0];
                    }
                }
                
                //if shortcut name available than replace with data
                $group_code = [];
                foreach($request->email_to as $groupId)
                {
                    $groupData = GroupList::where("id",$groupId)->first();
                    $arrayBpCode = json_decode($groupData->customer_id);
                    for($i=0; $i<count($arrayBpCode); $i++)
                    {
                        $GpData = Customer_master::where("bp_code",$arrayBpCode[$i])->first();
                        if(!empty($GpData))
                        {
                            $group_code[$arrayBpCode[$i]] = $GpData->group_code;
                        }
                    }
                }
              
                $allCusId = array_keys(array_unique($group_code));
               
                if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                {
                    $allShortNameData = [];
                    // customer master data
                    if (!empty($cusMasterShortNameFind))
                    {   
                        // return $cusMasterShortNameFind[0];
                        $cusMasEmptyArray = [];
                        for ($i = 0; $i < count($cusMasterShortNameFind); $i++)
                        {
                            $allShortNameData[] = $cusMasterShortNameFind[$i];

                            for ($j = 0; $j < count($allCusId); $j++)
                            {
                                if (!isset($cusMasEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusMasEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusMasData = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusMasFindingName = $cusMasterShortNameFind[$i];
                                if(!empty($cusMasData->$cusMasFindingName)) 
                                {
                                    $innerCusMasArray = $cusMasData->$cusMasFindingName;
                                } 
                                else 
                                {
                                    $innerCusMasArray = "";
                                }

                                $cusMasEmptyArray[$allCusId[$j]][$cusMasFindingName] = $innerCusMasArray;
                            }
                        }
                        // return $cusMasEmptyArray;
                    } 


                    //customer contact data
                    if (!empty($cusContactShortNameFind)) 
                    {
                        $cusConEmptyArray = [];
                    
                        for($i = 0; $i < count($cusContactShortNameFind); $i++) 
                        {
                            if($cusContactShortNameFind[$i] == "mobile_phone")
                            {
                                $allShortNameData[] = "con_mobile_phone";
                                $searchName = "con_mobile_phone";
                            }
                            elseif($cusContactShortNameFind[$i] == "email_id")
                            {
                                $allShortNameData[] = "con_email_id";
                                $searchName = "con_email_id";
                            }
                            else
                            {
                                $allShortNameData[] = $cusContactShortNameFind[$i];
                                $searchName = $cusContactShortNameFind[$i];
                            }
                           
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusConEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusConEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                // $cusBpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusConData = Customer_contact::where('bp_code', $allCusId[$j])->first();
                                $cusConFindingName = $cusContactShortNameFind[$i];
                                if(!empty($cusConData->$cusConFindingName)) 
                                {
                                    $cusConData->$cusConFindingName;
                                    $innerArray = $cusConData->$cusConFindingName;
                                } 
                                else 
                                {
                                    $innerArray = "";
                                }
                               
                                $cusConEmptyArray[$allCusId[$j]][$searchName] = $innerArray;
                                
                            }
                        }
                        // return $cusConEmptyArray;
                    }

                    //customer address data
                    if (!empty($cusAddressShortNameFind)) 
                    {
                        $cusAddEmptyArray = [];
                    
                        for($i = 0; $i < count($cusAddressShortNameFind); $i++) 
                        {
                            $allShortNameData[] = $cusAddressShortNameFind[$i];
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusAddEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusAddEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusAddBpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusAddData = Customer_address::where('address', $cusAddBpCode->default_billing)->first();
                                $cusAddFindingName = $cusAddressShortNameFind[$i];
                                if(!empty($cusAddData->$cusAddFindingName)) 
                                {
                                    $state = State_master::where('Code',$cusAddData->location_state)->first();
                                    $country = Country::where('sortname',$cusAddData->location_country)->first();

                                    if($cusAddFindingName == 'location_state')
                                    {
                                        $innerAddArray = $state->name;
                                    }
                                    elseif($cusAddFindingName == 'location_country')
                                    {
                                        $innerAddArray = $country->name; 
                                    }
                                    else
                                    {
                                        $innerAddArray = $cusAddData->$cusAddFindingName;
                                    }
                                } 
                                else 
                                {
                                    $innerAddArray = "";
                                }

                                $cusAddEmptyArray[$allCusId[$j]][$cusAddFindingName] = $innerAddArray;
                            }
                        }
                        // return $cusAddEmptyArray;
                    }


                    //price list data
                    if (!empty($cusPriceListName)) 
                    {
                        // return $priceListShortNameFind;
                        $cusPriceEmptyArray = [];
                    
                        for($i = 0; $i < count($cusPriceListName); $i++) 
                        {
                            $allShortNameData[] = $cusPriceListName[$i];
                            for($j = 0; $j < count($allCusId); $j++) 
                            {
                                if(!isset($cusPriceEmptyArray[$allCusId[$j]])) 
                                {
                                    $cusPriceEmptyArray[$allCusId[$j]] = [];
                                }
                    
                                $cusPriceGpCode = Customer_master::where('bp_code', $allCusId[$j])->first();
                                $cusPriceGpNo = Customer_group::where('group_no', $cusPriceGpCode->group_code)->first();
                                $cusPriceListData = Price_list::where('U_CGroup', $cusPriceGpNo->group_name)->where("U_ItemCode",$priceListShortNameFind[$i])->first();
                                $cusPriceFindingName = $priceListShortNameFind[$i];
                                if(!empty($cusPriceListData)) 
                                {
                                    $tag = "<a href='".$cusPriceListData->U_Link."' target='_blank'>".$cusPriceListData->U_ListName."</a>";
                                    $innerPriceArray = $tag;
                                } 
                                else 
                                {
                                    $innerPriceArray = "";
                                }

                                $cusPriceEmptyArray[$allCusId[$j]][$cusPriceListName[$i]] = $innerPriceArray;
                            }
                        }
                        // return $cusPriceEmptyArray;
                    }
                

                    if (!empty($cusPriceEmptyArray) || !empty($cusAddEmptyArray) || !empty($cusConEmptyArray) || !empty($cusMasEmptyArray)) 
                    {
                        $allFetchedData = [];
                    
                        foreach ($allCusId as $key => $customerId) 
                        {
                            $allFetchedData[$customerId] = [];
                    
                            // if (!empty($cusItemMasEmptyArray[$customerId])) {
                            //     $allFetchedData[$customerId] += $cusItemMasEmptyArray[$customerId];
                            // }
                    
                            if (!empty($cusPriceEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusPriceEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusAddEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusAddEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusConEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusConEmptyArray[$customerId];
                            }
                    
                            if (!empty($cusMasEmptyArray[$customerId])) {
                                $allFetchedData[$customerId] += $cusMasEmptyArray[$customerId];
                            }
                        }
                        
                        //replacing short name with data
                        for($k=0; $k< count($allFetchedData); $k++)
                        {
                            $cusMsg = $request->email_msg;
                            $perCusData = $allFetchedData[$allCusId[$k]];

                            for ($l = 0; $l < count($perCusData); $l++)
                            {
                                $search = $allShortNameData[$l];
                                $replacement = $perCusData[$search];

                                $cusMsg = preg_replace_callback(
                                    "/\b" . preg_quote($search, '/') . "\b/i", 
                                    function ($matches) use ($replacement, $search) {
                                        return $matches[0] === $search ? $replacement : $matches[0];
                                    }, 
                                    $cusMsg
                                );
                            }


                            //if cc mail available send to cc mail
                            if(!empty($request->email_cc))
                            {
                                $explodeCc = explode(",",$request->email_cc[0]);
                                for($i=0; $i<count($explodeCc); $i++)
                                {
                                    if(!empty($explodeCc[$i]))
                                    {
                                        $ccMail = trim($explodeCc[$i]." ");
                                        $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg], function($message) use($request,$ccMail,$attachmentPath,$attachmentName){
                                            $message->to($ccMail); 
                                            $message->subject($request->email_subject);
                                            if(!empty($attachmentPath) && !empty($attachmentName))
                                            {
                                                for($m = 0; $m< count($attachmentPath); $m++)
                                                {
                                                    $message->attach($attachmentPath[$m], array(
                                                        'as' => $attachmentName[$m],)
                                                    );
                                                }
                                            }
                                        });
                                    }
                                }
                            }

                            //sending email to test email id
                            $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg,"attachment" => $attachment], function($message) use($request,$attachmentPath,$attachmentName){
                                $message->to($request->test_email);
                                if(!empty($attachmentPath) && !empty($attachmentName))
                                {
                                    for($m = 0; $m< count($attachmentPath); $m++)
                                    {
                                        $message->attach($attachmentPath[$m], array(
                                            'as' => $attachmentName[$m],)
                                        );
                                    }
                                }
                                $message->subject($request->email_subject);
                            });
                        }
                        
                        return back()->with("success","Test email sent successfully!");
                    }  
                }
                else
                {
                    for($i=0; $i<count($allCusId); $i++)
                    {
                        //if cc mail available send to cc mail
                        if(!empty($request->email_cc))
                        {
                            $explodeCc = explode(",",$request->email_cc[0]);
                            for($j=0; $j<count($explodeCc); $j++)
                            {
                                if(!empty($explodeCc[$j]))
                                {
                                    $ccMail = trim($explodeCc[$j]." ");
                                    $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $request->email_msg], function($message) use($request,$ccMail,$attachmentPath,$attachmentName){
                                        $message->to($ccMail);
                                        if(!empty($attachmentPath) && !empty($attachmentName))
                                        {
                                            for($m = 0; $m< count($attachmentPath); $m++)
                                            {
                                                $message->attach($attachmentPath[$m], array(
                                                    'as' => $attachmentName[$m],)
                                                );
                                            }
                                        }
                                        $message->subject($request->email_subject);
                                    });
                                }
                            } 
                        }

                        //sending email to test email id
                        $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $request->email_msg], function($message) use($request,$attachmentPath,$attachmentName){
                            $message->to($request->test_email);
                            if(!empty($attachmentPath) && !empty($attachmentName))
                            {
                                for($m = 0; $m< count($attachmentPath); $m++)
                                {
                                    $message->attach($attachmentPath[$m], array(
                                        'as' => $attachmentName[$m],)
                                    );
                                }
                            }
                            $message->subject($request->email_subject);
                        });
                        
                    }
                    return back()->with("success","Test email sent successfully!"); 
                }
            }
            else
            {
                $validator = Validator::make($request->all(), [
                    'schedule_time' => 'required|string'
                ]);
                if ($validator->fails())
                { 
                    return back()->withErrors($validator)->withInput();
                }
                else
                {
                    foreach($request->email_to as $toData)
                    {
                        $groupList[] = GroupList::where("id",$toData)->first();
                    }

                    foreach($groupList as $key => $grpList)
                    {
                        $valid = [];
                        $inValid = [];
                        $grp_id[$key] = $grpList->id;
                        $grpAllData = json_decode($grpList->customer_id);
                        for($i=0; $i<count($grpAllData); $i++)
                        {
                            $allCusData = Customer_master::where("bp_code",$grpAllData[$i])->first();
                            $cus_email = $allCusData->email_id;
                            if(!empty($cus_email))
                            {
                                $emailValidator = new EmailValidator();
                                if($emailValidator->isValid($cus_email, new RFCValidation())) 
                                {
                                    $valid[] = 1;
                                } 
                                else 
                                {
                                    $inValid[] = 0;  
                                }
                            }else
                            {
                                $inValid[] = 0;  
                            }
                        }

                        if(count($inValid) > 0)
                        {
                            $allInvalid = count($inValid);
                        }
                    }
                    if(!empty($allInvalid))
                    {
                        return back()->with("error","Please correct customer email!");
                    }

                    $emailCompose = new EmailCompose;
                    $emailCompose->from = $request->email_from;
                    $emailCompose->to = $email_to;
                    $emailCompose->cc = json_encode($email_cc);
                    $emailCompose->subject = $request->email_subject;
                    $emailCompose->message = $request->email_msg;
                    $emailCompose->attachment = $attachment;
                    $emailCompose->schedule_time = $request->schedule_time;
                    $emailCompose->add_by = Auth::user()->id;
                    if($emailCompose->save())
                    {
                        session()->forget("duplicate_email_from");
                        session()->forget("duplicate_email_to");
                        session()->forget("duplicate_email_cc");
                        session()->forget("duplicate_email_subject");
                        session()->forget("duplicate_email_msg");
                        session()->forget("duplicate_test_email");
                        session()->forget("duplicate_attachment");
                        return back()->with("success","Email schedule duplicated successfully!");
                    }
                }
            }
            
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function stopEmail($id)
    {
        try
        { 
            $update = EmailCompose::where("id",$id)->update([
                'status' => 3,
                'sending_status' => 0
            ]);
            if($update)
            {
                return back()->with("success","Sending email stopped!");
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function startEmail($id)
    {
        try
        {
            $update = EmailCompose::where("id",$id)->update([
                'status' => 0,
                'sending_status' => 1
            ]);
            if($update)
            {
                return back()->with("success","Schedule Email ready to execute!");
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try
        {
            //delete exists attachment file
            $emailData = EmailCompose::where('id',$id)->first();
            if(!empty($emailData))
            {
                if(!empty($emailData->attachment))
                {
                    $dbAttachFile = json_decode($emailData->attachment);
                    for($i = 0; $i< count($dbAttachFile); $i++)
                    {
                        //delete exists attachment file
                        $image_path = public_path()."/emailFile/".$dbAttachFile[$i];
                        if(file_exists($image_path))
                        {
                            @unlink($image_path);
                        } 
                    }
                }

                if(EmailCompose::where('id',$id)->delete())
                {
                    return back()->with("success","Scheduled email deleted successfully!");
                }
            }
            else
            {
                return back()->with("error","Invalid Access!");   
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function attachDelete(Request $request)
    {
        try{
            //delete exists attachment file
            $emailData = EmailCompose::where('id',$request->id)->first();
            if(!empty($emailData->attachment))
            {
                $dbAttachFile = json_decode($emailData->attachment);
                for($i = 0; $i< count($dbAttachFile); $i++)
                {
                    //delete exists attachment file
                    $image_path = public_path()."/emailFile/".$dbAttachFile[$i];
                    if(file_exists($image_path))
                    {
                        @unlink($image_path);
                    } 
                }
            }
            
            $update = EmailCompose::where('id',$request->id)->update([
                "attachment" => "",
            ]);
            if($update)
            {
                return "Scheduled attachment deleted successfully!";
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function formReset(Request $request,$id)
    {
        try
        {
            if($id == 1)
            {
                session()->forget("email_from");
                session()->forget("email_to");
                session()->forget("email_cc");
                session()->forget("email_subject");
                session()->forget("email_msg");
                session()->forget("test_email");
                session()->forget("attachment");
            }
            elseif($id == 2)
            {
                session()->forget("update_email_from");
                session()->forget("update_email_to");
                session()->forget("update_email_cc");
                session()->forget("update_email_subject");
                session()->forget("update_email_msg");
                session()->forget("update_test_email");
                session()->forget("update_attachment");
            }
            elseif($id == 3)
            {
                session()->forget("duplicate_email_from");
                session()->forget("duplicate_email_to");
                session()->forget("duplicate_email_cc");
                session()->forget("duplicate_email_subject");
                session()->forget("duplicate_email_msg");
                session()->forget("duplicate_test_email");
                session()->forget("duplicate_attachment");
            }

           return back()->with("success","Form Reset Successfully");
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function attachEmpty(Request $request)
    {
        try{
            //delete exists attachment file
            $attachFile = json_decode($request->file);
            for($i = 0; $i< count($attachFile); $i++)
            {
                //delete exists attachment file
                $image_path = public_path()."/emailFile/".$attachFile[$i];
                if(file_exists($image_path))
                {
                    @unlink($image_path);
                } 
            }
            session()->forget("update_attachment");
            session()->forget("attachment");
            return "Scheduled attachment deleted successfully!";
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
