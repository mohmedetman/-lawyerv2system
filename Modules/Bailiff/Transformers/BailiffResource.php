<?php

namespace Modules\Bailiff\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BailiffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ,
            'name'=>$this->name ,
            'email'=>$this->email
        ];
    }
}
