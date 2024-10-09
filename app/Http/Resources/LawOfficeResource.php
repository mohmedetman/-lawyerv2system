<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawOfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
           'name' => $this->name ,
           'description' => $this->description ,
            'history'=> $this->history ,
             'specializations' =>explode(',',$this->specializations) ,
             'phones' => explode(',',$this->phones)
        ];
    }
}
