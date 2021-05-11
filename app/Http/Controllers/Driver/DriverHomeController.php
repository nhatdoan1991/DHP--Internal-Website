<?php

namespace App\Http\Controllers\Driver;

//require __DIR__ . '/vendor/autoload.php';

use Twilio\Rest\Client;
use Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GroupOrder;
use App\Models\Item;
use App\Models\Order;
use App\Models\Message;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Facade\FlareClient\Http\Client as HttpClient;


class DriverHomeController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $today = new Carbon();
        $today = $today->format('Y-m-d');

        $upcominggroups = GroupOrder::with('order', 'order.customer', 'order.item')
            ->where('refuserid', auth()->id())
            ->whereHas('order', function ($q) {
                $q->where('deliverystatus', 1)
                    ->orWhere('deliverystatus', 2);
            })

            ->orderBy('deliverydate', 'asc')
            ->get();
        $lastmonth = today()->subDays(30);

        $completedgroups = GroupOrder::with('order',)
            ->where('refuserid', auth()->id())
            ->where('deliverydate', '>=', $lastmonth)
            ->whereHas('order', function ($q) {
                $q->where('deliverystatus', 3)
                    ->orWhere('deliverystatus', 4);
            })
            ->orderBy('deliverydate', 'desc')
            ->get();


        

        return view(
            'driver.home',
            [
                'upcoming' => $upcominggroups,
                'today' => $today,
                'completed' => $completedgroups
            ]
        );
    }

    public function driver(Request $request, $id)
    {
        
        $orders = Order::with('customer', 'item', 'message')
            ->where('grouporderid', $id)->orderBy('index', 'asc')->get();


        return view('driver.driver', ['orders' => $orders]);
    }

    public function pickup(Request $request, $id)
    {
        $orders = Order::with('customer', 'item')
            ->where('grouporderid', $id)->get();
        foreach ($orders as $order) {
            $order->update(['deliverystatus' => 2]);
        }
        return back();
    }
    public function reportorder(Request $request)
    {
        $orders = Order::where('grouporderid', $request['groupid'])->get();
        // $message = Message::where('orderid', $request['orderid'])->get();
        $request->validate([
            'message' => 'required',
        ]);
        Message::create([
            'orderid' => $request->orderid,
            'userid' =>  Auth::id(),
            'message' => $request->message,
            'isOperator' => false,
            'isRead' => false,
        ]);
        foreach ($orders as $order) {

            if ($order->id == $request['orderid']) {
                $order->update(['reportnote' => $request['message']]);
                $order->update(['deliverystatus' => 3]);
                $order->update(['index' => $orders->count()]);
            } else {
                $order->update(['index' => ($order->index - 1)]);
            }
        }

        return back();
    }
    public function markmessageread(Request $request)
    {
        $message = Message::where('orderid', $request->orderid)->where('isOperator', true)->orderBy('id', 'desc')->get();
        foreach ($message as $m) {
            $m->update(['isRead' => true]);
        }
        if (Order::find($request->orderid)->deliverystatus == 2) {
            $orders = Order::where('grouporderid', $request['groupid'])->get();
            foreach ($orders as $order) {
                if ($orders->where('deliverystatus', 2)->count() > 1) {
                    if ($order->index == $request['index']) {
                        $order->update(['index' => $orders->where('deliverystatus', 2)->count()]);
                    } elseif ($order->index < $request['index'] && $order->index >= $orders->where('deliverystatus', 2)->count()) {
                        $order->update(['index' => ($order->index + 1)]);
                    }
                } else {

                    if ($order->index == $request['index']) {
                        $order->update(['index' => 1]);
                    } elseif ($order->index < $request['index']) {
                        $order->update(['index' => ($order->index + 1)]);
                    }
                }
            }
        }
        return back();
    }
    public function completeorder(Request $request)
    {
        if ($request->has('customer_phonenumber')) {
            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = config('app.twilio_id');
            $auth_token = config('app.twilio_token');

            // A Twilio number you own with SMS capabilities
            $twilio_number = "+17042766630";
            $customer_number = "+1" . $request['customer_phonenumber'];
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                $customer_number,
                //'+19162983484',
                array(
                    'from' => $twilio_number,
                    'body' => 'You Order from Delta Hand Pie was just delivered to you'
                )
            );
        }
        $orders = Order::where('grouporderid', $request['groupid'])->get();
        foreach ($orders as $order) {
            if ($order->id == $request['orderid']) {
                $order->update(['deliverystatus' => 4]);
                $order->update(['index' => $orders->count()]);
            } else {
                $order->update(['index' => ($order->index - 1)]);
            }
        }
        if ($request->has('customer_phonenumber')) {
            return back()->with('message', '<div class ="alert alert-success alert-dhp">A Message was just sent to the customer. The order is now complete!</div>');
        }
        return back();
    }
    public function swaporder(Request $request)
    {
        //dd($request['index']);
        $orders = Order::where('grouporderid', $request['groupid'])->get();
        foreach ($orders as $order) {
            if ($order->index == $request['index']) {
                $order->update(['index' => 1]);
            } elseif ($order->index < $request['index']) {
                $order->update(['index' => ($order->index + 1)]);
            }
        }
        return back();
    }
}
