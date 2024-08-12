<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
            'title_en' => $this->title_en,
            'image' => asset('images/services').'/'. $this->image,
            'description_en' => $this->description_en,
            'title_ar' => $this->title_ar,
            'description_ar' => $this->description_ar,
//            'created_at' => $this->created_at->toIso8601String(),
//            'updated_at' => $this->updated_at->toIso8601String(),
//            'lawyer_id' => $this->lawyer_id,
            ];
    }
}
