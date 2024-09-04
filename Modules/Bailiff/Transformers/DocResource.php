<?php

namespace Modules\Bailiff\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DocResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' =>$this->id ,
             'document_type'=>$this->document_type,
            'document_file' => asset('uploads/document').'/'. $this->document_file
        ];
    }
}
