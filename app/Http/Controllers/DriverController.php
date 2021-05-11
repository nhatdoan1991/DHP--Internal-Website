<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DriverResource;
use App\Models\GroupOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $driver = User::where('role', 'driver')->get();

        return DriverResource::collection($driver);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function date($date)
    {
        $result = User::with([
            'grouporder' =>
            function ($q) use ($date) {
                $q->where('deliverydate', 'like', $date);
            }
        ])->get();
        return DriverResource::collection($result);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|max:255',
            'phonenumber' => 'required|max:11',
            'role' => 'required',
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return Response()->json([
                "error" => " Missing parameter"
            ]);
        }
        user::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return new DriverResource(User::last());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $driver = User::where('role', 'driver')->where('id', $id)->get();

        return DriverResource::collection($driver);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $driver = User::find($id);
        if ($request->has('firstname')) {
            $driver->update(['firstname' => $request->firstname]);
        }
        if ($request->has('lastname')) {
            $driver->update(['lastname' => $request->lastname]);
        }
        if ($request->has('email')) {
            $driver->update(['email' => $request->email]);
        }
        if ($request->has('phonenumber')) {
            $driver->update(['phonenumber' => $request->phonenumber]);
        }
        if ($request->has('role')) {
            $driver->update(['role' => $request->role]);
        }
        if ($request->has('password')) {
            $driver->update(['password' => Hash::make($request->password)]);
        }
        return new DriverResource(User::find($id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver =  User::find($id);
        if ($driver != null) {
            $driver->delete($id);
            return response()->json(["mesage" => "delete successfully driver " . $id]);
        }

        return response()->json(["mesage" => "delete unsuccessfully. Please check your URL/method again"]);
    }
}
