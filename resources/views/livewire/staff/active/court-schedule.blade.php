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

}; ?>

<x-partials.sidebar :id-detail="$this->case?->id" menu="staff-active-case"
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
            <flux:button variant="primary" size="sm" icon="plus" icon:variant="micro" class="cursor-pointer">
                Jadwalkan
            </flux:button>
        </flux:modal.trigger>
    </x-slot:action>
    <x-table thead="#, Pertemuan, Agenda, Tanggal Sidang, Jam, Ditunda?," :action="false" label="Jadwal Sidang"
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
                    <td class="px-6 py-4 text-nowrap">
                        Pertemuan {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->agenda }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ Carbon::parse($schedule->date)->isoFormat('dddd, D MMMM Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ Carbon::parse($schedule->time)->isoFormat('HH:mm') }}
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
                <flux:heading size="lg" level="1">Jadwal Pertemuan</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mt-2">
                    Jadwalkan agenda pertemuan klien dengan pengacara.
                </flux:text>
            </div>
            <form wire:submit="store" class="space-y-6">
                <flux:input label="Pembahasan" wire:model="about"/>
                <flux:input label="Waktu Pertemuan" wire:model="date_time" type="datetime-local"/>

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
