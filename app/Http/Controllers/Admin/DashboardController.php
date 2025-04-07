<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmailCompose;
use App\Models\Customer_master;
use App\Models\GroupList;
use App\Models\Failed_jobs;
use Illuminate\Support\Facades\Artisan;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class DashboardController extends Controller
{
    public function index()
    {
        try
        {
            $data['title'] = 'Dashboard';
            return view('admin.dashboard',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function dashboard()
    {
        try{
            $data['list'] = GroupList::get();

            if(!empty( $data['list']))
            {
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
                                $allCusData->setAttribute('email_status', 1);
                                $valid[] = 1;
                            } 
                            else 
                            {
                                $inValid[] = $allCusData->setAttribute('email_status', 0);
                                // $inValid[] = 0;  
                            }
                        }
                    }
                   
                    $allInvalid[$grpList->id] = $inValid;
                  
                }

                if(!empty($allInvalid))
                {
                    $data['invalidEmail'] = $allInvalid;
                }
                else
                {
                    $data['invalidEmail'] = [];
                }
            }
            
            $data['title'] = 'Dashboard';
            $data['customer'] = Customer_master::count();
            $data['user'] = User::where('role',2)->latest()->paginate(4);
            $data['sAdmin'] = User::where('role',2)->count();
            $data['group'] = GroupList::count();
            $data['sEmail'] = EmailCompose::where('status',0)->count();
            
            return view('admin.dashboard.index',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function queueWork()
    {
        try
        {
            Artisan::call('queue:work --stop-when-empty');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    //Re-send all failed emails
    public function failedWork()
    {
        try
        {
            $failedJobs = Failed_jobs::get();
            if(count($failedJobs) != 0)
            {
                // Artisan::call('queue:retry-batch all');
                Artisan::call('queue:retry all');
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
