<?php

use App\Models\CourtResult;
use App\Models\CourtSchedule;
use App\Models\MeetingSchedule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{Computed, On, Url, Title};
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public ?int $id = null;
    public string $reason_for_postponement = '';
    public $files = [];
    public $file;

    public $court;

    public function mount($id): void
    {
        $court = CourtSchedule::find($id);
        $this->court = $court;
    }

    #[Computed]
    public function courtResults()
    {
        return CourtResult::where('court_schedule_id', $this->court->id)->get();
    }

    public function __reset(): void
    {
        $this->reset(['reason_for_postponement', 'files']);
        $this->resetValidation(['reason_for_postponement', 'files']);
    }

    public function cancel(): void
    {
        $this->validate([
            'reason_for_postponement' => 'required'
        ]);
        $this->court->update([
            'reason_for_postponement' => $this->reason_for_postponement,
            'status' => 'cancelled'
        ]);
        $this->dispatch('toast', message: 'Pertemuan dibatalkan');
        $this->redirectIntended(route('staff.active.page', ['id' => $this->court->legal_case_id, 'status' => 'court-schedule'], absolute: false), navigate: true);
    }

    public function store(): void
    {
        $this->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'extensions:pdf,doc,docx', 'max:2048'],
        ]);
        try {
            $this->court->update([
                'status' => 'finished'
            ]);
            foreach ($this->files as $file) {
                CourtResult::create([
                    'court_schedule_id' => $this->court->id,
                    'file' => $file->store('court-result', 'public'),
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getClientOriginalExtension(),
                ]);
            }
            $this->reset(['files']);
            unset($this->courtResults);
            $this->dispatch('pond-reset');
            $this->dispatch('toast', message: 'Berhasil disimpan');
            Flux::modal('modal-upload')->close();
        } catch (\Exception $e) {
            Flux::modal('modal-upload')->close();
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function delete(CourtResult $result): void
    {
        try {
            Storage::delete($result->file);
            $result->delete();
            $this->dispatch('toast', message: 'Berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

}; ?>

<x-partials.sidebar :back="route('staff.active.page', ['id' => $this->court->legal_case_id, 'status' => 'court-schedule'], absolute: false)" :id-detail="$this->court->legalCase?->id" menu="staff-active-case"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->court->legalCase?->title }} / Hasil Sidang">
    <x-slot:profile>
        <div class="flex flex-col border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">PENGACARA</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->court->legalCase?->lawyer->user->name"
                                 :initials="$this->court->legalCase?->lawyer->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->court->legalCase?->lawyer->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->court->legalCase?->lawyer->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    <x-slot:action>
        <flux:modal.trigger name="modal-cancel">
            <flux:button :disabled="$this->court->status == 'cancelled' || $this->court->status == 'finished'"
                         variant="danger" size="sm" icon="no-symbol" icon:variant="micro"
                         class="cursor-pointer mr-2 disabled:cursor-not-allowed">
                Ditunda
            </flux:button>
        </flux:modal.trigger>
        <flux:modal.trigger name="modal-upload">
            <flux:button :disabled="$this->court->status == 'cancelled'" variant="primary" size="sm"
                         icon="plus" icon:variant="micro" class="cursor-pointer">
                Tambah
            </flux:button>
        </flux:modal.trigger>
    </x-slot:action>
    @if($this->court->status == 'cancelled')
        <flux:callout icon="no-symbol" variant="danger" inline class="mt-2 mb-1">
            <flux:callout.heading>Pertemuan dibatalkan
                pada {{ $this->court->updated_at->isoFormat('dddd, D MMMM YYYY HH:mm') }} WIT
            </flux:callout.heading>
            <x-slot name="actions">
                <flux:button icon:trailing="arrow-right" variant="outline"
                             href="{{ route('staff.active.page', ['id' => $this->court->legal_case_id, 'status' => 'court-schedule']) }}">
                    Kembali
                </flux:button>
            </x-slot>
        </flux:callout>
    @endif

    <x-card :label="__('Hasil Sidang')"
            :sub-label=" __('Hasil Persidangan, Pada :date', ['date' => Carbon::parse($this->court->date)->isoFormat('dddd, D MMMM Y')])"
            main-class="flex-1">
        @if($this->courtResults->count() > 0)
            <div
                class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($this->courtResults as $file)
                    <div class="relative">
                        <a
                            class="border size-44 overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 flex flex-col items-center justify-center text-center p-2" href="{{ asset('storage/' . $file->file) }}" target="_blank"
                            wire:key="image-{{ $file->id }}">
                            {{ $file->name }}

                        </a>
                        <div class="z-10" wire:ignore>
                            <div class="absolute top-0 right-7 p-1" wire:click="delete({{$file->id}})"
                                 wire:confirm="Lanjutkan menghapus?">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                     viewBox="0 0 256 256">
                                    <g fill="#e30000">
                                        <path d="M224 128a96 96 0 1 1-96-96a96 96 0 0 1 96 96"/>
                                        <path fill="#fff"
                                              d="M165.66 101.66L139.31 128l26.35 26.34a8 8 0 0 1-11.32 11.32L128 139.31l-26.34 26.35a8 8 0 0 1-11.32-11.32L116.69 128l-26.35-26.34a8 8 0 0 1 11.32-11.32L128 116.69l26.34-26.35a8 8 0 0 1 11.32 11.32M232 128A104 104 0 1 1 128 24a104.11 104.11 0 0 1 104 104m-16 0a88 88 0 1 0-88 88a88.1 88.1 0 0 0 88-88"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div
                class="min-h-[500px] border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 my-auto flex justify-center items-center">
                <flux:heading size="lg" level="1">Belum ada hasil sidang</flux:heading>
            </div>
        @endif
    </x-card>

    <flux:modal name="modal-upload" class="md:w-lg" @close="__reset">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Unggah Hasil Persindangan</flux:heading>
                <flux:text class="mt-2">Tarik dan lepas foto ke dalam kotak atau cari berkas melalui browser</flux:text>
            </div>
            <form wire:submit.prevent="store">
                <x-filepond label="Hasil Persindangan" wire:model="files" multiple accept="application/pdf, application/doc, application/docx"/>
                <div class="flex gap-2 mt-4">
                    <flux:spacer/>
                    <flux:modal.close>
                        <flux:button variant="ghost">Batal</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary">Unggah</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <flux:modal name="modal-cancel" class="md:w-xl" @close="__reset">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg">Ditunda</flux:heading>
                <flux:text class="mt-2">Persidangan ditunda dengan alasan</flux:text>
            </div>
            <flux:textarea label="Alasan Penundaan" wire:model="reason_for_postponement"/>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="filled" size="sm" class="cursor-pointer">
                        Batal
                    </flux:button>
                </flux:modal.close>
                <flux:button variant="primary" size="sm" class="cursor-pointer"
                             wire:click="cancel">
                    Kirim
                </flux:button>
            </div>
        </div>
    </flux:modal>
</x-partials.sidebar>
