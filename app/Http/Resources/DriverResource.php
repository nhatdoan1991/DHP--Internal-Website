<?php

namespace App\Http\Resources;

use App\Models\GroupOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->firstname . " " . $this->lastname,
            'email' => $this->email,
            'phone' => $this->phonenumber,
            'Deliveries' => GroupOrderResource::collection(GroupOrder::where('refuserid', $this->id)->get()),
        ];
    }
}
