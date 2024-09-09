<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Case\Entities\CaseFile;

class JudgmentResource extends JsonResource
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
            'case_id' => $this->case_id,
            'judgment_type' => $this->judgment_type,
            'issued_by' => $this->issued_by,
            'date_issued' => $this->date_issued,
            'details' => $this->details,
            'appeal_possible' => $this->appeal_possible,
            'is_in_absentia' => $this->is_in_absentia,
            'notification_date' => $this->notification_date,
            'default_party' => $this->default_party,
            'appeal_deadline' => $this->appeal_deadline,
            'is_final' => $this->is_final,
            'finalized_on' => $this->finalized_on,
            'execution_status' => $this->execution_status,
            //public/uploads/judgment/1725884618.avif
            'file' => asset('uploads/judgment').'/'.$this->file,
            // Include the related case data
            'case' => new CaseFileResource($this->case),
        ];
    }
}
