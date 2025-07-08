<?php

use App\Models\LegalCase;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\{ Computed, Url, Title };

new
#[Title('Kasus Aktif')]
class extends Component {
    use WithFileUploads;
    use WithPagination;

    #[Url(history: true, keep: true)]
    public $show = 5;
    #[Url(history: true, keep: true)]
    public string $search = '';

    #[Computed]
    public function cases()
    {
        return LegalCase::where('status', 'accepted')
            ->where(function ($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                        ->orWhere('title', 'like', '%' . $this->search . '%')
                        ->orWhere('summary', 'like', '%' . $this->search . '%');
            })->latest()->paginate($this->show, pageName: 'active-case-page');
    }

}; ?>

<div>
    <x-partials.breadcrumbs active="Penanganan Kasus"/>
    <x-table thead="#, Nomor, Kasus, Jenis, Pengacara, Status," :action="false"
             label="Penanganan Kasus" sub-label="Daftar kasus yang diterima dan sedang dalam penanganan.">
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
                        <flux:tooltip content="{{$case->number}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($case->number, 12, '...') }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <td class="px-6 py-4">
                        {{ $case->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $case->type == 'civil' ? 'Perdata' : 'Pidana' }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $case->lawyer->user->name }}
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$case->status"/>
                    </td>
                    <td>
                        <flux:button variant="outline" icon:trailing="arrow-right" size="sm" class="cursor-pointer dark:bg-zinc-800 dark:hover:bg-zinc-800" href="{{ route('staff.active.page', ['id' => $case->id, 'status' => 'schedule']) }}">
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
</div>
