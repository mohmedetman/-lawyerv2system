<?php

namespace Modules\Bailiff\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            "action_taken" =>$this->action_taken ,
            "result" =>$this->result,
            "action_date"=>$this->action_date,
            "document" => new  DocResource($this->document),
            'bailiff'=> new  BailiffResource($this->bailiff)
        ];
    }
}
