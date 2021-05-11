<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Item;
use DB;
use App\Rules\ArrayAtLeastOneRequired;

class ListController extends Controller
{
    // only authorize user can view this page
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //List page will display all the orders with a status list/1
        //checking to see if a pagination option was selected
        //if not, display 5 orders by default
        $items = $request->items ?? 10;

        $data = Order::where('status', '=', 1)
            ->with('customer')
            ->with('item')
            ->paginate($items);

        $querystringarray = (['items' => $request->items, 'page' => $request->page]);
        $data->appends($querystringarray);
        return view('list.list', ['data' => $data, 'items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //create button will direct user to create page
        return view('list.create');
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
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'zipcode' => 'required|max:255',
            'state' => 'required',
            'phonenumber' => 'required|max:255',
            'deliverydate' => 'required',
            'myItems' => [new ArrayAtLeastOneRequired()],
            'myQuantity' => [new ArrayAtLeastOneRequired()],

        ]);

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

        //returning the user to list, and flash a success notification
        return redirect('list')->with('message', '<div class ="alert alert-success alert-dhp">Order Successfully Created.</div>');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //getting the order that user wants to edit
        $data = Order::where('id', $id)->with('item')->with('customer')->get();
        return view('list.edit', ['data' => $data, 'id' => $id]);
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
        //deleting an item 
        $action = $request->input('action');
        if ($action == 'delete') {
            $order = Order::where('id', $id)->with('item')->first();
            $items = $order->item->first();
            $items->delete();
            return back();
        }

        //validating the information entered
        $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'zipcode' => 'required|max:255',
            'state' => 'required|max:255',
            'phonenumber' => 'required|max:255',
            'deliverydate' => 'required',
            'myItems' => 'required|min:1',
            'myQuantity' => 'required|min:1',
        ]);

        //getting all the information entered through request
        $input = $request->all();

        //getting the order using id
        $order = Order::find($id);

        //getting all the items in that order
        $items = $order->item;

        //changing the order information
        $order->customer->update(['firstname' => $request->firstname]);
        $order->customer->update(['lastname' => $request->lastname]);
        $order->customer->update(['email' => $request->email]);
        $order->customer->update(['phonenumber' => $request->phonenumber]);
        $order->customer->update(['address' => $request->address]);
        $order->customer->update(['city' => $request->city]);
        $order->customer->update(['zipcode' => $request->zipcode]);
        $order->update(['deliveryinstruction' => $request->deliveryinstruction]);
        $order->update(['deliverydate' => $request->deliverydate]);

        $count = 0;
        //checking to see how many new items added 
        foreach ($items as $item) {
            if ($request->myItems[$count] != null && $request->myQuantity[$count] != null) {
                $item->update(['itemname' => $request->myItems[$count]]);
                $item->update(['itemquantity' => $request->myQuantity[$count]]);
                $count++;
            } else if ($request->myItems[$count] == null && $request->myQuantity[$count] == null) {
                $item->delete();
            }
        }

        //adding all items to the order
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
        //flash a success notification that an order has been created
        return redirect()->route('list')->with('message', '<div class ="alert alert-success alert-dhp">Order Successfully Editted.</div>');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { //deleting an order
        //getting the order using id
        $order = Order::findorfail($id);
        //getting the customer id
        $custid = $order->customerid;
        //getting the customer
        $customer = Customer::findorfail($custid);

        //delete order
        $order->delete();
        //delete customer
        $customer->delete();
        return redirect()->route('list')->with('message', '<div class ="alert alert-success alert-dhp">Delete an order successfully</div>');
    }

    public function deleteItem($id)
    { //deleting an item
        //getting the item id
        $items = Item::find($id);
        $items->delete();
        return back();
    }

    public function push(Request $request, $id)
    {
        //pushing an order from list to queue, 
        //changing the status from 1 to 2
        $order = Order::find($id);
        $order->update(['status' => 2]);

        //display a success notification that the order was pushed 
        return redirect('list')->with('message', '<div class ="alert alert-success alert-dhp">Push to Queue Successfully</div>');
    }
}
