<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BailiffsPapersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'bailiffs_pen_en' => $this->bailiffs_pen_en,
            'bailiffs_pen_ar' => $this->bailiffs_pen_ar,
            "user_name" => $this->user->name  ?? '',
            'delivery_time' => $this->delivery_time,
            'session_time' => $this->session_time,
            'status' => $this->status,
            'employee_name' => $this->employee->name ?? "" ,
            'permission' => $this->permission,
            'announcment_time' => $this->announcment_time,
            'bailiff_reply' => $this->bailiff_reply,
            'bailiffs_num' => $this->bailiffs_num
        ];
    }
}
