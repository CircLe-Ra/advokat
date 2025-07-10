<?php

use App\Models\LegalCase;
use Livewire\Attributes\{ Computed, Title, Url };
use Livewire\Volt\Component;
use Livewire\WithPagination;

new
#[Title('Kasus')]
class extends Component {
    use WithPagination;

    #[Url(history: true, keep: true)]
    public $show = 5;
    #[Url(history: true, keep: true)]
    public string $search = '';

    #[Computed]
    public function cases()
    {
        return LegalCase::where('lawyer_id', auth()->user()->lawyer->id)
            ->where(function ($query) {
                $query->where('number', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('summary', 'like', '%' . $this->search . '%');
            })->latest()->paginate($this->show, pageName: 'lawyer-case-page');
    }


}; ?>

<div>
    <x-partials.breadcrumbs active="Kasus"/>
    <x-table label="Kasus" sub-label="Daftar kasus yang anda tangani."
             thead="#, Nomor, Nama, Jenis, Tanggal Pengajuan, Status">
        <x-slot name="filter">
            <x-filter wire:model.live="show"/>
            <flux:input wire:model.live="search" size="sm" placeholder="Cari" class="w-full max-w-[220px]"/>
        </x-slot>
        @if($this->cases->count() > 0)
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
                        {{ $case->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :status="$case->status"/>
                    </td>
                    <td>
                        <flux:button variant="outline" icon:trailing="arrow-right" size="sm" class="cursor-pointer dark:bg-zinc-800 dark:hover:bg-zinc-800" href="{{ route('lawyer.case.page', ['case' => $case->id, 'status' => 'schedule']) }}">
                            Detail
                        </flux:button>
                    </td>
                </tr>
            @endforeach
        @else
            <td colspan="7" class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-400">
                Tidak ada data
            </td>
        @endif
    </x-table>
</div>

