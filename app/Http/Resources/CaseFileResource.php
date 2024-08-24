<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id ,
            'customer_id'=>$this->customer_id,
            'court_en' =>$this->court_en ,
              'permission' => $this->permission,
            'status' => $this->status,
            'create_by_me' => $this->model_type == "Lawyer" ? 1 : 0,
            'lawyer_name' => [
                'en' =>$this->lawyer->first()->name_en ,
                'ar' => $this->lawyer->first()->name_ar
            ],
            "user_name" =>$this->user->name ?? "" ,
            'employee_name' => $this->employee->name ?? "me" ,

         ];
    }
}
