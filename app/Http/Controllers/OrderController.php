<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api')->except(['index', 'show', 'destroy', 'store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('item')->paginate(15);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Order::all();

        //validating the information entered
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'zipcode' => 'required|max:255',
            'state' => 'required',
            'phonenumber' => 'required|max:255',
            'deliverydate' => 'required',
        ]);
        if ($validator->fails()) {
            return Response()->json([
                "error" => " Missing parameter"
            ]);
        }
        //creating a row in the customer table
        Customer::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'state' => $request->state,
            'phonenumber' => $request->phonenumber,
        ]);

        //getting the new customer to be used in the new order
        $last = Customer::all()->last();

        //creating a new order row
        Order::create([
            'status' => 1,
            'customerid' => $last->id,
            'deliveryinstruction' => $request->deliveryinstruction,
            'deliverydate' => $request->deliverydate,

        ]);

        //getting the new order to add items to
        $lastOrder = Order::all()->last();

        //adding all the items added on the form to order
        for ($i = 0; $i < count($request->myItems); $i++) {
            //making sure all the items info was entered correctly
            if ($request->myItems[$i] != null && $request->myQuantity[$i] != null) {
                Item::create([
                    'orderid' => $lastOrder->id,
                    'itemname' => $request->myItems[$i],
                    'itemquantity' => $request->myQuantity[$i],
                ]);
            }
        }
        return new OrderResource($lastOrder);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new OrderResource(Order::with('item')->findOrFail($id));
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
        //getting the order using id
        $order = Order::find($id);

        //getting all the items in that order
        $items = $order->item;

        //changing the order information
        if ($request->has('firstname')) {
            $order->customer->update(['firstname' => $request->firstname]);
        }
        if ($request->has('lastname')) {
            $order->customer->update(['lastname' => $request->lastname]);
        }
        if ($request->has('email')) {
            $order->customer->update(['email' => $request->email]);
        }
        if ($request->has('phonenumber')) {
            $order->customer->update(['phonenumber' => $request->phonenumber]);
        }
        if ($request->has('address')) {
            $order->customer->update(['address' => $request->address]);
        }
        if ($request->has('city')) {
            $order->customer->update(['city' => $request->city]);
        }
        if ($request->has('zipcode')) {
            $order->customer->update(['zipcode' => $request->zipcode]);
        }
        if ($request->has('deliveryinstruction')) {
            $order->customer->update(['deliveryinstruction' => $request->deliveryinstruction]);
        }
        if ($request->has('deliverydate')) {
            $order->customer->update(['deliverydate' => $request->deliverydate]);
        }


        $count = 0;
        //checking to see how many new items added 
        if ($request->has('myItems[]') && $request->has('myQuantity[]')) {
            foreach ($items as $item) {
                if ($request->myItems[$count] != null && $request->myQuantity[$count] != null) {
                    $item->update(['itemname' => $request->myItems[$count]]);
                    $item->update(['itemquantity' => $request->myQuantity[$count]]);
                    $count++;
                } else if ($request->myItems[$count] == null && $request->myQuantity[$count] == null) {
                    $item->delete();
                }
            }
            for ($i = $count; $i < count($request->myItems); $i++) {

                //checking to see if both the item name and item quantity was entered
                if ($request->myItems[$i] != null && $request->myQuantity[$i] != null) {
                    Item::create([
                        'orderid' => $id,
                        'itemname' => $request->myItems[$i],
                        'itemquantity' => $request->myQuantity[$i],
                    ]);
                }
            }
        }
        //adding all items to the order
        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $order =  Order::find($id);
        if ($order != null) {
            $order->delete($id);
            return response()->json(["mesage" => "delete successfully order " . $id]);
        }

        return response()->json(["mesage" => "delete unsuccessfully. Please check your URL/method again"]);
    }
}
