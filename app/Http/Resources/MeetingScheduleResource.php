<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->about,
            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $this->date_time)->toIso8601String(),
            'end' => Carbon::createFromFormat('Y-m-d H:i:s', $this->date_time)->toIso8601String(),
        ];
    }
}
