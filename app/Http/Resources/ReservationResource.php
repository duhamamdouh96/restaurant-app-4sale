<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'date' => Carbon::parse($this->from_date_time)->format('Y-m-d'),
            'from' => Carbon::parse($this->from_date_time)->format('h:i a'),
            'to' => Carbon::parse($this->to_date_time)->format('h:i a'),
            'guests_count' => $this->guests_count,
            'table' => (new TableResource($this->table))
        ];
    }
}
