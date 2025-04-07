<?php

namespace App\Http\Controllers\admin;
use App\Models\State_master;
use App\Models\City;
use App\Models\Pincode_master;
use App\Models\Customer_address;
use App\Models\Customer_master;
use App\Models\Country;
use App\Models\Customer_group;
use App\Models\GroupList;
use App\Models\Invoice_master;
use App\Models\Invoice_master_summary;
use App\Models\Order_summary;
// use App\Models\Product_category;
use App\Models\Item_master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Facades\Auth;
// use PHPUnit\TextUI\XmlConfiguration\Group;

class CreateListController extends Controller
{
    function index(Request $request)
    {
        try
        {
            $data['title'] = "Create List";
            $territory = Customer_master::select("territory_name")->get();
            $repeatedTerritory = collect($territory)->pluck("territory_name")->all();
            $data['territory'] = array_values(array_unique($repeatedTerritory));

            $item_master = Item_master::all();
            foreach($item_master as $itemMasterData)
            {
                $pCategory[] = $itemMasterData->product_category;
                $item_sku[] = $itemMasterData->item_sku;
            }
        
            $data['item_sku'] = array_values(array_unique($item_sku));
            $data['pCategory'] = array_values(array_unique($pCategory));
            $data['country'] = Country::all();
            $data['city'] = City::all();
            $data['state'] = State_master::all();
            // $data['pincode'] = Pincode_master::all();
            $data['cusCategory'] = Customer_group::all();
            $data['cusCodeName'] = Customer_master::all();
            return view("admin.createList.index",$data);
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    function save(Request $request)
    {
        try
        {   
            $data['title'] = "List of Emails";
            if(!empty($request->cus_no_filter) || !empty($request->invoice_no_filter))
            {   
                
                if(!empty($request->cus_no_filter) && !empty($request->invoice_no_filter))
                {
                    if(!empty($request->customer_code) || !empty($request->customer_name) || !empty($request->customer_category) || !empty($request->territory) || $request->country != "choose country" || !empty($request->city) && !empty($request->state) && !empty($request->pincode))
                    {
                        if(empty($request->product_code) && empty($request->product_category) && empty($request->fromDate) && empty($request->toDate))
                        {
                            return back()->with("error","Please fill at least one field of invoice filter!");
                        }
                    }
                    else
                    {
                        if(empty($request->product_code) && empty($request->product_category) && empty($request->fromDate) && empty($request->toDate))
                        {
                            return back()->with("error","Please fill at least one field of both filter!");
                        }
                        else
                        {
                            return back()->with("error","Please fill at least one field of customer filter!");
                        }
                    }
                }
                
                // Start customer filter
                if(!empty($request->cus_no_filter))
                {
                    // $state = explode(",",$request->state)[0];
                    // if(!empty($request->state))
                    // {
                    //     $stateData = State_master::where("name",$request->state)->first();
                    //     $stateCode = $stateData->Code;
                    // }

                    $allCusInfo = [];
                    if($request->active == "yes")
                    {
                        if(!empty($request->customer_code) || !empty($request->customer_name) || !empty($request->customer_category) || !empty($request->territory) || !empty($request->country) || !empty($request->city) || !empty($request->state) || !empty($request->pincode))
                        { 
                            //find Territory Data 
                            if(!empty($request->territory))
                            {
                                foreach($request->territory as $tCode)
                                {
                                    $territoryData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->where('territory_name',$tCode)->get();
                                    $allCusInfo = array_merge($allCusInfo, $territoryData->toArray());
                                }
                            }
                           
                            //find customer_code Data 
                            if(!empty($request->customer_code))
                            {
                                $cCodeData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->whereIn("bp_code",$request->customer_code)->get();
                                $allCusInfo = array_merge($allCusInfo, $cCodeData->toArray());
                            }

                            //find customer_name Data 
                            if(!empty($request->customer_name))
                            {
                                $cNameData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->whereIn("bp_name",$request->customer_name)->get();
                                $allCusInfo = array_merge($allCusInfo, $cNameData->toArray());
                            }
                     
                            //find customer_category Data 
                            if(!empty($request->customer_category))
                            {
                                foreach($request->customer_category as $cCat)
                                {
                                    $cCatData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->where('group_code',$cCat)->get();
                                    $allCusInfo = array_merge($allCusInfo, $cCatData->toArray());
                                }
                            }
                        
                            //find country Data 
                            if(!empty($request->country))
                            {
                                foreach($request->country as $cusCountry)
                                {
                                    $locationData = Customer_address::where("location_country",strtoupper($cusCountry))->get();
                                    $countryCusId = collect($locationData)->pluck("bp_code")->all();
                                    $countryCusData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->whereIn('bp_code',$countryCusId)->get();
                                    $allCusInfo = array_merge($allCusInfo, $countryCusData->toArray());
                                }
                            }

                            //find pincode Data 
                            // if(!empty($request->pincode))
                            // {
                            //     foreach($request->pincode as $cusPincode)
                            //     {
                            //         $locationPData = Customer_address::where("location_postal_code",$cusPincode)->get();
                            //         $pincodeCusId = collect($locationPData)->pluck("bp_code")->all();
                            //         $pincodeCusData = Customer_master::where("status",1)->whereIn('bp_code',$pincodeCusId)->get();
                            //         $allCusInfo = array_merge($allCusInfo, $pincodeCusData->toArray());
                            //     }
                            // }

                            //find state Data 
                            if(!empty($request->state))
                            {
                                foreach($request->state as $cusState)
                                {
                                    $locationSData = Customer_address::where("location_state",strtoupper($cusState))->get();
                                    $stateCusId = collect($locationSData)->pluck("bp_code")->all();
                                    $stateCusData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->whereIn('bp_code',$stateCusId)->get();
                                    $allCusInfo = array_merge($allCusInfo, $stateCusData->toArray());
                                }
                            }

                            //find city Data 
                            if(!empty($request->city))
                            {
                                foreach($request->city as $cusCity)
                                {
                                    $locationCData = Customer_address::where("location_city",strtoupper($cusCity))->get();
                                    $cityCusId = collect($locationCData)->pluck("bp_code")->all();
                                    $cityCusData = Customer_master::where("status",1)->where('u_ecomm','Yes')->where('valid','tYES')->whereIn('bp_code',$cityCusId)->get();
                                    $allCusInfo = array_merge($allCusInfo, $cityCusData->toArray());
                                }
                            }
                          
                        }
                        else
                        {
                            return back()->with("error","Please fill at least one field in customer filter!");
                        }
                    }
                    else
                    {
                        if(!empty($request->customer_code) || !empty($request->customer_name) || !empty($request->customer_category) || !empty($request->territory) || !empty($request->country) && !empty($request->city) && !empty($request->state) && !empty($request->pincode))
                        {
                            //find Territory Data 
                            if(!empty($request->territory))
                            {
                                foreach($request->territory as $tCode)
                                {
                                    $territoryData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->where('territory_name',$tCode)->get();
                                    $allCusInfo = array_merge($allCusInfo, $territoryData->toArray());
                                }
                            }
                           
                            //find customer_code Data 
                            if(!empty($request->customer_code))
                            {
                                $cCodeData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->whereIn("bp_code",$request->customer_code)->get();
                                $allCusInfo = array_merge($allCusInfo, $cCodeData->toArray());
                            }

                            //find customer_name Data 
                            if(!empty($request->customer_name))
                            {
                                $cNameData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->whereIn("bp_name",$request->customer_name)->get();
                                $allCusInfo = array_merge($allCusInfo, $cNameData->toArray());
                            }
                     
                            //find customer_category Data 
                            if(!empty($request->customer_category))
                            {
                                foreach($request->customer_category as $cCat)
                                {
                                    $cCatData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->where('group_code',$cCat)->get();
                                    $allCusInfo = array_merge($allCusInfo, $cCatData->toArray());
                                }
                            }
                            
                            //find country Data 
                            if(!empty($request->country))
                            {
                                foreach($request->country as $cusCountry)
                                {
                                    $locationData = Customer_address::where("location_country",strtoupper($cusCountry))->get();
                                    $countryCusId = collect($locationData)->pluck("bp_code")->all();
                                    $countryCusData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->whereIn('bp_code',$countryCusId)->get();
                                    $allCusInfo = array_merge($allCusInfo, $countryCusData->toArray());
                                }
                            }

                            //find pincode Data 
                            // if(!empty($request->pincode))
                            // {
                            //     foreach($request->pincode as $cusPincode)
                            //     {
                            //         $locationPData = Customer_address::where("location_postal_code",$cusPincode)->get();
                            //         $pincodeCusId = collect($locationPData)->pluck("bp_code")->all();
                            //         $pincodeCusData = Customer_master::where("status",0)->whereIn('bp_code',$pincodeCusId)->get();
                            //         $allCusInfo = array_merge($allCusInfo, $pincodeCusData->toArray());
                            //     }
                            // }

                            //find state Data 
                            if(!empty($request->state))
                            {
                                foreach($request->state as $cusState)
                                {
                                    $locationSData = Customer_address::where("location_state",strtoupper($cusState))->get();
                                    $stateCusId = collect($locationSData)->pluck("bp_code")->all();
                                    $stateCusData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->whereIn('bp_code',$stateCusId)->get();
                                    $allCusInfo = array_merge($allCusInfo, $stateCusData->toArray());
                                }
                            }

                            //find city Data 
                            if(!empty($request->city))
                            {
                                foreach($request->city as $cusCity)
                                {
                                    $locationCData = Customer_address::where("location_city",strtoupper($cusCity))->get();
                                    $cityCusId = collect($locationCData)->pluck("bp_code")->all();
                                    $cityCusData = Customer_master::where("status",0)->where('u_ecomm','Yes')->where('valid','tNO')->whereIn('bp_code',$cityCusId)->get();
                                    $allCusInfo = array_merge($allCusInfo, $cityCusData->toArray());
                                }
                            }
                            
                        }
                        else
                        {
                            return back()->with("error","Please fill at least one field in customer filter!");
                        }
                    }
                }
                // End customer filter

                // Start invoice filter
                if(!empty($request->invoice_no_filter))
                { 
                    if(!empty($request->product_code) || !empty($request->product_category) || !empty($request->fromDate) || !empty($request->toDate))
                    {
                        $allCusData = [];
                        //Start Search by product Code
                        if(!empty($request->product_code))
                        { 
                            foreach($request->product_code as $pCode)
                            {
                                $pCodeData = Invoice_master_summary::where("ItemNo",'LIKE',$pCode)->get();
                                $DocEntry = collect($pCodeData)->pluck("DocEntry")->all();
                                $cus_bpCode = Invoice_master::whereIn('DocEntry',$DocEntry)->get();
                                $iDataCusId = collect($cus_bpCode)->pluck("BPCode")->all();
                                $cusMasData = Customer_master::whereIn('bp_code',$iDataCusId)->get();
                                $allCusData = array_merge($allCusData, $cusMasData->toArray());
                            }
                        }
                        //End Search by product Code
                        
           
                        if(!empty($request->product_category))
                        {
                            foreach($request->product_category as $pCategory)
                            {
                                $itemMas = Item_master::where("product_category",'LIKE',$pCategory)->get();
                                
                                $item_sku = collect($itemMas)->pluck("item_sku")->all();
                                $pCatData = Invoice_master_summary::whereIn("ItemNo",$item_sku)->get();
                                $catDocEntry = collect($pCatData)->pluck("DocEntry")->all();
                                $catCus_bpCode = Invoice_master::whereIn('DocEntry',$catDocEntry)->get();
                                $catCusId = collect($catCus_bpCode)->pluck("BPCode")->all();
                                $catCusMasData = Customer_master::whereIn('bp_code',$catCusId)->get();
                                $allCusData = array_merge($allCusData, $catCusMasData->toArray());
                            }
                        }
                        //End Search by product_category
                       
                        //Start Search by date range group
                        if(!empty($request->fromDate) && !empty($request->toDate))
                        {
                            $searchByDate = Invoice_master::whereBetween('PostingDate', [$request->fromDate, $request->toDate])->get();
                            $BPCode = collect($searchByDate)->pluck("BPCode")->all();
                            $searchDate = Customer_master::whereIn('bp_code', $BPCode)->get();
                            $allCusData = array_merge($allCusData, $searchDate->toArray());
                        }
                        else if(!empty($request->fromDate) || !empty($request->toDate))
                        {
                            return back()->with("error","Please fill both date field!");
                        }
                        //End Search by date range group
                    }
                    else
                    {
                        return back()->with("error","Please fill at least one field in invoice filter!");
                    }

                }
                // End invoice filter

                if(!empty($request->cus_no_filter) && !empty($request->invoice_no_filter))
                {
                    //both searched data merging in array
                    if(!empty($allCusInfo) || !empty($allCusData))
                    {
                        $bothSearchedDataMerged = array_merge($allCusInfo,$allCusData);
                        $data['search'] = collect($bothSearchedDataMerged)->unique('bp_code')->values()->all();
                    }
                    else
                    {
                        return back()->with("error","Record doesn't found!");
                    }
                }
                else
                {
                    if(!empty($request->cus_no_filter))
                    {
                        if(!empty($allCusInfo))
                        {
                            $data['search'] = collect($allCusInfo)->unique('bp_code')->values()->all();
                        }
                        else
                        {
                            return back()->with("error","Record doesn't found!");
                        }
                    }

                    if(!empty($request->invoice_no_filter))
                    {
                        if(!empty($allCusData))
                        {
                            $data['search'] = collect($allCusData)->unique('bp_code')->values()->all();
                        }
                        else
                        {
                            return back()->with("error","Record doesn't found!");
                        }
                    }
                }
            }
            else
            {
                return back()->with('error','Please checked at least one filter!');
            }

            $searchedData = $data['search'];
            $invalidEmail = [];
            $validEmail = [];
            $invalid = [];
            $valid = [];
            for($j=0; $j<count($searchedData); $j++)
            {
                $cus_email = $searchedData[$j]['email_id'];
                if(!empty($cus_email))
                {
                    $emailValidator = new EmailValidator();
                    if($emailValidator->isValid($cus_email, new RFCValidation())) 
                    {
                        $valid[] = array_merge($searchedData[$j],['email_status' => 1]);
                        $validEmail[] = 1; 
                        
                    } 
                    else 
                    {
                        $invalid[] = array_merge($searchedData[$j],['email_status' => 0]);
                        $invalidEmail[] = 0; 
                        
                    }
                }
                else
                {
                    $invalid[] = array_merge($searchedData[$j],['email_status' => 0]);
                    $invalidEmail[] = 0; 
                }
            }

            // $data['search'] = $searchedData;
            $data['validEmail'] = $valid;
            $data['invalidEmail'] = $invalid;
            $data['customer_group'] = Customer_group::get();
            return view("admin.createList.view",$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function pincode($pincode)
    {
        try{
            $data = Pincode_master::where("code",$pincode)->first();
            return $data;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }


    public function createGroup(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'group_name' => 'required|string|max:255|unique:group_lists',
                'select' => 'required|array',
            ]);
            if($validator->fails())
            {
                return back()->with("error",$validator->errors()->all());
            }

        //   for($i=0; $i<count($request->select); $i++)
        //   {
        //         $allBpCode[] = $request->select[$i];
        //   }
          
            $groupData = new GroupList;
            $groupData->group_name = $request->group_name;
            $groupData->customer_id = json_encode($request->select);
            $groupData->add_by = Auth::user()->id;
            if($groupData->save())
            {
                return redirect("/create-list/view")->with("success","Group Created Successfully!");
            }

        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function groupList()
    {
        try
        {
            session()->forget("group_id");
            $data['title'] = "Group List";
            $data['group'] = GroupList::latest()->get();
            return view("admin.createList.groupList",$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getGroupData(Request $request)
    {
        try
        {
            if($request->group_name != "choose group name")
            {
                session()->put("group_id",$request->group_name);
                
                $data['title'] = "Group List";
                $data['group'] = GroupList::latest()->get();

                $selectedGroup = GroupList::where("id",$request->group_name)->first();
                $allCusId = json_decode($selectedGroup->customer_id);
                for($i=0; $i<count($allCusId); $i++)
                {
                    $allCusData[] = Customer_master::where("bp_code",$allCusId[$i])->first();
                }
                
                $invalidEmail = [];
                $validEmail = [];
                for($j=0; $j<count($allCusData); $j++)
                {
                    $cus_email = $allCusData[$j]->email_id;
                    if(!empty($cus_email))
                    {
                        $emailValidator = new EmailValidator();
                        if($emailValidator->isValid($cus_email, new RFCValidation())) 
                        {
                            $validEmail[] = $allCusData[$j]->setAttribute('email_status', 1);
                            // $validEmail[] = 1; 
                        } 
                        else 
                        {
                            $invalidEmail[] =$allCusData[$j]->setAttribute('email_status', 0);
                            // $invalidEmail[] = 0; 
                        }
                    }
                    else
                    {
                        $invalidEmail[] =$allCusData[$j]->setAttribute('email_status', 0);
                    }
                }
                
                $data['group_data'] = $allCusData;
                $data['validEmail'] = $validEmail;
                $data['invalidEmail'] = $invalidEmail;
                $data['customer_group'] = Customer_group::get();
                return view("admin.createList.groupList",$data);
            }
            else
            {
                return redirect("create-list/view")->with("error","Please select group name!");
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function emailUpdate(Request $request,$id)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'email_id' => 'required|email|max:255',
            ]);
            if($validator->fails())
            {
                return back()->with('error',$validator->errors()->all());
            }
            else
            {
                $update = Customer_master::where("rowId",$id)->update([
                    'email_id' => $request->email_id
                ]);
                if($update)
                {
                    return back()->with("success","Email update at the position no : ".$request->position);
                }
                else
                {
                    return back()->with("success","Email update as previous email");
                }
            }   
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }   
    }

    public function detail()
    {
        try{
            $data['title'] = "Group Detail List";
            $data['list'] = GroupList::orderBy("id","DESC")->paginate(10);
 
            foreach($data['list'] as $key => $grpList)
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
                            // $allCusData[$j]->setAttribute('email_status', 1);
                            $valid[] = 1;
                        } 
                        else 
                        {
                            // $allCusData[$j]->setAttribute('email_status', 0);
                            $inValid[] = 0;  
                        }
                    }
                    else{
                        $inValid[] = 0; 
                    }
                }

                $allInvalid[$grpList->id] = count($inValid);
            }

            // $data['group_data'] = $allCusData;
            if(!empty($allInvalid))
            {
                $data['invalidEmail'] = $allInvalid;
            }
            else
            {
                $data['invalidEmail'] = [];
            }

            $data['totalGroup'] = GroupList::get();
            return view("admin.createList.detail",$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function duplicate(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'group_name' => 'required|string|max:255|unique:group_lists',
            ]);
            if($validator->fails())
            {
                return back()->with('error',$validator->errors()->all());
            }

            $groupData = GroupList::where("id",$request->oldGroupId)->first();
            if(!empty($groupData))
            {
                $newGroup = new GroupList;
                $newGroup->group_name = $request->group_name;
                $newGroup->customer_id = $groupData->customer_id;
                $newGroup->add_by = Auth::user()->id;
                if($newGroup->save())
                {
                    return redirect("/create-list/view")->with("success","Group list duplicated successfully");
                }
            }
            
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function sync($id)
    {
        try
        {
            $groupData = GroupList::where("id",$id)->where("add_by",Auth::user()->id)->first();
            // return $groupData->group_name;
            $totalCus = json_decode($groupData->customer_id);
            for($i=0; $i<count($totalCus); $i++)
            {
                $syncData = Customer_master::where("rowId",$totalCus[$i])->first();
                if(!empty($syncData))
                {
                    $syncAllData [] = $syncData->rowId;
                }
            }

            if(!empty($syncAllData))
            {
                $groupUpdate = GroupList::where("id",$id)->update([
                    'group_name' => $groupData->group_name,
                    'customer_id' => json_encode($syncAllData)
                ]);

                if($groupUpdate)
                {
                    return back()->with("success","Congrats You have synced Group list successfully");
                }
            }
            else
            {
                if(GroupList::where("id",$id)->where("add_by",Auth::user()->id)->delete())
                {
                    return back()->with("error","You haven't sync any contact, so your group deleted successfully!");
                }
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
            $deleteGroup = GroupList::where("id",$id)->delete();
            if($deleteGroup)
            {
                return back()->with("success","Group list deleted successfully");
            }
            else
            {
                return back()->with("error","Invalid access!");
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
