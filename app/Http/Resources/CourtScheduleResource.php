<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtScheduleResource extends JsonResource
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
            'title' => $this->agenda,
            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $this->date . ' ' . $this->time)->toIso8601String(),
            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $this->date . ' ' . $this->time)->toIso8601String(),
            'place' => $this->place,
            'reason_for_postponement' => $this->reason_for_postponement
        ];
    }
}
