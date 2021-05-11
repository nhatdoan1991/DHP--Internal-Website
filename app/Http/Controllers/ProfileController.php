<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }
    public function edit(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phonenumber' => $request->phonenumber,
        ]);
        return back()->with('message', '<div class ="alert alert-success alert-dhp">Update Profile Successfully</div>');
    }
    public function resetpassword(Request $request)
    {
        $user = Auth::user();
        if (password_verify($request->password, $user->password)) {
            if ($request->newpassword == $request->confirmpassword) {
                $user->update([
                    'password' => Hash::make($request->newpassword)
                ]);
                $user->save();
                return back()->with('message', '<div class ="alert alert-success alert-dhp">Reset Password Successfully</div>');
            } else {
                return back()->with('message', '<div class ="alert alert-danger alert-dhp">New passwords dont match</div>');
            }
        } else {
            return back()->with('message', '<div class ="alert alert-danger alert-dhp">Fail to reset password</div>');
        }
    }
    public function index()
    {
        return view('profile');
    }

    public function driverProfile()
    {
        return view('driver.profile');
    }
}
