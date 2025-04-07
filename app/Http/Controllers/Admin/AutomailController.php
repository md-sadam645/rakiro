<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice_master;
use App\Models\SmtpSetup;
use App\Models\Customer_master;
use App\Models\Customer_contact;
use App\Models\Customer_address;
use App\Models\Customer_group;
use App\Models\Price_list;
use App\Models\Automail;
use App\Models\Failed_jobs;
use App\Models\Job_batch;
use App\Models\Country;
use App\Jobs\Automation;
use App\Models\State_master;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;

class AutomailController extends Controller
{
    public function index()
    {
        try
        {
            $data['title'] = "Create Automation";
            $data['cusCategory'] = Customer_group::all();
            $data['smtp'] = SmtpSetup::latest()->get();
            return view('admin.automail.index',$data);
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
            if($request->email_from != "choose from address" && $request->cus_category != "choose customer category")
            {
                $validator = Validator::make($request->all(), [
                    'email_from' => 'required|string|email|max:255',
                    'cus_category' => 'required|array',
                    'days_from' => 'required|string',
                    'email_msg' => 'required|string',
                    'subject' => 'required|string',
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
                        $attachFile = json_decode($request->no_attachment);
                        if(!empty($attachFile))
                        {
                            for($i = 0; $i< count($attachFile); $i++)
                            {
                                $image_path = public_path()."/emailFile/".$attachFile[$i];
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
                }
              
                if(!empty($request->test_email))
                {

                    session()->put("automail_name",$request->name);
                    session()->put("automail_from",$request->email_from);
                    session()->put("cus_category",$request->cus_category);
                    session()->put("days_from",$request->days_from);
                    session()->put("automail_subject",$request->subject);
                    session()->put("automail_msg",$request->email_msg);
                    session()->put("automail_test_email",$request->test_email);
                    session()->put("automail_attachment",$attachment);
                    
                    //adding manual day in current date
                    $currentDate = Carbon::now();
                    $newDate = $currentDate->subDay($request->days_from);
                    $finalDate = $newDate->format('Y-m-d');

                    $cusRowId = [];
                    foreach ($request->cus_category as $key => $cus_category)
                    {
                        $groupData = Customer_master::where("group_code", $cus_category)->get();

                        if (count($groupData) != 0)
                        {
                            $bpCodes = $groupData->pluck('bp_code')->toArray();
                            $cusRowData = Invoice_master::whereIn("BPCode", $bpCodes)
                                ->where("CreationDate", '<', $finalDate)
                                ->get();

                            
                            if (!$cusRowData->isEmpty()) {
                                $cusRowId[$key] = array_values(array_unique($cusRowData->pluck('BPCode')->toArray()));
                            }
                        }
                    }
             
                    if(count($cusRowId) != 0)
                    {
                        for($i=0; $i < count($cusRowId); $i++)
                        {
                            $allCusId[] = $cusRowId[$i][0];
                        }
                    }
               
                    if(!empty($allCusId))
                    {
                        
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
                            preg_match("/".$cusAddressShortName[$i]."/", $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                            if(!empty($matches))
                            {
                                $cusAddressShortNameFind[] = $matches[0];
                            }
                        }
                    
                        if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                        {
                            $allShortNameData = [];
                            //customer master data
                            if (!empty($cusMasterShortNameFind))
                            {   
                                $cusMasEmptyArray = [];
                                for ($i = 0; $i < count($cusMasterShortNameFind); $i++)
                                {
                                    $allShortNameData[] = $cusMasterShortNameFind[$i];

                                    for($j = 0; $j < count($allCusId); $j++) 
                                    {
                                        if(!isset($cusMasEmptyArray[$allCusId[$j]])) 
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
                                        // return $allCusId[$j];
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
                            if (!empty($priceListShortNameFind)) 
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
                                    // echo $key. "=>" .$customerId." / ";
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
                                
                                //replacing short name with customer data
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
                                    // return $cusMsg;

                                    //sending email to test email id
                                    $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg,"attachment" => $attachment], function($message) use($request,$attachmentPath,$attachmentName){
                                        $message->to($request->test_email);
                                        $message->subject($request->subject);
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
                            //sending email to test email id
                            for($k=0; $k< count($allCusId); $k++)
                            {
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
                                    $message->subject($request->subject);
                                });
                            }
                           
                            return back()->with("success","Test email sent successfully!");
                            
                        }
                    }
                    else
                    {
                        return back()->with("error","Doesn't found any customer!");
                    }  
                }
                else
                {
                    $autoMail = new Automail;
                    $autoMail->name = $request->name;
                    $autoMail->from = $request->email_from;
                    $autoMail->cus_category = json_encode($request->cus_category);
                    $autoMail->days_from_last_invoice = $request->days_from;
                    $autoMail->schedule_days = $request->schedule_days;
                    $autoMail->schedule_time = $request->schedule_time;
                    $autoMail->subject = $request->subject;
                    $autoMail->message = $request->email_msg;
                    $autoMail->attachment = $attachment;
                    // $autoMail->execution_date = date('Y-m-d');
                    $autoMail->add_by = Auth::user()->id;
                    if($autoMail->save())
                    {
                        session()->forget("automail_name");
                        session()->forget("automail_from");
                        session()->forget("cus_category");
                        session()->forget("days_from");
                        session()->forget("automail_subject");
                        session()->forget("automail_msg");
                        session()->forget("automail_test_email");
                        session()->forget("automail_attachment");
                        return back()->with("success","Automail Created successfully !");
                    }
                    
                }
            }
            else{
                return back()->with("error","Customer category & from field are required!");
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
            $data['title'] = "View Automation";
            $data['customer_group'] = Customer_group::all();
            $data['totalList'] = Automail::count();
            $data['list'] = Automail::latest()->paginate(10);

            //Start - checking failed email status
            $emailDatas = Automail::where('add_by',Auth::user()->id)->get();
            if(count($emailDatas) != 0)
            {
                foreach($emailDatas as $emailData)
                {
                    $jobData = Job_batch::where('id',$emailData->job_batch_id)->first();
                    if(empty($jobData))
                    {
                        Automail::where("id",$emailData->id)->update([
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
                                Automail::where("id",$emailData->id)->update([
                                    'total_jobs' => $jobData->total_jobs,
                                    'pending_jobs' => $jobData->pending_jobs,
                                    'failed_jobs' => $jobData->failed_jobs,
                                ]);
                            }
                            else
                            {
                                Automail::where("id",$emailData->id)->update([
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
                                Automail::where("id",$emailData->id)->update([
                                    'status' => 2,
                                    'total_jobs' => $jobData->total_jobs,
                                    'pending_jobs' => $jobData->pending_jobs,
                                    'failed_jobs' => 0,
                                ]);
                            }
                            else
                            {
                                Automail::where("id",$emailData->id)->update([
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

            return view('admin.automail.view',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }


    public function automation()
    {
        try
        {
            $cDateTime = Carbon::now();
            $cDate = $cDateTime->toDateString();  
            $autoMail = Automail::where("status",2)->get();

            if(!empty($autoMail))
            {
                for($i=0; $i<count($autoMail); $i++)
                {
                    $lExecuteDate = Carbon::createFromFormat('Y-m-d', $autoMail[$i]->last_executed_date);
                    $addedDate = $lExecuteDate->addDays($autoMail[$i]->schedule_time);
                    $finalDate = $addedDate->toDateString();
                    if($cDate >= $finalDate)
                    {
                        Automail::where("id",$autoMail[$i]->id)->update(["status" => 1]);
                    }
                }
            }


            $cTime = $cDateTime->toTimeString(); 
            $automail = Automail::where("status",1)->get();
         
            if(count($automail) != 0)
            {  
                for($i=0; $i<count($automail); $i++)
                {
                    if($cTime >= $automail[$i]->schedule_time)
                    {
                        $fromEmail = $automail[$i]->from;
                        $emailRowId = $automail[$i]->id;
                        $subject = $automail[$i]->subject;
                        $msg = $automail[$i]->message;
                        $cus_category = $automail[$i]->cus_category;
                        $days_from = $automail[$i]->days_from_last_invoice;
                        
                        $attachmentPath = [];
                        $attachmentName = [];
                        if(!empty($automail[$i]->attachment))
                        {
                            $arrayAttachFile = json_decode($automail[$i]->attachment);
                            for($j=0; $j<count($arrayAttachFile); $j++)
                            {
                                // echo public_path("emailFile/".$arrayAttachFile[$j]);
                                $attachmentPath[] = public_path("emailFile/".$arrayAttachFile[$j]); 
                                $attachmentName[] = $arrayAttachFile[$j];
                            }
                        }

                        //adding manual day in current date
                        $currentDate = Carbon::now();
                        $newDate = $currentDate->subDay($days_from);
                        $finalDate = $newDate->format('Y-m-d');
                     
                        $cusRowId = [];
                        foreach(json_decode($cus_category) as $key => $cus_catData)
                        {
                            $groupData = Customer_master::where("group_code",$cus_catData)->where('u_ecomm','Yes')->where('valid','tYES')->where('status',1)->get();
                            if(count($groupData) != 0)
                            {
                                $bpCodes = $groupData->pluck('bp_code')->toArray();
                                $cusRowData = Invoice_master::whereIn("BPCode", $bpCodes)
                                    ->where("CreationDate", '<', $finalDate)
                                    ->get();
                                
                                if(!$cusRowData->isEmpty())
                                {
                                    $cusRowId[] = array_values(array_unique($cusRowData->pluck('BPCode')->toArray()));
                                }
                            }
                        }

                        //merging multiple array in to single array
                        $emptyArray = [];
                        foreach ($cusRowId as $cusRow)
                        {
                            $emptyArray = array_merge($emptyArray, $cusRow);
                        }

                        if(!empty($emptyArray))
                        {
                            $allCusId = array_values(array_unique($emptyArray));
                        }
                        else{
                            $allCusId = [];
                        }

                        if($allCusId != '[]')
                        {
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
                                preg_match('~\\b' . $cusMasterShortName[$j] . '\\b~i', $msg, $matches, PREG_UNMATCHED_AS_NULL);
                                if(!empty($matches))
                                {
                                    $cusMasterShortNameFind[] = $matches[0];
                                }
                            }

                            $cusContactShortName = ['contact_person_name','con_mobile_phone','con_email_id'];
                            for($j=0; $j<count($cusContactShortName); $j++)
                            {
                                preg_match('~\\b' . $cusContactShortName[$j] . '\\b~i', $msg, $matches, PREG_UNMATCHED_AS_NULL);
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
                                preg_match("/".$cusAddressShortName[$j]."/", $msg, $matches, PREG_UNMATCHED_AS_NULL);
                                if(!empty($matches))
                                {
                                    $cusAddressShortNameFind[] = $matches[0];
                                }
                            }

                            
                            if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                            {
                                $allShortNameData = [];
                                //customer master data
                                if (!empty($cusMasterShortNameFind))
                                {   
                                    // return $cusMasterShortNameFind[0];
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
                                
                                            // $cusBpCode = Customer_master::where('rowId', $allCusId[$k])->first();
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
                                if(!empty($priceListShortNameFind)) 
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
                                            $cusPriceListData = Price_list::where('U_CGroup',$cusPriceGpNo->group_name)->where("U_ItemCode",$priceListShortNameFind[$j])->first();
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


                                if (!empty($cusPriceEmptyArray) || !empty($cusAddEmptyArray) || !empty($cusConEmptyArray) || !empty($cusMasEmptyArray)) 
                                {
                                    $allFetchedData = [];
                                
                                    foreach ($allCusId as $customerId) 
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
                                        $batch->add(new Automation($allCusIdChunks[$m],$allFetchedDataChunks[$m],$emailRowId,$allShortNameData,$msg,$subject,$fromEmail,$attachmentPath,$attachmentName));
                                    }

                                    Automail::where('id', $emailRowId)->update(['job_batch_id' => $batch->id,'total_cus' => count($allCusId)]);
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
                                    $batch->add(new Automation($allCusIdChunks[$m],$allFetchedDataChunks[$m],$emailRowId,$allShortNameData,$msg,$subject,$fromEmail,$attachmentPath,$attachmentName));
                                }

                                Automail::where('id', $emailRowId)->update(['job_batch_id' => $batch->id,'total_cus' => count($allCusId)]); 
                            }

                            //WHEN EXECUTE THIS ARRAY WE NEED TO MAKING EMPTY
                            $cusMultipleId = [];
                            $allCusId = []; 
                        }
                    }
                }
            }
            return "end";  
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    //showing all information about of sending email 
    public function automailSentDetails($id)
    {
        try
        {
            $data['title'] = "Email Details";
            $emailData = Automail::where('id',$id)->where('add_by',Auth::user()->id)->first();
            if(!empty($emailData))
            {
                $jobData = Job_batch::where('id',$emailData->job_batch_id)->first();
                if(empty($jobData))
                {
                    Automail::where("id",$id)->update([
                        'job_batch_id' => 0,
                        'total_cus' => 0,
                        'total_jobs' => 0,
                        'pending_jobs' => 0,
                        'failed_jobs' => 0,
                    ]);
                }
                else
                {
                    //fjId = failed job id & fj = failed job
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
                            Automail::where("id",$id)->update([
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => $jobData->pending_jobs,
                                'failed_jobs' => $jobData->failed_jobs,
                            ]);
                        }
                        else
                        {
                            Automail::where("id",$id)->update([
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
                            Automail::where("id",$id)->update([
                                'status' => 2,
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => $jobData->pending_jobs,
                                'failed_jobs' => 0,
                            ]);
                        }
                        else
                        {
                            Automail::where("id",$id)->update([
                                'total_jobs' => $jobData->total_jobs,
                                'pending_jobs' => $jobData->pending_jobs,
                                'failed_jobs' => $jobData->failed_jobs,
                            ]);
                        }
                    }
                }

                $data['list'] = Automail::where('id',$id)->first();
                return view("admin.automail.emailDetails",$data);
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
            $automailData = Automail::where('id',$id)->where('add_by',Auth::user()->id)->first();
            if(!empty($automailData))
            {
                $data['title'] = "Edit Automation";
                $data['cusCategory'] = Customer_group::all();
                $data['smtp'] = SmtpSetup::latest()->get();
                $data['list'] = $automailData;
                return view("admin.automail.edit",$data);   
            }
            else
            {
                return back()->with('error','Invalid Access!');
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
            if($request->email_from != "choose from address" && $request->cus_category != "choose customer category")
            {
                $validator = Validator::make($request->all(), [
                    'email_from' => 'required|string|email|max:255',
                    'cus_category' => 'required|array',
                    'days_from' => 'required|string',
                    'email_msg' => 'required|string',
                    'subject' => 'required|string',
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
                }
            
                if(!empty($request->test_email))
                {
                    session()->put("update_automail_name",$request->name);
                    session()->put("update_automail_from",$request->email_from);
                    session()->put("update_cus_category",$request->cus_category);
                    session()->put("update_days_from",$request->days_from);
                    session()->put("update_automail_subject",$request->subject);
                    session()->put("update_automail_msg",$request->email_msg);
                    session()->put("update_automail_test_email",$request->test_email);
                    session()->put("update_automail_attachment",$attachment);
                
                    //adding manual day in current date
                    $currentDate = Carbon::now();
                    $newDate = $currentDate->subDay($request->days_from);
                    $finalDate = $newDate->format('Y-m-d');

                    $cusRowId = [];

                    foreach ($request->cus_category as $key => $cus_category)
                    {
                        $groupData = Customer_master::where("group_code", $cus_category)->get();

                        if (count($groupData) != 0)
                        {
                            $bpCodes = $groupData->pluck('bp_code')->toArray();
                            $cusRowData = Invoice_master::whereIn("BPCode", $bpCodes)
                                ->where("CreationDate", '<', $finalDate)
                                ->get();

                            
                            if (!$cusRowData->isEmpty()) {
                                $cusRowId[] = array_values(array_unique($cusRowData->pluck('BPCode')->toArray()));
                            }
                        }
                    }

                    if(count($cusRowId) != 0)
                    {
                        for($i=0; $i < count($cusRowId); $i++)
                        {
                            $allCusId[] = $cusRowId[$i][0];
                        }
                    }

                    if(!empty($allCusId))
                    {
                        
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
                            preg_match("/".$cusAddressShortName[$i]."/", $request->email_msg, $matches, PREG_UNMATCHED_AS_NULL);
                            if(!empty($matches))
                            {
                                $cusAddressShortNameFind[] = $matches[0];
                            }
                        }
                    
                        if(!empty($priceListShortNameFind) || !empty($cusMasterShortNameFind) || !empty($cusContactShortNameFind) || !empty($cusAddressShortNameFind))
                        {
                            $allShortNameData = [];
                            //customer master data
                            if (!empty($cusMasterShortNameFind))
                            {   
                                $cusMasEmptyArray = [];
                                for ($i = 0; $i < count($cusMasterShortNameFind); $i++)
                                {
                                    $allShortNameData[] = $cusMasterShortNameFind[$i];

                                    for($j = 0; $j < count($allCusId); $j++) 
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
                            if (!empty($priceListShortNameFind)) 
                            {
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
                                    // echo $key. "=>" .$customerId." / ";
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
                               
                                    //sending email to test email id
                                    $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg,"attachment" => $attachment], function($message) use($request,$attachmentPath,$attachmentName){
                                        $message->to($request->test_email);
                                        $message->subject($request->subject);
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
    
                            //sending email to test email id
                            for($k=0; $k< count($allCusId); $k++)
                            {
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
                                    $message->subject($request->subject);
                                });
                            }
                            return back()->with("success","Test email sent successfully!");
                        }
                    }
                    else
                    {
                        return back()->with("error","Doesn't found any customer!");
                    }  
                }
                else
                {
                    $update = Automail::where("id",$id)->update([
                        'name' => $request->name,
                        'from' => $request->email_from,
                        'cus_category' => json_encode($request->cus_category),
                        'days_from_last_invoice' => $request->days_from,
                        'schedule_days' => $request->schedule_days,
                        'schedule_time' => $request->schedule_time,
                        'subject' => $request->subject,
                        'message' => $request->email_msg,
                        'attachment' => $attachment,
                        'add_by' => Auth::user()->id
                    ]);
                    if($update)
                    {
                        session()->forget("update_automail_name");
                        session()->forget("update_automail_from");
                        session()->forget("update_cus_category");
                        session()->forget("update_days_from");
                        session()->forget("update_automail_subject");
                        session()->forget("update_automail_msg");
                        session()->forget("update_automail_test_email");
                        session()->forget("update_automail_attachment");
                        return back()->with("success","Automail updated successfully!");
                    } 
                }
            }
            else{
                return back()->with("error","Customer category & from field are required!");
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        } 
    }

    public function status($id)
    {
        try{
            $gettingData = Automail::where('id',$id)->first();
            if($gettingData->status == 0)
            {
                $status = 1;
            }
            elseif($gettingData->status == 1)
            {
                $status = 0;
            }
            elseif($gettingData->status == 2)
            {
                return back()->with("error","Invalid Access!");
            }

            $update = Automail::where('id',$id)->update(['status' => $status]);
            if($update)
            {
                return back()->with("success","Automail status update successfully!");
            }  
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        try{
            //delete exists attachment file
            $emailData = Automail::where('id',$id)->first();
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

            if(Automail::where('id',$id)->delete())
            {
                return back()->with("success","Automail deleted successfully!");
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
            $emailData = Automail::where('id',$request->id)->first();
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
            
            $update = Automail::where('id',$request->id)->update([
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

            session()->forget("automail_attachment");
            return "Automail attachment deleted successfully!";
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
                session()->forget("automail_name");
                session()->forget("automail_from");
                session()->forget("cus_category");
                session()->forget("days_from");
                session()->forget("automail_subject");
                session()->forget("automail_msg");
                session()->forget("automail_test_email");
                session()->forget("automail_attachment");
            }
            elseif($id == 2)
            {
                session()->forget("update_automail_name");
                session()->forget("update_automail_from");
                session()->forget("update_cus_category");
                session()->forget("update_days_from");
                session()->forget("update_automail_subject");
                session()->forget("update_automail_msg");
                session()->forget("update_automail_test_email");
                session()->forget("update_automail_attachment");
            }

           return back()->with("success","Form Reset Successfully");
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
