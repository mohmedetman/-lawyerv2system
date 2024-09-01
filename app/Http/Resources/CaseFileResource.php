<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Lawyer\Transformers\CaseDegreeResource;
use Modules\Lawyer\Transformers\CaseTypeResource;
use Modules\Lawyer\Transformers\EmployeeResource;

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
            'id' => $this->id,
//            'created_by' => $this->created_by,
            'court_en' => $this->court_en,
            'court_ar' => $this->court_ar,
            'customer_id' => $this->customer_id,
            'lawyer_id' => $this->lawyer_id,
//            'case_type_id' => $this->case_type_id,
//            'case_degree_id' => $this->case_degree_id,
//            'model_type' => $this->model_type,
            'permission' => $this->permission,
//            'actions' => $this->actions,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'case_degree' => new CaseDegreeResource($this->whenLoaded('caseDegree')),
            'case_type' => new CaseTypeResource($this->whenLoaded('caseType')),
            'employee' => EmployeeResource::collection($this->whenLoaded('employee')),

         ];
    }
}
