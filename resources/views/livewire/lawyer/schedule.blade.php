<?php

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
        return MeetingSchedule::where('legal_case_id', $this->case->id)->paginate($this->show, pageName: 'staff-active-schedule-page');
    }

}; ?>

<x-partials.sidebar  :back="route('lawyer.case')" :id-detail="$this->case?->id" menu="lawyer-active-case" active="Penanganan Kasus / Jadwal Pertemuan / {{ $this->case?->title }}">
    <x-slot:profile>
        <div class="flex flex-col  dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
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
    <x-table thead="#, Pertemuan, Tentang, Waktu, Status," :action="false" label="Agenda Pertemuan" sub-label="Jadwal pertemuan klien dengan anda">
        <x-slot name="filter">
            <x-filter wire:model.live="show"/>
            <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
        </x-slot>
        <x-slot name="actionHead">
            <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400 mb-2">
                <thead class="text-xs text-zinc-700 uppercase bg-zinc-200 dark:bg-zinc-700 dark:text-zinc-100 ">
                <tr>
                    <th scope="col" colspan="6" class="px-6 py-3 text-center border-b border-zinc-300 dark:border-zinc-500">
                        Informasi Kasus
                    </th>
                </tr>
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No. Kasus
                    </th>
                    <th scope="col" class="px-6 py-3">
                        No. Perkara
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Jenis
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tanggal Pengajuan
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <flux:tooltip content="{{$this->case->number}}">
                            <flux:button variant="subtle" class="dark:bg-zinc-800 dark:hover:bg-zinc-800 !px-0">
                                {{ Str::limit($this->case->number, 12, '...') }}
                            </flux:button>
                        </flux:tooltip>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <flux:tooltip content="{{$this->case->case_number}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800 !px-0">{{ $this->case->case_number ? Str::limit($this->case->case_number, 20, '...') : '-' }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <td class="px-6 py-4">
                        {{ $this->case->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $this->case->type == 'civil' ? 'Perdata' : 'Pidana' }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $this->case->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$this->case->status"/>
                    </td>
                </tr>
                </tbody>
            </table>
        </x-slot>
        @if($this->schedules->count())
            @foreach($this->schedules as $schedule)
                <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <td class="px-6 py-4 text-nowrap">
                        Pertemuan {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->about }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ Carbon::parse($schedule->date_time)->isoFormat('dddd, D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->status == 'pending' ? 'Belum Terlaksana' : ($schedule->status == 'finished' ? 'Terlaksana' : 'Dibatalkan') }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:dropdown position="bottom" align="center">
                            <flux:button size="sm" icon:trailing="chevron-down" variant="filled">Menu</flux:button>
                            <flux:menu>
                                <flux:menu.group heading="Kasus">
                                    <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="file-plus-2"
                                                    href="{{ route('lawyer.case-detail.page', ['id' => $schedule->id, 'status' => 'meeting-result']) }}" wire:navigate>
                                        Hasil Pertemuan
                                    </flux:menu.item>
                                    <flux:menu.separator/>
                                    <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="photo"
                                                    href="{{ route('lawyer.case-detail.page', ['id' => $schedule->id, 'status' => 'meeting-documentation']) }}" wire:navigate>
                                       Dokumentasi
                                    </flux:menu.item>
                                </flux:menu.group>
                            </flux:menu>
                        </flux:dropdown>
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
