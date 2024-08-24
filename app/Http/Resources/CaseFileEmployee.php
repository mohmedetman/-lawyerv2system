<?php

namespace App\Http\Resources;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseFileEmployee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer_id'=>$this->customer_id,
            'court_ar' => $this->court_ar,
            'permission' => $this->permission,
            'status' => $this->status,
            'create_by_me' => $this->model_type == "Employee" ? 1 : 0,
            'lawyer_name' => [
                'en' =>$this->lawyer->first()->name_en ,
                'ar' => $this->lawyer->first()->name_ar
            ],
            'employee_name' => Employee::where('id',$this->user_id)->first()->name ?? "me"  ,
        ];
    }
}
