<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'customer_name' => $this->firstname . " " . $this->lastname,
            'customer_email' => $this->email,
            'customer_phone' => $this->phonenumber,
            'customer_address' => $this->address . " " . $this->city . "," . $this->zipcode . "," . $this->state,
        ];
    }
}
