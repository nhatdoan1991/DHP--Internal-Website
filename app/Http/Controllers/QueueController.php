<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GroupOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
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
    public function index()
    {
        // Select all order with status is queue join with customer and item
        $order = Order::with('item', 'customer')->where('status', 2)->orderBy('grouporderid', 'asc')->orderBy('index', 'asc')->get();
        // Collection hold an array of all grouporderid that in queue
        $subset = $order->pluck('grouporderid');
        // Select all grouporder that do not have any order or have queue orders
        //$grouporder = GroupOrder::wheredoesntHave('order')->orwhereIn('id',$subset)->get();
        // Select all driver to assist delivery
        $driver = User::where('role', 'driver')->get();
        //Right join groupoder and order by grouporderid to get all the group with no order or with queue order
        $grouporder = Order::rightjoin('grouporder', 'grouporder.id', '=', 'order.grouporderid')
            ->whereNull('order.grouporderid')
            ->orwhereIn('order.grouporderid', $subset)
            ->groupBy('groupname')
            ->get();
        // return view + data
        //dd($grouporder);
        return view('queue.queue', [
            'orders' => $order,
            'grouporders' => $grouporder,
            'drivers' => $driver,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $orders = Order::where('status', 2)->get();
        foreach ($orders as $order) {
            if ($request->has('orderid' . (string)$order->id)) {
                if ($request->input(['orderid' . (string)$order->id]) == 'null') {
                    $order->update(['grouporderid' => NULL]);
                } else {
                    $order->update(['grouporderid' => $request->input(['orderid' . (string)$order->id])]);
                }
                $order->save();
            }
            if ($request->has(['indexof' . (string)$order->id])) {
                $order->update(['index' => $request->input(['indexof' . (string)$order->id])]);
            }
        };
        return redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Drag orders Succesfully</div>');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        //dd($order); 
        $orders = Order::where('grouporderid', $order->grouporderid)->get();
        if ($order->grouporderid != null) {
            foreach ($orders as $o) {
                if ($o->index > $order->index) {
                    $o->update(['index' => $o->index - 1]);
                }
            }
        }

        $order->update(['status' => 1]);
        $order->update(['grouporderid' => NULL]);
        $order->update(['index' => 0]);
        return  redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Pop to List Successfully</div>');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grouporder = GroupOrder::find($id);
        $grouporder->delete();
        return redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Delete a group successfully</div>');
    }

    public function addgroup(Request $request)
    {
        $exirtgroup = GroupOrder::where('groupname', $request->groupname)->get();
        if ($exirtgroup->count()) {
            return redirect('queue')->with('message', '<div class ="alert alert-danger alert-dhp">This name is already exist</div>');
        } else {
            $groupName = $request->groupname;
            if ($groupName == null) {
                $groupName = "Empty";
            }
            GroupOrder::create([
                'groupname' => $groupName
            ]);
            $newgroup = Grouporder::where('groupname', 'Empty')->get();
            foreach ($newgroup as $group) {
                $group->update(['groupname' => "Empty $group->id"]);
            }
            return redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Create a group successfully</div>');
        }
    }

    public function deletegroup($id)
    {
        $grouporder = GroupOrder::find($id);
        $grouporder->delete();
        return redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Delete a group successfully</div>');
    }
 

    public function assignGroup(Request $request)
    {
        //dd($request);
        $input = $request->all();
        $grouporders = GroupOrder::get();
        foreach ($grouporders as $group) {
            if ($request->has('groupid' . ((string)$group->id))) {
                if ($input['driver' . (string)$group->id] != null) {
                    if ($input['calendar' . (string)$group->id]) {
                        $group->update(['refuserid' => $input['driver' . (string)$group->id]]);
                        $group->update(['deliverydate' => $input['calendar' . (string)$group->id]]);
                        $group->save();
                    } else {
                        return redirect('queue')->with('message', '<div class ="alert alert-danger alert-dhp">Missing information when assigning groups</div>');
                    }
                } else {
                    return redirect('queue')->with('message', '<div class ="alert alert-danger alert-dhp">Missing information when assigning groups</div>');
                }
            }
        }
        return redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Assign groups Successfully</div>');
    }

    public function pushDeliver()
    {
        //get all order belongs to a group in queue to push to delivery
        $order = Order::where('status', 2)->whereNotNull('grouporderid')->get();
        //get a subset hold all group id in queue
        $subset = $order->pluck('grouporderid');
        $grouporders = GroupOrder::whereIn('id', $subset)->get();
        foreach ($grouporders as $group) {
            if ($group->deliverydate == null || $group->refuserid == null) {
                return redirect('queue')->with('message', '<div class ="alert alert-danger alert-dhp">At least a group is not assigned to a driver or delivery date</div>');
            }
        }
        //dd($order);
        foreach ($order as $order) {
            $order->update(['status' => 3]);
            $order->update(['deliverystatus' => 1]);
            $order->save();
        }
        return redirect('queue')->with('message', '<div class ="alert alert-success alert-dhp">Create a group successfully</div>');
    }
}
