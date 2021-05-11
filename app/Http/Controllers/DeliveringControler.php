<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupOrder;
use Illuminate\Support\Facades\Response;

class DeliveringControler extends Controller
{
    // only authorize user can view this page
    public function __construct()
    {

        $this->middleware(['auth']);
    }
    //
    public function index()
    {
        $temp = Order::with('item', 'customer', 'grouporder', 'grouporder.user', 'message')
            ->where('status', 3)
            ->get();

        $groups = $temp->groupBy('grouporderid');
        return view('delivering.delivering', [
            'grouporder' => $groups
        ]);
    }
    public function updateUnfulfilled(Request $request)
    {
        if ($request->has('status')) {
            $order = Order::find($request->orderid);
            $order->update(['deliverystatus' => $request->status]);
        }
        Message::create([
            'orderid' => $request->orderid,
            'userid' =>  Auth::id(),
            'message' => $request->message,
            'isOperator' => true,
            'isRead' => false,
        ]);
        return redirect('delivering')->with('message', '<div class ="alert alert-success alert-dhp">Change Status Successfully</div>');
    }
    public function pusharchive($groupid)
    {
        $orders = Order::where('status', 3)->where('grouporderid', $groupid)->get();
        foreach ($orders as $order) {
            $order->update(['status' => 4]);
            $order->save();
        }
        return redirect('delivering')->with('message', '<div class ="alert alert-success alert-dhp">Push to Archive Successfully</div>');
    }
}
