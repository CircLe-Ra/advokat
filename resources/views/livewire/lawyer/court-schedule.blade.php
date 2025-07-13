<?php

use App\Models\CourtSchedule;
use App\Models\LegalCase;
use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, Url, Title};
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;
    use WithPagination;

    #[Url(history: true, keep: true)]
    public $show = 5;
    #[Url(history: true, keep: true)]
    public string $search = '';

    public $case;

    public function mount(LegalCase $case): void
    {
        $this->case = $case;
    }

    #[Computed]
    public function schedules()
    {
        return CourtSchedule::where('legal_case_id', $this->case->id)->paginate($this->show, pageName: 'lawyer-active-court-schedule-page');
    }

}; ?>

<x-partials.sidebar :id-detail="$this->case?->id" menu="lawyer-active-case"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->case?->title }}">
    <x-slot:profile>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">KLIEN</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->case->client->user->name"
                                 :initials="$this->case->client->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->case->client->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->case->client->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    <x-table thead="#, Agenda, Tanggal Sidang, Jam, Tempat, Ditunda?," :action="false" label="Jadwal Sidang"
             sub-label="Jadwal sidang pengadilan">
        <x-slot name="filter">
            <x-filter wire:model.live="show"/>
            <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
        </x-slot>
        @if($this->schedules->count())
            @foreach($this->schedules as $schedule)
                <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $schedule->agenda }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ Carbon::parse($schedule->date)->isoFormat('dddd, D MMMM Y') }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ Carbon::parse($schedule->time)->isoFormat('HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->place }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->reason_for_postponement ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:button icon:variant="micro" size="sm" icon:trailing="arrow-up-right" icon="file-plus-2"
                                     href="{{ route('lawyer.case-detail.page', ['id' => $schedule->id, 'status' => 'court-result']) }}"
                                     wire:navigate>
                            Hasil Sidang
                        </flux:button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                <td colspan="7" class="px-6 py-4 text-center">
                    Tidak ada data
                </td>
            </tr>
        @endif
    </x-table>
</x-partials.sidebar>
