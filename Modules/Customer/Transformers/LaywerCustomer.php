<?php

namespace Modules\Customer\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class LaywerCustomer extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
          'name_en' => $this->name_en,
          'name_ar'=>$this->name_ar,
          'department'=>$this->department->name
        ];
    }
}
