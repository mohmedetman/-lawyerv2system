<?php

namespace Modules\Case\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class JudicialAgendas extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'=>$this->id,
            'next_agenda_date'=>$this->next_agenda_date,
            'previous_agenda_date'=>$this->previous_agenda_date,
            'case_court' => ['en'=>'court_en','ar'=>'court_ar'],
            'actions'=>$this->actions??'',
            'customer' => ['name_en'=>$this->customer_name_en,'name_ar'=>$this->customer_name_ar,'phone'=>$this->phone_number,],
            'case_type' => ['en' => $this->case_type_name_en, 'ar'=>$this->case_type_name_ar],
            'case_degree_name' => ['en' => $this->case_degree_name_en, 'ar'=>$this->case_degree_name_ar],



        ];
    }
}
