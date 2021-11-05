<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'paid' => $this->paid,
            'status' => $this->status,
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'due_date' => $this->due_date,
            // 'image' => $this->image,
            'reader_id' => $this->reader_id,
            'reader_name' => $this->reader_name,
            'reading_date' => $this->reading_date
        ];
    }
}
