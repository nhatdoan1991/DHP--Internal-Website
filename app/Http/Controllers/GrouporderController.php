<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupOrderResource;
use App\Models\GroupOrder;
use Illuminate\Http\Request;

class GrouporderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return GroupOrderResource::collection(GroupOrder::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new GroupOrderResource(GroupOrder::findOrFail($id));
    }
    public function date($date)
    {
        return GroupOrderResource::collection(GroupOrder::where('deliverydate', $date)->get());
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
        $grouporder =  GroupOrder::find($id);
        if ($grouporder != null) {
            $grouporder->delete($id);
            return response()->json(["mesage" => "delete successfully order " . $id]);
        }

        return response()->json(["mesage" => "delete unsuccessfully. Please check your URL/method again"]);
    }
}
