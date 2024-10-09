<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JudicialReportResource extends JsonResource
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
            'report_number' => $this->report_number,
            'report_date' => $this->report_date,
            'report_type' => $this->report_type,
            'description' => $this->description,
            'status' => $this->status,
            //public/uploads/judicial-report/1725962737.docx
            'file' => asset('uploads/judicial-report') .'/'. $this->file,
            'archived_at' => $this->archived_at,
        ];
    }
}
