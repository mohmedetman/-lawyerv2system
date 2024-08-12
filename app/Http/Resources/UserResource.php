<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lawyer_name' =>
                ['en' => $this->lawyer->name_en ,
                'ar' => $this->lawyer->name_ar],
            'code' => $this->code,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'phone_number' => $this->phone_number,
            'personal_id' => $this->personal_id,
            'address' => $this->address,
            'gender' => $this->gender,
            'user_type' => $this->user_type,
            'litigationDegree_en' => $this->litigationDegree_en,
            'litigationDegree_ar' => $this->litigationDegree_ar,
            'email' => $this->email,
        ];
    }
}
