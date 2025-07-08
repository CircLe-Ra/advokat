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

    public ?int $id = null;
    public string $about = '';
    public $date_time;

    public $case;

    public function mount($id): void
    {
        $case = LegalCase::find($id);
        $this->case = $case;
    }

    #[Computed]
    public function schedules()
    {
        return MeetingSchedule::where('legal_case_id', $this->case->id)->paginate($this->show, pageName: 'staff-active-schedule-page');
    }

    public function __reset(): void
    {
        $this->reset(['id','about', 'date_time']);
        $this->resetValidation(['about', 'date_time']);
    }

    public function store(): void
    {
        $this->validate([
            'about' => 'required',
            'date_time' => 'required',
        ]);

        try {
            MeetingSchedule::updateOrCreate(
                ['id' => $this->id],
                [
                    'legal_case_id' => $this->case->id,
                    'about' => $this->about,
                    'date_time' => $this->date_time,
                ]);
            $this->__reset();
            unset($this->schedules);
            $this->dispatch('toast', message: 'Jadwal berhasil disimpan');
            Flux::modal('modal-shcedule')->close();
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function edit(MeetingSchedule $schedule): void
    {
        $this->id = $schedule->id;
        $this->about = $schedule->about;
        $this->date_time = $schedule->date_time;
        Flux::modal('modal-shcedule')->show();
    }

    public function delete(MeetingSchedule $schedule): void
    {
        try {
            $schedule->delete();
            $this->dispatch('toast', message: 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

}; ?>

<x-partials.sidebar :back="route('client.case')" position="right" :id-detail="$this->case?->id" menu="staff-active-case" active="Kasus / Jadwal Pertemuan / {{ $this->case?->title }}">
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
    <x-table thead="#, Pertemuan, Tentang, Waktu, Status," :action="false" label="Agenda Pertemuan" sub-label="Jadwal pertemuan dengan pengacara">
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
                        {{ $schedule->about }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ Carbon::parse($schedule->date_time)->isoFormat('dddd, D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        {{ $schedule->status == 'pending' ? 'Belum Terlaksana' : ($schedule->status == 'finished' ? 'Terlaksana' : 'Dibatalkan') }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:dropdown>
                            <flux:button size="sm" icon:trailing="chevron-down" variant="filled">Aksi</flux:button>
                            <flux:menu>
                                <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="file-plus-2"
                                                href="{{ route('staff.active.page', ['id' => $schedule->id, 'status' => 'meeting-result']) }}" wire:navigate>
                                    Hasil Pertemuan
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="photo"
                                                href="{{ route('staff.active.page', ['id' => $schedule->id, 'status' => 'meeting-documentation']) }}" wire:navigate>
                                   Dokumentasi
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" icon="pencil" variant="warning" wire:click="edit({{ $schedule->id }})" :disabled="$schedule->status != 'pending'">
                                    Ubah Jadwal
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" variant="danger" icon="trash" wire:click="delete({{ $schedule->id }})" wire:confirm="Anda yakin ingin menghapus jadwal ini?" :disabled="$schedule->status != 'pending'">
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
                <flux:input label="Pembahasan" wire:model="about" />
                <flux:input label="Waktu Pertemuan" wire:model="date_time" type="datetime-local" />

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
