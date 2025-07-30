<?php

use App\Models\MeetingDocumentation;
use App\Models\MeetingSchedule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

new class extends Component {
    use WithFileUploads;

    public $meeting;
    public ?array $files = [];
    public $file;

    public function mount(MeetingSchedule $id): void
    {
        $this->meeting = $id;
    }

    #[Computed]
    public function meetingDocumentations()
    {
        return MeetingDocumentation::where('meeting_schedule_id', $this->meeting->id)->get();
    }

    public function showImage($file): void
    {
        $this->file = MeetingDocumentation::where('id', $file)->first()->file;
        Flux::modal('modal-show-image')->show();
    }

}; ?>

<x-partials.sidebar :back="route('lawyer.case.page', ['case' => $this->meeting->legal_case_id, 'status' => 'schedule'], absolute: false)" :id-detail="$this->meeting->legalCase?->id" menu="lawyer-active-case"
                    active="Penanganan Kasus / Jadwal Pertemuan / {{ $this->meeting->legalCase?->title }} / Hasil Pertemuan">
    <x-slot:profile>
        <div class="flex flex-col  dark:border-zinc-700 dark:bg-zinc-800 flex-shrink-0">
            <div
                class="flex flex-col items-center border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                <flux:heading size="xl" class="text-center text-xl mb-2">KLIEN</flux:heading>
                <div class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <flux:avatar size="xl" class="size-full " :name="$this->meeting->legalCase?->client->user->name"
                                 :initials="$this->meeting->legalCase?->client->user->initials()"/>
                </div>
                <div class="text-sm font-semibold mt-2">{{ $this->meeting->legalCase?->client->user->name }}</div>
                <div class="text-xs text-gray-500">{{ $this->meeting->legalCase?->client->user->email }}</div>
            </div>
        </div>
    </x-slot:profile>
    @if($this->meeting->status == 'cancelled')
        <flux:callout icon="no-symbol" variant="danger" inline class="mt-2 mb-1">
            <flux:callout.heading>Pertemuan dibatalkan
                pada {{ $this->meeting->updated_at->isoFormat('dddd, D MMMM YYYY HH:mm') }} WIT
            </flux:callout.heading>
            <x-slot name="actions">
                <flux:button icon:trailing="arrow-right" variant="outline"
                             href="{{ route('staff.active.page', ['id' => $this->meeting->legal_case_id, 'status' => 'schedule']) }}">
                    Kembali
                </flux:button>
            </x-slot>
        </flux:callout>
    @endif
    <x-card :label="__('Dokumentasi Pertemuan')"
            :sub-label=" __('Dokumentasi pertemuan dengan pengacara, Pada :date', ['date' => Carbon::parse($this->meeting->date_time)->isoFormat('dddd, D MMMM Y')])"
            main-class="flex-1">
        @if($this->meetingDocumentations->count() > 0)
            <div
                class="p-6 border border-zinc-200 dark:border-zinc-700 mt-1 rounded-lg bg-zinc-50 dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($this->meetingDocumentations as $image)
                    <div
                        class="relative size-44 overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900"
                        wire:key="image-{{ $image->id }}">
                        <img wire:click="showImage({{$image->id}})" class="object-cover size-44"
                             src="{{ asset('storage/'.$image->file) }}" alt="">
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
