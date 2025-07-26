<?php

use App\Models\LegalCase;
use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, Url, Title};
use Carbon\Carbon;

new class extends Component {

    public function mount(): void
    {

    }
}; ?>

<div class="space-y-2">
    <x-partials.breadcrumbs active="Jadwal Konsultasi & Sidang Pengadilan"/>
    <livewire:calendar url="/api/client-schedule/{{ auth()->user()->client->id ?? 0 }}" />
</div>
