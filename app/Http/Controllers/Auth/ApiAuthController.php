<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    //Start - register user
    public function register (Request $request)
    {
        $user_data = User::where('email', $request['email'])->where('role', '3')->first();
        
        if(!empty($user_data))
        {
            return response(['status'=>1,'msg'=>"You've already registered!"],200);  
        }
        else
        {
            //new register
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'mobile' => 'required|string|min:10|max:10',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails())
            {
                return response(['status'=>0,'errors'=>$validator->errors()->all()],200);
            }
            
        
            //creating random refcode
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $nameCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $refCode = '';
            // $randomName = '';
            
            for($i = 0; $i < 7; $i++)
            {
                $index = rand(0, strlen($characters) - 1);
                // $index2 = rand(0, strlen($nameCharacters) - 1);
                $refCode .= $characters[$index];
                // $randomName .= $nameCharacters[$index2];
            }
          
            // $mob_otp = mt_rand(1111,9999);
            $request['refcode'] = $refCode;
            // $request['name'] = $randomName;
            // $request['mob_otp'] = "1234";
            $request['role'] = '3';
            $request['password']= Hash::make($request['password']);
            $request['photo'] = "https://app.rummyagent.com/images/avtaar/avtaar.jpg";
            
            $request['avatar'] = json_encode(["https://app.rummyagent.com/images/avtaar/avtaar.jpg","https://app.rummyagent.com//images/avtaar/male.png","https://app.rummyagent.com//images/avtaar/male1.png","https://app.rummyagent.com//images/avtaar/female.png","https://app.rummyagent.com//images/avtaar/female1.png"]);
            
            $user = User::create($request->toArray());
            if($user)
            {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                // return response(['status'=>1,'msg'=>'Sent OTP','token'=>$token],200);
                return response(['status'=>1,'msg'=>'You have been successfully Registered!','token'=>$token],200);
            }
            else
            {
                return response(['status'=>0,'msg'=>'Invalid Register!'],200);
            }
          
        }
    }
    //End - register user
    
    
    //Start - login
    public function login (Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 200);
        }

        $user = User::where('email', $request->email)->first();
        if(!empty($user))
        {
            if(Hash::check($request->password, $user->password))
            {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                return response(['status'=>1,'msg'=>'You have been successfully Login!','token'=>$token],200);
            }
            else
            {
                return response(['status'=>0,'msg'=>"Password mismatch"],200);
            }
        }
        else
        {
            return response(['status'=>0,'msg'=>"User doesn't exist"],200);
        }
    }
    //End - login

    //Start - verify otp
    // public function verifyOtp (Request $request)
    // {
    //     $userDetail= $request->user()->token();
    //     $uid = $userDetail->user_id;
        
    //     $data = User::where('id', $uid)->get();
        
    //     if($data[0]->mob_otp == $request['mob_otp'])
    //     {
    //         $userData = User::where('id', $uid)->update(array('status' => "1"));
    //         if($userData)
    //         {
    //             return response(['status'=>1,'msg'=> "You have been successfully Login!"],200);
    //         }
           
    //     }
    //     else
    //     {
    //         return response(['status'=>0,'msg'=>'Wrong OTP'],200);
    //     }
        
    // }
    //End - verify otp
    



    //Start - profile
    public function myProfile (Request $request)
    {
        $userDetail= $request->user()->token();
        $uid = $userDetail->user_id;

        $allData = User::where('id', $uid)->first();
        $data["name"] = $allData->name;
        $data["profile"] = $allData->photo;
        
        for($i=0; $i<count(json_decode($allData->avatar)); $i++)
        {
            $data["avatar"][$i] = json_decode($allData->avatar)[$i];
        }
        
        // return json_decode($data->avatar);
        if(!empty($allData))
        {
            return response(['status'=>1,'data'=> $data],200);   
        }
        
    }
    //End - profile

    //Start - profile update
    public function profileUpdate (Request $request)
    {
        
        
        $userDetail= $request->user()->token();
        $uid = $userDetail->user_id;
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'profile' => 'required|mimes:jpeg,png,jpg',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        else
        {
            $fname = time().'.'.$request->profile->extension();  
            $fileName =date('d-m-Y-H-i-s').$fname;
                
            $request->profile->move(public_path('gallery'), $fileName);
            $profileImage= $fileName;

            $update = User::where('id', $uid)->update(array(
                'name' => $request->name,
                'photo' => "https://app.rummyagent.com/gallery/".$profileImage,
            ));
            if($update)
            {
                return response(['status'=>1,'msg'=> "You have been successfully update profile!"],200);   
            }
            else
            {
                return response(['status'=>0,'msg'=>'Failed to update profile'],200);
            }
        }
        
    }
    //End - profile update


    //Start - password Change
    public function passwordChange (Request $request)
    {
        $userDetail= $request->user()->token();
        $uid = $userDetail->user_id;
        
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|min:8'
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user =  User::where("id", $uid)->first();
        if($user && password_verify($request->old_password, $user->password))
        {
            $update = $user->update(array(
                'password' =>  Hash::make($request['password']),
            ));
            if($update)
            {
                return response(['status'=>1,'msg'=> "Password Changed Successfully!"],200);
            }
        }
        else
        {
            return response(['status'=>1,'msg'=> "Old Password Doesn't Matched!"],200);
            
        }
    }
    //End - password Change

    //logout
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
