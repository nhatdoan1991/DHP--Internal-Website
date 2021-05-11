<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    protected $redirectTo = '/';

    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //validate
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|max:255',
            'phonenumber' => 'required|max:11',
            'role' => 'required',
            'password' => 'required|confirmed',
        ]);

        user::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60),
        ]);

        return redirect('register')->with('message', '<div class ="alert alert-success alert-dhp">Registration Complete</div>');
    }
}
