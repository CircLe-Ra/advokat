<?php

use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new
#[\Livewire\Attributes\Title('Kasus')]
class extends Component {
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
        return LegalCase::where('status', 'verified')->latest()->paginate($this->show, pageName: 'verified-page');
    }

    public function submit($id): void
    {
        try {
            $case = LegalCase::find($id);
            $case->update([
                'status' => 'pending',
            ]);
            $this->dispatch('toast', message: 'Pengajuan kasus berhasil diajukan');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function __reset(): void
    {
        $this->reset(['id']);
        $this->dispatch('pond-reset');
        $this->resetValidation(['id']);
    }

    public function showFile($id): void
    {
        $this->file_open = LegalCaseDocument::where('id', $id)->first()->file;
        Flux::modal('modal-show-image')->show();
    }
}; ?>

<x-partials.sidebar position="right" menu="staff-case" active="Pengajuan Kasus / Status Kasus / Diverifikasi">
    <x-table thead="#, Nomor, Nama, Jenis, Tanggal Pengajuan, Status, Informasi" :action="false"
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
                    <td class="px-6 py-4">
                        <flux:tooltip toggleable>
                            <flux:button icon="information-circle" size="sm" variant="outline">Info</flux:button>
                            <flux:tooltip.content class="max-w-[20rem]">
                                <p>Diproses sebelumya anda telah memverifikasi data kasus ini sebagai</p>
                                <p>Anda masih dapat melakukan perubahan pada data kasus Anda.</p>
                                <p>Pastikan bahwa data yang Anda masukkan sudah benar, kemudian ajukan pengajuan
                                    melalui menu aksi di samping.</p>
                                <p class="text-red-500">Namun, jika Anda telah mengajukan kasus, Anda tidak
                                    dapat melakukan perubahan atau menghapus data kasus.</p>
                            </flux:tooltip.content>
                        </flux:tooltip>
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
