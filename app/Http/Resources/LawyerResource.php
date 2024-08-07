<?php

namespace App\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sub = Subscription::all();
        return [
            'id'=>$this->id,
            'name_en' =>$this->name_ne,
            'name_ar'=>$this->name_ar,
            'email'=>$this->email,
//            'subscription'=> $sub->where('id',$this->id)->first() ? true : false,
            'phone_number'=>$this->phone_number
        ];
    }
}
