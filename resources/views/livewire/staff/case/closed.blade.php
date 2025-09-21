<?php

use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component {
    use WithFileUploads;
    use WithPagination;

    #[\Livewire\Attributes\Url(history: true, keep: true)]
    public $show = 5;
    #[\Livewire\Attributes\Url(history: true, keep: true)]
    public string $search = '';

    public ?int $id = null;
    public string $file_open = '';

    #[\Livewire\Attributes\Computed]
    public function cases()
    {
        return LegalCase::where('status', 'closed')
            ->where(function ($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                    ->orWhere('case_number', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('summary', 'like', '%' . $this->search . '%');
            })->latest()->paginate($this->show, pageName: 'closed-page');
    }

    public function showFile($id): void
    {
        $this->file_open = LegalCaseDocument::where('id', $id)->first()->file;
        Flux::modal('modal-show-image')->show();
    }
}; ?>

<x-partials.sidebar position="right" menu="staff-case" active="Pengajuan Kasus / Status Kasus / Ditutup">
    <x-table thead="#, Nomor, Kasus, Jenis, Tanggal Pengajuan, Status," :action="false"
             label="Pengajuan Kasus" sub-label="Informasi tentang kasus yang diajukan.">
        <x-slot name="filter">
            <x-filter wire:model.live="show"/>
            <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
        </x-slot>
        @if($this->cases->count())
            @foreach($this->cases as $case)
                <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <div class="block">
                            <flux:tooltip content="Nomor Kasus : {{$case->number}}" >
                                <flux:button variant="subtle"
                                             class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($case->number, 12, '...') }}</flux:button>
                            </flux:tooltip>
                        </div>
                        <div class="block">
                            <flux:tooltip content="No. Perkara : {{$case->case_number}}">
                                <flux:button variant="subtle"
                                             class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($case->case_number, 12, '...') }}</flux:button>
                            </flux:tooltip>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        {{ $case->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $case->type == 'civil' ? 'Perdata' : 'Pidana' }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $case->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$case->status"/>
                    </td>
                    <td class="px-6 py-4">
{{--                        <flux:button wire:navigate size="sm" variant="primary" icon:trailing="arrow-up-right"--}}
{{--                                     href="{{ route('staff.case.detail-case', ['id' => $case->id, 'status' => 'closed']) }}">--}}
{{--                            Lihat Detail--}}
{{--                        </flux:button>--}}
                        <flux:button variant="outline" icon:trailing="arrow-right" size="sm"
                                     class="cursor-pointer dark:bg-zinc-800 dark:hover:bg-zinc-800" wire:navigate
                                     href="{{ route('staff.active.page', ['id' => $case->id, 'status' => 'schedule']) }}">
                            Detail
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
    <flux:modal name="modal-show-image" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Dokumen Pendukung</flux:heading>
            </div>
            @if($this->file_open)
                <div>
                    <img class="object-cover w-full" src="{{ asset('storage/'.$this->file_open) }}" alt="">
                </div>
            @endif
        </div>
    </flux:modal>
</x-partials.sidebar>
