<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Entities\LawyerCustomer;

class CustomerResource extends JsonResource
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
            'code' => $this->code,
            'name_en' => $this->name_en??'',
            'name_ar' => $this->name_ar??'',
            'personal_id' => $this->personal_id,
            'address' => $this->address,
            'gender' => $this->gender,
            'litigationDegree_en' => $this->litigationDegree_en,
            'litigationDegree_ar' => $this->litigationDegree_ar,
            'email' => $this->email,

            'customer_phones' => CustomerPhoneResource::collection($this->phones),
            'customer_addresses' => CustomerAddressResource::collection($this->addresses),
            'lawyers' => LawyerResource::collection($this->lawyers),  // Corrected line

        ];
    }
}
