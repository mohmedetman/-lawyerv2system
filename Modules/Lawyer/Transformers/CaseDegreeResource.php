<?php

namespace Modules\Lawyer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseDegreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'name_en' =>$this->name_en,
            'name_ar' =>$this->name_ar,

        ];
    }
}
