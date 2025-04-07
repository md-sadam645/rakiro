<?php

namespace App\Http\Controllers\admin;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class settingController extends Controller
{
    //Start - App version
    //app version view
    function appVersionIndex()
    {
        $data['title'] = "App Version";
        $appVersion = Setting::where("option_name","app_version")->first();
        if(!empty($appVersion))
        {
            $data['list'] = $appVersion;
        }
        else
        {
            $data['list'] = "";
        }

        return view("admin.Setting.appVersion",$data);
    }


    //app version update
    function appVersionUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_version' => 'required|string|max:15',
        ]);
        if($validator->fails())
        {
            return back()->with('error',$validator->errors()->all());
        }

        $update = Setting::where("option_name","app_version")->update(array(
            "option_value" => $request->app_version
        ));

        if($update)
        {
            return back()->with("success","App Version Update Successfully!");
        }
        
    }
    //End - App version

    //Start - Rules
    //rules view
    function rulesIndex()
    {
        $data['title'] = "Rules";
        $appVersion = Setting::where("option_name","rules")->first();
        if(!empty($appVersion))
        {
            $data['list'] = $appVersion;
        }
        else
        {
            $data['list'] = "";
        }

        return view("admin.Setting.rules",$data);
    }

    //rules update
    function rulesUpdate(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'rules' => 'required|string',
        ]);
        if($validator->fails())
        {
            return back()->with('error',$validator->errors()->all());
        }

        $update = Setting::where("option_name","rules")->update(array(
            "option_value" => $request->rules
        ));

        if($update)
        {
            return back()->with("success","Rules Update Successfully!");
        }
        
    }
    //End - Rules
}
