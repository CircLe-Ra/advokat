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

    public ?int $id = null;
    public string $agenda = '';
    public $date;
    public $time;
    public string $place = '';
    public string $reason_for_postponement = '';
    public string $case_number = '';

    public $case;

    public function mount($id): void
    {
        $case = LegalCase::find($id);
        $this->case = $case;
    }

    #[Computed]
    public function schedules()
    {
        return CourtSchedule::where('legal_case_id', $this->case->id)->paginate($this->show, pageName: 'staff-active-court-schedule-page');
    }

    public function __reset(): void
    {
        $this->reset(['id', 'agenda', 'date', 'time', 'place', 'reason_for_postponement']);
        $this->resetValidation(['agenda', 'date', 'time', 'place', 'reason_for_postponement']);
    }

    public function store(): void
    {
        $this->validate([
            'date' => ['required'],
            'time' => ['required'],
            'agenda' => ['required'],
            'place' => ['required'],
            'reason_for_postponement' => ['nullable'],
        ]);

        try {
            CourtSchedule::updateOrCreate(
                ['id' => $this->id],
                [
                    'legal_case_id' => $this->case->id,
                    'date' => $this->date,
                    'time' => $this->time,
                    'agenda' => $this->agenda,
                    'place' => $this->place,
                    'reason_for_postponement' => $this->reason_for_postponement
                ]);
            $this->__reset();
            unset($this->schedules);
            $this->dispatch('toast', message: 'Jadwal berhasil disimpan');
            Flux::modal('modal-shcedule')->close();
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function edit(CourtSchedule $schedule): void
    {
        $this->id = $schedule->id;
        $this->agenda = $schedule->agenda;
        $this->date = $schedule->date;
        $this->time = $schedule->time;
        $this->place = $schedule->place;
        $this->reason_for_postponement = $schedule->reason_for_postponement;
        Flux::modal('modal-shcedule')->show();
    }

    public function delete(CourtSchedule $schedule): void
    {
        try {
            $schedule->delete();
            $this->dispatch('toast', message: 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function save()
    {
        $this->validate([
            'case_number' => ['required'],
        ]);
        try {
            $this->case->update([
                'case_number' => $this->case_number,
            ]);
            Flux::modal('modal-case-number')->close();
            $this->dispatch('toast', message: 'Nomor Kasus berhasil disimpan');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

}; ?>

<x-partials.sidebar :back="route('staff.active.case')" :id-detail="$this->case?->id" menu="staff-active-case"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->case?->title }}">
    <x-slot:profile>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">PENGACARA</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->case->lawyer->user->name"
                                 :initials="$this->case->lawyer->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->case->lawyer->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->case->lawyer->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>

    <x-slot:action>
        <flux:modal.trigger name="modal-shcedule">
            <flux:button :disabled="!$this->case->case_number" variant="primary" size="sm" icon="plus" icon:variant="micro" class="cursor-pointer disabled:cursor-not-allowed">
                Jadwalkan
            </flux:button>
        </flux:modal.trigger>
    </x-slot:action>
    <div class="w-full mt-2">
        <flux:callout icon="information-circle" variant="secondary" class="bg-zinc-50 border dark:border-zinc-700 dark:bg-zinc-900" inline>
            @if($this->case->case_number)
                <flux:callout.heading>Anda dapat mengubah No. Perkara</flux:callout.heading>
            @else
                <flux:callout.heading>No. Perkara belum ditetapkan, Mohon agar ditetapkan terlebih dahulu agar dapat menjadwalkan sidang.</flux:callout.heading>
            @endif
                <x-slot name="actions">
                <flux:modal.trigger name="modal-case-number">
                    <flux:button icon:trailing="arrow-up-right" class="cursor-pointer">No. Perkara</flux:button>
                </flux:modal.trigger>
            </x-slot>
        </flux:callout>
    </div>
    <x-table thead="#, Agenda, Tanggal Sidang, Jam, Tempat, Ditunda?," :action="false" label="Jadwal Sidang"
             sub-label="Jadwal sidang pengadilan">
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
                        <flux:dropdown>
                            <flux:button size="sm" icon:trailing="chevron-down" variant="filled">Aksi</flux:button>
                            <flux:menu>
                                <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="file-plus-2"
                                                href="{{ route('staff.active.page', ['id' => $schedule->id, 'status' => 'court-result']) }}"
                                                wire:navigate>
                                    Hasil Sidang
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" icon="pencil" variant="warning"
                                                wire:click="edit({{ $schedule->id }})"
                                                :disabled="$schedule->status != 'pending'">
                                    Ubah Jadwal
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" variant="danger" icon="trash"
                                                wire:click="delete({{ $schedule->id }})"
                                                wire:confirm="Anda yakin ingin menghapus jadwal ini?"
                                                :disabled="$schedule->status != 'pending'">
                                    Hapus Jadwal
                                </flux:menu.item>
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
    <flux:modal name="modal-shcedule" class="md:w-96">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Jadwal Sidang</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2">
                    Jadwal sidang pengadilan yang akan dilaksanakan.
                </flux:text>
            </div>
            <form wire:submit="store" class="space-y-6">
                <flux:input label="Agenda" wire:model="agenda"/>
                <flux:input label="Tanggal Sidang" wire:model="date" type="date"/>
                <flux:input label="Jam" wire:model="time" type="time"/>
                <flux:input label="Tempat" wire:model="place"/>
                <div class="flex gap-2 justify-end">
                    <flux:modal.close>
                        <flux:button variant="filled">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    <flux:modal name="modal-case-number" class="md:w-96">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">No. Perkara</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2">
                    Masukan Nomor Perkara yang dikeluarkan oleh pengadilan.
                </flux:text>
            </div>
            <form wire:submit="save" class="space-y-6">
                <flux:input label="Nomor Perkara" wire:model="case_number"/>
                <div class="flex gap-2 justify-end">
                    <flux:modal.close>
                        <flux:button variant="filled">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</x-partials.sidebar>
