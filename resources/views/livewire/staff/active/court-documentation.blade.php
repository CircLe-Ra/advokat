<?php

use App\Models\CourtDocumentation;
use App\Models\CourtSchedule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public $court;
    public ?array $files = [];
    public $file;

    public function mount(CourtSchedule $id): void
    {
        $this->court = $id;
    }

    #[Computed]
    public function courtDocumentations()
    {
        return CourtDocumentation::where('court_schedule_id', $this->court->id)->get();
    }

    public function store(): void
    {
        $this->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);
        try {
            foreach ($this->files as $image) {
                CourtDocumentation::create([
                    'court_schedule_id' => $this->court->id,
                    'file' => $image->store('court-documentation', 'public'),
                    'type' => $image->getClientOriginalExtension(),
                ]);
            }
            $this->reset(['files']);
            unset($this->courtDocumentations);
            $this->dispatch('pond-reset');
            $this->dispatch('toast', message: 'Berhasil disimpan');
            Flux::modal('modal-upload-image')->close();
        } catch (\Exception $e) {
            Flux::modal('modal-upload-image')->close();
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function deleteImage($id): void
    {
        try {
            $file = CourtDocumentation::find($id);
            if ($file) {
                Storage::delete($file->file);
                $file->delete();
            }
            unset($this->courtDocumentations);
            $this->dispatch('toast', message: 'Berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function showImage($file): void
    {
        $this->file = CourtDocumentation::where('id', $file)->first()->file;
        Flux::modal('modal-show-image')->show();
    }

}; ?>

<x-partials.sidebar :back="route('staff.active.page', ['id' => $this->court->legal_case_id, 'status' => 'court-schedule'], absolute: false)" :id-detail="$this->court->legalCase?->id" menu="staff-active-case"
                    active="Penanganan Kasus / Jadwal Sidang / {{ $this->court->legalCase?->title }} / Dokumentasi Sidang">
    <x-slot:profile>
        <div class="flex flex-col  dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
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
        <flux:modal.trigger name="modal-upload-image" variant="primary" size="sm" icon="hard-drive-upload"
                            icon:variant="micro" class="cursor-pointer">
            <flux:button variant="primary" size="sm" icon="image-plus" icon:variant="micro" class="cursor-pointer"
                         :disabled="$this->court->status == 'cancelled'">
                Unggah Dokumentasi
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
                             href="{{ route('staff.active.page', ['id' => $this->court->legal_case_id, 'status' => 'schedule']) }}">
                    Kembali
                </flux:button>
            </x-slot>
        </flux:callout>
    @endif
    <x-card :label="__('Dokumentasi Sidang')"
            :sub-label=" __('Dokumentasi sidang pengadilan, Pada :date', ['date' => Carbon::parse($this->court->date_time)->isoFormat('dddd, D MMMM Y')])"
            main-class="flex-1">
        @if($this->courtDocumentations->count() > 0)
            <div
                class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($this->courtDocumentations as $image)
                    <div
                        class="relative size-44 overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900"
                        wire:key="image-{{ $image->id }}">
                        <img wire:click="showImage({{$image->id}})" class="object-cover size-44"
                             src="{{ asset('storage/'.$image->file) }}" alt="">
                        <div class="z-10" wire:ignore>
                            <div class="absolute top-0 right-0 p-1" wire:click="deleteImage({{$image->id}})"
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
                <flux:heading size="lg" level="1">Belum ada dokumentasi kegiatan</flux:heading>
            </div>
        @endif
    </x-card>
    <flux:modal name="modal-upload-image" class="md:w-lg">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Unggah Foto Dokumentasi</flux:heading>
                <flux:text class="mt-2">Tarik dan lepas foto ke dalam kotak atau cari foto melalui browser</flux:text>
            </div>
            <form wire:submit.prevent="store">
                <x-filepond label="Foto Dokumentasi (Max 2MB)" wire:model="files" multiple/>
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
    <flux:modal name="modal-show-image" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Foto Dokumentasi</flux:heading>
            </div>
            <div>
                @if($this->file)
                    <img class="object-cover w-full" src="{{ asset('storage/'.$this->file) }}" alt="">
                @endif
            </div>
        </div>
    </flux:modal>
</x-partials.sidebar>
