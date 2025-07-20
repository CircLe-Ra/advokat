<?php

use App\Models\LegalCase;
use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, Url, Title};
use Carbon\Carbon;

new class extends Component {

}; ?>

<div class="space-y-2">
    <x-partials.breadcrumbs active="Jadwal Pengacara"/>
    <livewire:calendar url="/api/lawyer-schedule/{{ auth()->user()->lawyer->id }}" />
</div>
