<?php

use App\Facades\PusherBeams;
use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[\Livewire\Attributes\Title('Kasus')]
class extends Component {
    use WithFileUploads;

    public ?int $id = null;
    public string $number = '';
    public string $type = '';
    public string $title = '';
    public string $summary = '';
    public string $chronology = '';
    public array $file = [];
    public $file_preview;
    public string $file_open = '';
    public string $condition = '';

    public bool $client = true;
    public bool $caseModal = false;
    public bool $caseEditModal = false;

    public function mount(): void
    {
        if (!empty(Auth::user()->client)) {
            $this->client = false;
        }
    }

    #[Computed]
    public function cases()
    {
        return LegalCase::where('client_id', Auth::user()->client->id ?? 0)->get();
    }

    public function __reset(): void
    {
        $this->reset(['id', 'number', 'type', 'title', 'summary', 'chronology', 'file', 'file_preview']);
        $this->dispatch('pond-reset');
        $this->resetValidation(['id', 'number', 'type', 'title', 'summary', 'chronology', 'file', 'file_preview']);
    }

    public function showCaseModal(): void
    {
        $numberCase = LegalCase::count() + 1;
        $this->number = 'KASUS-' . str_pad($numberCase, 4, '0', STR_PAD_LEFT) . '/' . Auth::user()->id . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/' . date('H') . date('i') . date('s');
        $this->caseModal = true;
    }

    public function store(): void
    {
        $this->validate([
            'type' => ['required'],
            'title' => ['required'],
            'summary' => ['required'],
            'chronology' => ['required'],
            'file' => ['required'],
            'file.*' => ['max:2048'],
        ]);

        try {
            DB::transaction(function () {
                $case = LegalCase::updateOrCreate([
                    'client_id' => Auth::user()->client->id,
                    'number' => $this->number,
                    'type' => $this->type,
                    'title' => $this->title,
                    'summary' => $this->summary,
                    'chronology' => $this->chronology,
                ]);

                foreach ($this->file as $file) {
                    LegalCaseDocument::create([
                        'legal_case_id' => $case->id,
                        'file' => $file->store('case'),
                        'type' => $file->getClientOriginalExtension(),
                    ]);
                }
            });
            unset($this->cases);
            $this->__reset();
            $this->dispatch('toast', message: 'Pengajuan kasus berhasil dibuat');
            $this->caseModal = false;

        } catch (Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }

    }

    public function update(): void
    {
        $this->validate([
            'type' => ['required'],
            'title' => ['required'],
            'summary' => ['required'],
            'chronology' => ['required'],
        ]);
        try {
            $case = LegalCase::find($this->id);
            $case->update([
                'type' => $this->type,
                'title' => $this->title,
                'summary' => $this->summary,
                'chronology' => $this->chronology,
            ]);

            if (!empty($this->file)) {
                foreach ($this->file as $file) {
                    LegalCaseDocument::create([
                        'legal_case_id' => $case->id,
                        'file' => $file->store('case'),
                        'type' => $file->getClientOriginalExtension(),
                    ]);
                }
            }
            unset($this->cases);
            $this->__reset();
            $this->dispatch('toast', message: 'Kasus berhasil di perbarui');
            $this->caseEditModal = false;
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function edit($id, $type = 'edit'): void
    {
        $this->condition = $type;
        $case = LegalCase::find($id);
        $this->id = $id;
        $this->number = $case->number;
        $this->type = $case->type;
        $this->title = $case->title;
        $this->summary = $case->summary;
        $this->chronology = $case->chronology;
        $this->file_preview = LegalCaseDocument::where('legal_case_id', $id)->get();
        $this->caseEditModal = true;
    }

    public function submit($id): void
    {
        try {
            $case = LegalCase::find($id);
            $case->update([
                'status' => 'pending',
            ]);
            unset($this->cases);
            LegalCaseValidation::create([
                'legal_case_id' => $case->id,
                'user_id' => auth()->user()->id,
                'date_time' => now(),
                'comment' => null,
                'validation' => 'pending',
            ]);
            $allStaff = User::whereHas('roles', function ($query) {
                $query->where('name', 'staf');
            })->get();
            foreach ($allStaff as $staff) {
                PusherBeams::send($staff->id, 'Pengajuan Kasus Baru', $case->title, route('staff.case.validation', ['status' => 'pending']), true);
            }
            $this->dispatch('toast', message: 'Pengajuan kasus berhasil dikirim');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function delete(LegalCase $case): void
    {
        try {
            $case->delete();
            unset($this->cases);
            $this->dispatch('toast', message: 'Kasus berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function deleteFile($id): void
    {
        try {
            $file = LegalCaseDocument::find($id);
            if ($file) {
                Storage::delete($file->file);
                $file->delete();
            }
            $this->file_preview = LegalCaseDocument::where('legal_case_id', $file->legal_case_id)->get();
            $this->dispatch('toast', message: 'Berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function showFile($id): void
    {
        $this->file_open = LegalCaseDocument::where('id', $id)->first()->file;
        Flux::modal('modal-show-image')->show();
    }
}; ?>

<x-partials.sidebar menu="case" active="Kasus / Pengajuan Kasus">
    @if($this->client)
        <x-slot name="menuInfo">
            <flux:callout icon="information-circle" color="red" class="mt-2">
                <flux:callout.heading>Kelengkapan Data !</flux:callout.heading>
                <flux:callout.text>
                    Sebelum membuat pengajuan kasus, anda harus melengkapi data pribadi terlebih dahulu.
                    <flux:callout.link href="{{ route('client.case.personal-data') }}" wire:navigate>
                        Lengkapi Sekarang
                        <flux:icon.arrow-up-right class="ms-1 inline" variant="mini"/>
                    </flux:callout.link>
                </flux:callout.text>
            </flux:callout>
        </x-slot>
    @endif
    <x-slot name="action">
        <flux:button variant="primary" size="sm" icon="plus" icon:variant="micro" wire:click="showCaseModal"
                     :disabled="$this->client">Buat Kasus
        </flux:button>
    </x-slot>
    <x-table thead="#, No. Kasus, No. Perkara, Jenis, Tanggal Pengajuan, Status," :action="false"
             label="Pengajuan Kasus" sub-label="Informasi tentang kasus yang anda ajukan.">
        @if($this->cases->count())
            @foreach($this->cases as $case)
                <tr class="bg-white border-b dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 ">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <flux:tooltip content="{{$case->number}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ Str::limit($case->number, 5, '...') }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                        <flux:tooltip content="{{$case->case_number}}">
                            <flux:button variant="subtle"
                                         class="dark:bg-zinc-800 dark:hover:bg-zinc-800"> {{ $case->case_number ? Str::limit($case->case_number, 5, '...') : '-' }}</flux:button>
                        </flux:tooltip>
                    </th>
                    <td class="px-6 py-4">
                        {{ $case->type == 'civil' ? 'Perdata' : 'Pidana' }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $case->created_at->isoFormat('D MMMM Y HH:mm') }} WIT
                    </td>
                    <td class="px-6 py-4">
                        <flux:heading class="flex items-center gap-2">
                            <x-badge :status="$case->status"/>
                            @if($case->status == 'draft')
                                <flux:tooltip toggleable>
                                    <flux:button icon="information-circle" size="sm" variant="ghost"/>
                                    <flux:tooltip.content class="max-w-[20rem]">
                                        <p>Status pengajuan kasus Anda saat ini adalah <b>draft</b>.</p>
                                        <p>Anda masih dapat melakukan perubahan pada data kasus Anda.</p>
                                        <p>Pastikan bahwa data yang Anda masukkan sudah benar, kemudian ajukan pengajuan
                                            melalui menu aksi di samping.</p>
                                        <p class="text-red-500">Namun, jika Anda telah mengajukan kasus, Anda tidak
                                            dapat melakukan perubahan atau menghapus data kasus.</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>
                            @elseif($case->status == 'revision')
                                <flux:tooltip toggleable>
                                    <flux:button icon="information-circle" size="sm" variant="ghost"/>
                                    <flux:tooltip.content class="max-w-[20rem]">
                                        <p>Data yang anda ajukan belum lengkap harap segera lakukan perubahan.</p><br/>
                                        <p class="text-red-500"><b>Pesan:</b> {{ $case->validations->last()->comment }}
                                        </p><br/>
                                        <p>Anda dapat melihat detail kasus anda melalui menu aksi disamping.</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>
                            @elseif($case->status == 'verified')
                                <flux:tooltip toggleable>
                                    <flux:button icon="information-circle" size="sm" variant="ghost"/>
                                    <flux:tooltip.content class="max-w-[20rem]">
                                        <p>Kasus telah diajukan ke pimpinan.</p>
                                        <p class="text-emerald-500">Silahkan tunggu keputusan dari pimpinan</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>
                            @endif
                        </flux:heading>
                    </td>
                    <td class="px-6 py-4">
                        <flux:dropdown>
                            <flux:button size="sm" icon:trailing="chevron-down" variant="filled">Menu</flux:button>
                            <flux:menu>
                                <flux:menu.group heading="Kasus">
                                    <flux:menu.item icon:variant="micro" variant="info" icon="check-badge"
                                                    icon:trailing="arrow-right"
                                                    wire:click="$js.submit({{ $case->id }})"
                                                    :disabled="$case->status != 'draft'">Ajukan
                                    </flux:menu.item>
                                    <flux:menu.item icon:variant="micro" variant="info" icon="network"
                                                    icon:trailing="arrow-right" href="{{ route('client.case.handling', ['case' => $case->id, 'status' => 'schedule']) }}"
                                                    :disabled="$case->status != 'accepted'" wire:navigate>Penanganan Kasus
                                    </flux:menu.item>
                                </flux:menu.group>
                                <flux:menu.group heading="Komunikasi">
                                    <flux:menu.item icon:variant="micro" icon="chat-bubble-left-right"
                                                    icon:trailing="arrow-up-right" href="{{ route('client.chat') }}" wire:navigate>
                                        Hubungi Petugas
                                    </flux:menu.item>
                                </flux:menu.group>
                                <flux:menu.group heading="Aksi">
                                    <flux:menu.item icon:variant="micro" icon:trailing="arrow-up-right" icon="eye"
                                                    href="{{ route('client.case.detail-case', $case->id) }}" wire:navigate>
                                        Detail Kasus
                                    </flux:menu.item>
                                    <flux:menu.item icon:variant="micro" icon="pencil" variant="warning"
                                                    wire:click="edit({{ $case->id }})"
                                                    :disabled="$case->status != 'draft'">Ubah Kasus
                                    </flux:menu.item>
                                    <flux:menu.item icon:variant="micro" variant="danger" icon="trash"
                                                    wire:click="delete({{ $case->id }})"
                                                    wire:confirm="Anda yakin ingin menghapus kasus ini?"
                                                    :disabled="$case->status != 'draft'">Hapus Kasus
                                    </flux:menu.item>
                                </flux:menu.group>
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
    <flux:modal wire:model.self="caseModal" variant="flyout" @close="__reset">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Ajukan Kasus Anda</flux:heading>
                <flux:text class="mt-2">
                    <p>Buat pangajuan kasus anda dengan mengisi formulir berikut.</p>
                </flux:text>
            </div>
            <form wire:submit="store">
                <div class="flex flex-col gap-4">
                    <flux:input wire:model="number" label="Nomor Kasus" type="text" disabled/>
                    <flux:input wire:model="title" label="Nama Kasus" type="text"/>
                    <flux:select wire:model="type" label="Jenis Kasus">
                        <option value="">Pilih?</option>
                        <option value="civil">Perdata</option>
                        <option value="criminal">Pidana</option>
                    </flux:select>
                    <flux:textarea wire:model="summary" label="Ringkasan Kasus"/>
                    <flux:field>
                        <flux:label>Kronologi</flux:label>
                        <flux:description>Ceritakan kronologi kejadian yang terjadi secara detail.</flux:description>
                        <flux:textarea wire:model="chronology" cols="30" rows="10"/>
                        <flux:error name="chronology"/>
                    </flux:field>
                    <x-filepond wire:model="file" label="Dokumen Pendukung"
                                sub-label="Anda dapat mengunggah lebih dari satu dokumen" multiple/>
                </div>
                <div class="flex gap-2 mt-4">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    <flux:modal wire:model.self="caseEditModal" class="w-7xl" @close="__reset">
        <div class="space-y-6">
            <div>
                @if ($this->condition == 'detail')
                    <flux:heading size="lg">Detail Kasus Anda</flux:heading>
                    <flux:text class="mt-2">
                        <p>Silahkan lihat detail kasus anda.</p>
                    </flux:text>
                @else
                    <flux:heading size="lg">Ubah Kasus Anda</flux:heading>
                    <flux:text class="mt-2">
                        <p>Silahkan ubah data kasus anda.</p>
                    </flux:text>
                @endif
            </div>
            <form wire:submit="update">
                <div class="flex flex-col gap-4">
                    <flux:input wire:model="number" label="Nomor Kasus" type="text" disabled/>
                    <flux:input wire:model="title" label="Nama Kasus" type="text"
                                :disabled="$this->condition == 'detail'"/>
                    <flux:select wire:model="type" label="Jenis Kasus" :disabled="$this->condition == 'detail'">
                        <option value="">Pilih?</option>
                        <option value="civil">Perdata</option>
                        <option value="criminal">Pidana</option>
                    </flux:select>
                    <flux:textarea wire:model="summary" label="Ringkasan Kasus"
                                   :disabled="$this->condition == 'detail'"/>
                    <flux:field>
                        <flux:label>Kronologi</flux:label>
                        <flux:description>Ceritakan kronologi kejadian yang terjadi secara detail.</flux:description>
                        <flux:textarea wire:model="chronology" cols="30" rows="10"
                                       :disabled="$this->condition == 'detail'"/>
                        <flux:error name="chronology"/>
                    </flux:field>
                    @if($this->condition == 'edit')
                        <x-filepond wire:model="file" label="Dokumen Pendukung"
                                    sub-label="Anda dapat mengunggah lebih dari satu dokumen" multiple/>
                    @endif
                    @if($this->file_preview?->count() > 0)
                        <flux:label class="text-zinc-400">Dokumen Pendukung</flux:label>
                        @foreach($this->file_preview as $data)
                            <div
                                class="relative overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                                @if($data->type == 'png' || $data->type == 'jpg' || $data->type == 'jpeg' || $data->type == 'svg')
                                    <img wire:click="showFile({{ $data->id }})"
                                         class="object-cover w-full h-15 rounded-lg"
                                         src="{{ asset('storage/'. $data->file) }}" alt="">
                                @elseif($data->type == 'pdf' || $data->type == 'docx' || $data->type == 'doc' || $data->type == 'xlsx' || $data->type == 'xls' || $data->type == 'txt')
                                    <flux:button variant="primary" class="w-full" icon:trailing="arrow-up-right"
                                                 target="_blank" href="{{ asset('storage/'. $data->file) }}">Lihat
                                        Dokumen {{ $data->type }}</flux:button>
                                @endif
                                @if($data->count() > 1)
                                    @if($this->condition == 'edit')
                                        <div class="absolute top-2 right-2 flex gap-2">
                                            <flux:icon.circle-x size="sm" class="cursor-pointer "
                                                                wire:click="deleteFile({{$data->id}})"
                                                                wire:confirm="Lanjutkan menghapus?"/>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                @if($this->condition == 'edit')
                    <div class="flex gap-2 mt-6">
                        <flux:spacer/>
                        <flux:button type="submit" variant="primary">Simpan</flux:button>
                    </div>
                @endif
            </form>
        </div>
    </flux:modal>
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
@pushOnce('scripts')
    @script
    <script>
        document.addEventListener('livewire:navigated', () => {
            $js('submit', (id) => {
                $wire.dispatch('toast', {message: 'Proses...', type: 'info', duration: 5000});
                $wire.submit(id);
            });
        }, {once: true});
    </script>
    @endscript
@endpushOnce
