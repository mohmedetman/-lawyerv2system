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
            'court_en' =>$this->court_en ,
            'court_ar' => $this->court_ar ,
            'user_status_en'=>$this->user_status_en,
            'enemy_status_ar'=>$this->enemy_status_ar ,
            'last_session_en'=>$this->last_session_en,
            'decision_en'=>$this->decision_en,
            'user_status_ar' => $this->user_status_ar,
            'last_session_ar' => $this->last_session_ar,
            'decision_ar' => $this->decision_ar,
            'permission' => $this->permission,
            'status' => $this->status,
            'create_by_me' => $this->model_type == "Lawyer" ? 1 : 0,
            'lawyer_name' => [
                'en' =>$this->lawyer->first()->name_en ,
                'ar' => $this->lawyer->first()->name_ar
            ],
            'employee_name' => $this->employee->name ,

         ];
    }
}
