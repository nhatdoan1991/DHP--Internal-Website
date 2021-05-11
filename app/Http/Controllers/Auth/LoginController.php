<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {

        $this->middleware(['guest']);
    }

    public function index()
    {

        return view('auth.login');
    }

    protected $redirectTo = '/';

    public function store(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember_me = $request->has('remember_me') ? true : false;
        if (Auth::attempt($request->only('email', 'password'), $remember_me)) {

            $role = auth()->user()->role;
            switch ($role) {
                case 'operator':

                    return redirect()->route('list')->with('message', '<div class ="alert alert-success alert-dhp">Login Complete</div>');
                    break;
                case 'driver':
                    return redirect('/driverhome')->with('message', '<div class ="alert alert-success alert-dhp">Login Complete</div>');
                    break;
            }
        } else {
            return back()->with('status', 'Invalid login details');
        }
    }
    public function login(Request $request)
    {
        //dd($this);
        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();
            $user->generateToken();

            return response()->json([
                'data' => $user->toArray(),
            ]);
        }

        return response()->json([
            "Message" => "Wrong credentials",
        ]);
    }
    public function logout(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {

            $user = Auth::user();
            $user->api_token = null;
            $user->save();

            return response()->json([
                'data' => $user->toArray(),
            ]);
        }

        return response()->json([
            "Message" => "Wrong credentials",
        ]);
    }
}
