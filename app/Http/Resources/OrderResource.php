<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'reservation' => ReservationResource::make($this->reservation),
            'details' => OrderDetailRessource::collection($this->details),
            'total' => $this->total,
            'date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'paid_at' => $this->paid_at
        ];
    }
}
