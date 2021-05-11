<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Customer;

class OrderResource extends JsonResource
{
    public static $wrap = 'Order';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->status == 1) {
            $status = 'In List';
        } elseif ($this->status == 2) {
            $status = 'In Queue';
        } elseif ($this->status == 3) {
            $status = 'In Delivering';
        } elseif ($this->status == 4) {
            $status = 'Completed';
        } else {
            $status = '';
        }
        if ($this->deliverystatus == 1) {
            $deliverystatus = 'In Kitchen';
        } elseif ($this->deliverystatus == 2) {
            $deliverystatus = 'In Delivering';
        } elseif ($this->deliverystatus == 3) {
            $deliverystatus = 'Unfulfillied';
        } elseif ($this->deliverystatus == 4) {
            $deliverystatus = 'Completed';
        } else {
            $deliverystatus = '';
        }
        return [
            'order_id' => $this->id,
            'order_status' =>  $status,
            'delivery_status' => $deliverystatus,
            'grouporder_id' => $this->grouporderid,
            'index' => $this->index,
            'delivery_instruction' => $this->deliveryinstruction,
            'item' =>  ItemResource::collection($this->item),
            'customer' => CustomerResource::collection(Customer::where('id', $this->customerid)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
