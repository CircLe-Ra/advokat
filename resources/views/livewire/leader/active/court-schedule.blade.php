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

<x-partials.sidebar :back="route('leader.active.case')" :id-detail="$this->case?->id" menu="leader-active-case"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->case?->title }}">
    <x-slot:information>
        <div class="w-[250px] text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-zinc-900 dark:border-zinc-600 dark:text-white">
            <button type="button" class="justify-center text-xl font-semibold relative inline-flex items-center w-full px-4 py-2 text-sm font-medium border-b border-gray-200 rounded-t-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                Informasi Kasus
            </button>
            <button type="button" class="relative flex items-center w-full px-4 py-2 text-sm font-medium border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="font-semibold block">Klien :&nbsp;</span> {{ $this->case?->client?->user->name }}
            </button>
            <button type="button" class="relative inline-flex items-center w-full px-4 py-2 text-sm font-medium border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="font-semibold">Pengacara :&nbsp;</span> {{ $this->case?->lawyer?->user->name }}
            </button>
            <button type="button" class="relative inline-flex items-center w-full px-4 py-2 text-sm font-medium border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="font-semibold">Kasus:</span> Penggelapan
            </button>
            <button type="button" class="relative inline-flex items-center w-full px-4 py-2 text-sm font-medium border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="font-semibold">Jenis Kasus:</span> Pidana
            </button>
            <button type="button" class="relative inline-flex items-center w-full px-4 py-2 text-sm font-medium border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="font-semibold">Nomor Kasus:</span> 12345
            </button>
            <button type="button" class="relative inline-flex items-center w-full px-4 py-2 text-sm font-medium rounded-b-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-500 dark:focus:text-white">
                <span class="font-semibold">Nomor Perkara:</span> 98765
            </button>
        </div>

    </x-slot:information>
    <x-table thead="#, Agenda, Tanggal Sidang, Jam, Tempat, Ditunda?" label="Jadwal Sidang"
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
                        <flux:button icon:variant="micro" size="sm" icon:trailing="arrow-up-right"
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
