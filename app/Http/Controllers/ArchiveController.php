<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\GroupOrder;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use App\Models\Delivery;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ArchiveController extends Controller
{
    //middleware for authorize access
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    //displaying only orders delivered today by default
    public function index(Request $request)
    {
        $range = (new Carbon())->toDateString();
        $search = $request->get('search') ?? "";
        $items = $request->items ?? 10;
        $days = '';
        $searched = false;
        $groups = GroupOrder::with('order', 'order.customer', 'order.item')->whereHas('order', function ($q) {
            $q->where('status', '=', 4);
        })
            ->where('deliverydate', '>=', $range)
            ->orderBy('id', 'desc')
            ->paginate($items);

        $querystringarray = (['items' => $request->items, 'page' => $request->page]);
        $groups->appends($querystringarray);

        return view('archive.archive', [
            'groups' => $groups,
            'items' => $items,
            'days' => $days,
            'searched' => $searched,
        ]);
    }

    //taking in the number of days to display orders between now and that date
    public function timeframe(Request $request, $days)
    {
        // dd($Request);

        if ($days <= 365)
            $range = Carbon::now()->subDay($days)->toDateString();
        else
            $range = 0;
        $searched = false;
        $items = $request->items ?? 10;


        $groups = GroupOrder::with('order', 'order.customer', 'order.item')->whereHas('order', function ($q) {
            $q->where('status', '=', 4);
        })
            ->where('deliverydate', '>=', $range)
            ->orderBy('id', 'desc')
            ->paginate($items);


        $querystringarray = (['days' => $days, 'items' => $request->items, 'page' => $request->page]);
        $groups->appends($querystringarray);
        return view('archive.archive', [
            'groups' => $groups,
            'items' => $items,
            'days' => $days,
            'searched' => $searched,
        ]);
    }

    public function search(Request $request)
    {
        $range = (new Carbon())->toDateString();
        $terms = explode(" ", $request->get('search'));
        $days = '';
        $searched = true;


        $items = $request->items ?? 10;
        /*
        $groups = GroupOrder::with('order', 'order.customer', 'order.item', )
                            ->where('deliverydate', '>=', 0)
                            
                            ->whereHas('order', function($q) {
                                $q->where('status','=', 4);
                            })
                            //search through the grouporder table
                            ->where(function($q) use($terms) {
                                foreach($terms as $term)
                                $q->where('id', 'LIKE', '%'.$term.'%')
                                    ->orWhere('groupname', 'LIKE', '%'.$term.'%')
                                    ->orWhere('deliverydate', 'LIKE', '%'.$term.'%');
                            })
                            //search through the item table
                            ->orWhereHas('order.item',function($q) use($terms) {
                                foreach($terms as $term){
                                    $q->where('orderid', 'LIKE', '%'.$term.'%')
                                    ->orWhere('itemname', 'LIKE', '%'.$term.'%');
                                }
                                
                            })
                            //search through the customer table
                            ->orWhereHas('order.customer',function($q) use($terms) {
                                foreach($terms as $term) {
                                    $q->where('firstname', 'LIKE', '%'.$term.'%')
                                    ->orWhere('lastname', 'LIKE', '%'.$term.'%')
                                    ->orWhere('email', 'LIKE', '%'.$term.'%')
                                    ->orWhere('phonenumber', 'LIKE', '%'.$term.'%')
                                    ->orWhere('zipcode', 'LIKE', '%'.$term.'%')
                                    ->orWhere('state', 'LIKE', '%'.$term.'%')
                                    ->orWhere('city', 'LIKE', '%'.$term.'%');
                                }
                            })
                            //->get();
                            ->paginate($items);
//dd($groups);
*/




        $temp = Order::with('customer', 'item', 'grouporder')

            //look in order table
            ->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where('id', 'LIKE', '%' . $term . '%')
                        ->orWhere('grouporderid', 'LIKE', '%' . $term . '%')
                        ->orWhere('customerid', 'LIKE', '%' . $term . '%');
                }
            })
            //look in the item table
            ->orWhereHas('item', function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where('itemname', 'LIKE', '%' . $term . '%');
                }
            })
            //look in the customer table
            ->orWhereHas('customer', function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where('firstname', 'LIKE', '%' . $term . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $term . '%')
                        ->orWhere('email', 'LIKE', '%' . $term . '%')
                        ->orWhere('phonenumber', 'LIKE', '%' . $term . '%')
                        ->orWhere('address', 'LIKE', '%' . $term . '%')
                        ->orWhere('city', 'LIKE', '%' . $term . '%')
                        ->orWhere('zipcode', 'LIKE', '%' . $term . '%')
                        ->orWhere('state', 'LIKE', '%' . $term . '%');
                }
            })
            //look in the grouporder table
            ->orWhereHas('grouporder', function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where('groupname', 'LIKE', '%' . $term . '%')
                        ->orWhere('deliverydate', 'LIKE', '%' . $term . '%');
                }
            })
            ->paginate($items);
        //->get();
        $temp = $temp->where('status', 4);
        $groups = $temp->groupby('grouporderid');




        return view('archive.archive', [
            'groups' => $groups,
            'items' => $items,
            'days' => $days,
            'searched' => $searched,
        ]);
    }
}
