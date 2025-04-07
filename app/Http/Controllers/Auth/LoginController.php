<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\User;

class LoginController extends Controller
{
    public function adminlogin()
    {
        try
        {
            $data['title'] ='Login';
            if(Auth::check())
            {
                if(Auth::user()->role==1)
                {
                    return back()->with('error','Need to logout!');
                }
                if(Auth::user()->role==2)
                {
                    return back()->with('success','Need to logout!');
                }
            }
            return view('admin/auth/login',$data);
        }
        catch (\Exception $e) 
        {
            //error msg
            return $e->getMessage();
        }
    }

    // public function authadmin(Request $request)
    // {

    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
            
    //     ]);

    //     if (Auth::attempt($credentials)) 
    //     {
     
    //         if(Auth::user()->role==1){
    //             //ADMIN ROLE
                
    //             return redirect('dashboard')->with('success','User Auth Successfully');

    //         }
    //         if(Auth::user()->role==2){
    //             //HUB ROLE
    //             return redirect('hubpanel')->with('success','User Auth Successfully');

    //         }
    //         if(Auth::user()->role==3){
    //             //CUSTOMER ROLE
    //             echo "Customer Login";
                
    //         }
    //         //Intent use to redirect back the locotion comes from
    //         // return redirect()->intended('admindashboard');


    //     }
    //     else
    //     {
            
    //     }
    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ])->onlyInput('email');
        
    // }

    //Start - Admin & SubAdmin Login
    public function authAdmin(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
            if($validator->fails())
            {
                return back()->with('error',$validator->errors()->all());
            }

            $username = $request->email;
            $password = $request->password;

            $checkUser = User::where("email",$username)->first();
            if(!empty($checkUser))
            {  
                    //current date time
                    $now = Carbon::now();
                    $dateTime = $now->format('Y-m-d g:i A');

                    //admin login
                    if($checkUser->role == 1)
                    {
                        if(Auth::attempt(['email' => $username, 'password' => $password]))
                        {
                            $update = User::where("id",Auth::user()->id)->update(array(
                                "last_login" => $dateTime
                            ));
                            if($update)
                            {
                                return redirect('/dashboard')->with('success', 'Login Success');
                            } 
                        }
                        else
                        {
                            return back()->with("error","Wrong password !");
                        }
                    }

                    //Start - subAdmin login
                    if($checkUser->role == 2 && $checkUser->status == 1)
                    {
                        
                        if(Auth::attempt(['email' => $username, 'password' => $password]))
                        {
                            return redirect('/dashboard')->with('success', 'Login Success');
                        }
                        else
                        {
                            return back()->with("error","Wrong password !");
                        }
                    }
                    else
                    {
                        return back()->with("error","Account Inactive, Please Contact Your Admin!");   
                    }  
                    //End - admin login    
                
            }
            else
            {
                return back()->with("error","User not registered!");
            }
        }
        catch (\Exception $e) 
        {
            //error msg
            return $e->getMessage();
        }
    }
    //End - Admin & SubAdmin  Login

    public function unauthorized()
    {
        Auth::logout();
        return redirect("/")->with("Unauthorized Access!"); 
    }

    public function profile()
    {
        try{
            $data['title'] = "User Profile";
            return view("admin.auth.profile",$data);
        }
        catch (\Exception $e) 
        {
            //error msg
            return $e->getMessage();
        }
    }

    public function profileUpdate(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                // 'mobile' => 'required|string|min:10|max:10',
            ]);
            if($validator->fails())
            {
                return redirect("/profile/view")->with('error',$validator->errors()->all());
            }
            else
            {
                if($request->photo == "")
                {
                    $photo = $request->oldimage;
                }
                else
                {
                    //-- unlink image
                    $image_path = public_path($request->oldimage);
                    if(File::exists($image_path))
                    {
                        File::delete($image_path); 
                    }
                    //-- unlink close

                    $fname = time().'.'.$request->photo->extension('jpg,png,jpeg');  
                    $fileName =date('d-m-Y-H-i-s').$fname;
                    $request->photo->move(public_path('gallery'), $fileName);
                    $photo = "gallery/".$fileName;
                }

                $update = User::where("id", Auth::user()->id)->update(array(
                    'name' => $request['name'],
                    'mobile' => $request['mobile'],
                    'email' => $request['email'],
                    'address' => $request['address'],
                    'photo' => $photo,
                ));
                if($update)
                {
                    return redirect("/profile/view")->with("success","Profile update successfully!");
                }
            }
            
        }
        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }

    public function changePassword()
    {
        try{
            $data['title'] = "Change Password";
            return view("admin.auth.changePassword",$data);
        }
        catch (\Exception $e) 
        {
            //error msg
            return $e->getMessage();
        }
    }

    //Start - Password Update
    function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required| min:6'
            
        ]);
        if ($validator->fails())
        { 
            return back()->withErrors($validator)->withInput();
        }
        else
        {
            $user =  User::where("id", Auth::user()->id)->first();
            if($user && password_verify($request->current_password, $user->password))
            {
                $update = $user->update(array(
                    'password' =>  Hash::make($request['password_confirmation']),
                ));
                if($update)
                {
                    Auth::logout();
                    return redirect("/")->with("success","Your password was changed successfully!");
                }
            }
            else
            {
                return back()->with("error","Current Password Doesn't Matched !");
                
            }
        }
    }
    //End - Password Update

    public function logout_admin()
    {
        try
        {
            Auth::logout();
            return redirect('/');
        }
        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }
}
