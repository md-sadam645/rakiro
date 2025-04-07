<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\User;
// use File;

class SubAdminController extends Controller
{
    public function index()
    {
        try{
            $data['title']='View SubAdmin';
            $data['list']= User::where("role",2)->latest()->paginate(10);
            $data['totalSubadmin']= User::where("role",2)->get();
            return view('admin.subAdmin.index',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function add()
    {
        try{
            $data['title']='Create SubAdmin';
            return view('admin.subAdmin.create',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function save(request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                // 'profile' => 'required|mimes:jpeg,png,jpg',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                // 'mobile' => 'required|string|min:10|max:10',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails())
            { 
                return back()->withErrors($validator)->withInput();
            }
            else
            {
                // $fname = time().'.'.$request->profile->extension();  
                // $fileName =date('d-m-Y-H-i-s').$fname;
                    
                // $request->profile->move(public_path('gallery'), $fileName);
                // $profile = "gallery/".$fileName;
            
                $subAdmin= new User();
                // $subAdmin->photo = $profile;
                $subAdmin->name= $request->name;
                $subAdmin->email= $request->email;
                // $subAdmin->mobile= $request->mobile;
                $subAdmin->password= Hash::make($request->password);
                // $subAdmin->address= $request->address;
                $subAdmin->status = $request->status;
                $subAdmin->role= 2;

                if($subAdmin->save())
                {
                    return back()->with('success','SubAdmin created successfully');
                }
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
            $data['title']='Edit SubAdmin';
            $data['list']= User::where('id',$id)->first();
            return view('admin.subAdmin.edit',$data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function update(request $request,$id)
    {
        try{

            if($request->password == "")
            {
                $uData = User::where('id',$id)->first();
                $password = $uData->password;
            }
            else
            {
                $password = Hash::make($request->password);
            }
        
            // if($request->profile == "")
            // {
            //     $profile = $request->oldimage;
            // }
            // else
            // {
            //     //-- unlink image
            //     $image_path = public_path($request->oldimage);
            //     if(File::exists($image_path))
            //     {
            //         File::delete($image_path); 
            //     }
            //     //-- unlink close

            //     $fname = time().'.'.$request->profile->extension('jpg,png,jpeg');  
            //     $fileName =date('d-m-Y-H-i-s').$fname;
            //     $request->profile->move(public_path('gallery'), $fileName);
            //     $profile = "gallery/".$fileName;
            //     // $profile;
            // }

            $update = User::where('id',$id)->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$password,
                // 'mobile'=>$request->mobile,
                // 'photo'=>$profile,
                'status'=>$request->status,
                // 'address'=>$request->address
            ]);
            if($update)
            {
                return back()->with('success','SubAdmin Updated Successfully');
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try{
            //-- unlink image
            // $userAllDetails = User::where('id',$id)->first();
            // $image_path = public_path($userAllDetails->photo);
            // if(File::exists($image_path))
            // {
            //     File::delete($image_path); 
            //     if(User::where('id',$id)->delete())
            //     {
            //         return back()->with('success','SubAdmin deleted successfully');
            //     }
            // }
            //-- unlink close 

            if(User::where('id',$id)->delete())
            {
                return back()->with('success','SubAdmin deleted successfully');
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
