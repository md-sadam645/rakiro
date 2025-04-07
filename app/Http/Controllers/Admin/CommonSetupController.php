<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\CommonSetup;

class CommonSetupController extends Controller
{
    public function index()
    {
        try{

            $data['title'] = "Create Common Setup";
            $data['cSetup'] = CommonSetup::first();
            return view("admin.commonSetup.index",$data);
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'mailer' => 'required|string|max:255',
                'host' => 'required|string|max:255',
                'port' => 'required|integer',
                'username' => 'required|string|max:255',
                'password' => 'required|string',
                'encryption' => 'required|string'
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {

                //Email Config dynamically
                $key = ['MAIL_MAILER','MAIL_HOST','MAIL_PORT','MAIL_USERNAME','MAIL_PASSWORD','MAIL_ENCRYPTION'];
                $value = [$request->mailer,$request->host,$request->port,$request->username,$request->password,$request->encryption];
                for($i=0; $i<count($key); $i++)
                {
                    file_put_contents(app()->environmentFilePath(), 
                    str_replace($key[$i] . '='. env($key[$i]),
                    $key[$i] . '=' . $value[$i],file_get_contents(app()->environmentFilePath())));
                }
                

               $cSetupData = CommonSetup::first();
               if(!empty($cSetupData))
               {
                    $cSetupId = CommonSetup::first();
                    $update = CommonSetup::where("id",$cSetupId->id)->update([
                        "mailer" => $request->mailer,
                        "host" => $request->host,
                        "port" => $request->port,
                        "username" => $request->username,
                        "password" => $request->password,
                        "encryption" => $request->encryption,
                    ]);
                    if($update)
                    {
                        return back()->with("success","Common setup updated successfully!");
                    }
               }
               else
               {
                    $cSetup = new CommonSetup;
                    $cSetup->mailer = $request->mailer;
                    $cSetup->host = $request->host;
                    $cSetup->port = $request->port;
                    $cSetup->username = $request->username;
                    $cSetup->password = $request->password;
                    $cSetup->encryption = $request->encryption;
                    $cSetup->add_by = Auth::user()->id;
                    if($cSetup->save())
                    {
                        return back()->with("success","Common Setup Created Successfully");
                    }
               }
            }
         }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
