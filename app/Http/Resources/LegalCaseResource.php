<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LegalCaseResource extends JsonResource
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
            'number' => $this->number,
            'case_number' => $this->case_number,
            'case_type' => $this->case_type,
            'title' => $this->title,
            'meeting_schedules' => MeetingScheduleResource::collection($this->meetingSchedules),
            'court_schedules' => CourtScheduleResource::collection($this->courtSchedules),
        ];
    }
}
