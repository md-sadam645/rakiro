<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SmtpSetupController extends Controller
{
    public function index()
    {
        try{

            $data['title'] = "Create Smtp Setup";
            return view("admin.smtpSetup.index",$data);
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function add(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'from_address' => 'required|string|email|max:255|unique:smtp_setups',
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                $smtp = new SmtpSetup;
                $smtp->name = str_replace(" ","",$request->name);
                $smtp->from_address = $request->from_address;
                $smtp->add_by = Auth::user()->id;
                if($smtp->save())
                {
                    return back()->with("success","Smtp setup created successfully!");
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

            $data['title'] = "View Smtp Setup";
            $data['list'] = SmtpSetup::where("add_by",Auth::user()->id)->latest()->paginate(10);
            $data['totalEmailSetup'] = SmtpSetup::where("add_by",Auth::user()->id)->get();
            return view("admin.smtpSetup.view",$data);
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function edit($id)
    {
        try{

            $data['title'] = "Edit Smtp Setup";
            $data['list'] = SmtpSetup::where("id",$id)->first();
            return view("admin.smtpSetup.edit",$data);
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'from_address' => 'required|string|email|max:255',
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                $update = SmtpSetup::where("id",$id)->update([
                    "name" => str_replace(" ","",$request->name),
                    "from_address" => $request->from_address,
                ]);
                if($update)
                {
                    return back()->with("success","Smtp setup updated successfully!");
                }
            }
            
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function smtpSetup(Request $request)
    {
        //smtp setup dynamically
        $smtpData = SmtpSetup::where("from_address",$request->emailFrom)->first();
        if(!empty($smtpData))
        {
            $key = ['MAIL_FROM_ADDRESS','MAIL_FROM_NAME'];
            $value = [$smtpData->from_address,$smtpData->name];
            for($i=0; $i<count($key); $i++)
            {
                file_put_contents(app()->environmentFilePath(), 
                str_replace($key[$i] . '='. env($key[$i]),
                $key[$i] . '=' . $value[$i],file_get_contents(app()->environmentFilePath())));
            }
            
            return "Smtp setup success!";
        }
        else
        {
            return "Smtp setup failed!"; 
        }
    }


    public function delete($id)
    {
        try{
            if(SmtpSetup::where("id",$id)->delete())
            {
                return back()->with("success","Smtp setup deleted successfully!");
            }
           
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
