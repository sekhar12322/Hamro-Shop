<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AdminProfileController extends Controller
{
    //Admin Profile
    public function profile(){
        $admin = Auth::guard('admin')->user();

        return view('admin.profile', compact('admin') );
    }


    public function updateProfile(Request $request, $id){
        $data=$request->all();
       $rule=[
           'name' => 'required|max:255',
           'email' => 'required|email|max:255',
           'phone' => 'required|max:255',
           'address' => 'required|max:255',
       ];

       $customMessages=[
           'name.required' => 'Please Enter the Name',
           'name.max' => 'You are not allowed to enter more than 255 characters',
           'email.required' => 'please enter email',
           'email.max' => 'You are not allowed to enter more than 255 characters',
           'email.email' => 'Please enter a valid email',
           'phone.required' => 'please enter Phone',
           'address.required' => 'please enter Address',
       ];

       $this->validate($request, $rule, $customMessages);
       $admin_id = Auth::guard('admin')->user()->id;
       $admin=Admin::findorFail($admin_id);
       $admin->name = $data['name'];
       $admin->email = $data['email'];
       $admin->phone = $data['phone'];
       $admin->address = $data['address'];

       $random = Str::random(10);
       if($request->hasFile('image')){
           $image_tmp = $request->file( 'image');
           if($image_tmp->isValid()){
               $extension = $image_tmp->getClientOriginalExtension();
               $filename = $random. '.'. $extension;
                $image_path = 'public/uploads/admin/'. $filename;
               Image::make($image_tmp)->save($image_path);
               $admin->image = $filename;
           }
       }
       $admin->save();
       Session::flash('success_message', 'Admin Profile has been updated successfully');
       return redirect()->back();
        }

//Admin Passwod Update
public function changePassword(){
        $user = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        return view('admin.changePassword', compact('user'));
}


//checking current password

public function chkUserPassword(Request $request){
        $data = $request->all();
        $current_password = $data['current_password'];
        $user_id = Auth::guard('admin')->user()->id;
        $check_password = Admin::where('id', $user_id)->first();
        if(Hash::check($current_password, $check_password->password)){
            return "true"; die;
        }else{
            return "false"; die;
        }
    }


    //update admin password
    public function UpdatePassword(Request $request, $id){
        $validateData = $request->validate([
            'current_password' =>  'required|max:255|min:6',
            'password' => 'required|min:6',
            'confirm_password' => 'required_with:password|same:password|min:6'
        ]);
        $user = Admin::where('email', Auth::guard('admin')->user()->email)->first();
        $current_user_password = $user->password;
        $data = $request->all();
        if (Hash::check($data['current_password'], $current_user_password)){
            $user->password = bcrypt($data['password']);
            $user->save();
            Session::flash('success_message', 'Admin Profile has been updated successfully');
            return redirect()->back();
        } else {
            Session::flash('error_message', 'Your current password doesnt match with database');
            return redirect()->back();
        }
    }
}

