<?php

use App\Facades\PusherBeams;
use App\Models\LegalCase;
use App\Models\LegalCaseDocument;
use App\Models\LegalCaseValidation;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[\Livewire\Attributes\Title('Detail Kasus')]
class extends Component {
    use WithFileUploads;

    public LegalCase $case;
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


    public function mount(LegalCase $case): void
    {
        $this->id = $case->id;
        $this->number = $case->number;
        $this->type = $case->type;
        $this->title = $case->title;
        $this->summary = $case->summary;
        $this->chronology = $case->chronology;
        $this->file_preview = LegalCaseDocument::where('legal_case_id', $case->id)->get();
        $this->condition = 'edit';
    }

    public function __reset(): void
    {
        $this->reset(['id', 'number', 'type', 'title', 'summary', 'chronology', 'file', 'file_preview']);
        $this->dispatch('pond-reset');
        $this->resetValidation(['id', 'number', 'type', 'title', 'summary', 'chronology', 'file', 'file_preview']);
    }

    public function revised(): void
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
                'status' => 'revised',
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

            LegalCaseValidation::create([
                'legal_case_id' => $case->id,
                'user_id' => auth()->user()->id,
                'date_time' => now(),
                'comment' => null,
                'validation' => 'revised',
            ]);

            $allStaff = User::whereHas('roles', function ($query) {
                $query->where('name', 'staf');
            })->get();
            foreach ($allStaff as $staff) {
                PusherBeams::send($staff->id, 'Kasus direvisi', $case->title, route('staff.case.validation', ['status' => 'revision']), true);
            }

            $this->__reset();
            $this->dispatch('toast', message: 'Kasus berhasil di perbarui');
            Flux::modal('modal-revision')->close();
            $this->redirectIntended(route('client.case.detail-case', $case->id), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function showFile($id): void
    {
        $this->file_open = LegalCaseDocument::where('id', $id)->first()->file;
        Flux::modal('modal-show-image')->show();
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

}; ?>

<x-partials.sidebar :back="route('client.case')" position="right" menu="case"
                    active="Kasus / Pengajuan Kasus / Detail Kasus">
    @if($this->case->status == 'revision')
        <x-slot:menu-info>
            <flux:callout icon="information-circle" color="orange" class="mt-2">
                <flux:callout.heading>Informasi Revisi</flux:callout.heading>
                <flux:callout.text>
                    {{ $this->case->validations->last()->comment }}
                </flux:callout.text>
            </flux:callout>
        </x-slot:menu-info>
        <x-slot:action>
            <flux:modal.trigger name="modal-revision">
                <flux:button variant="primary" size="sm" icon="arrow-up-on-square-stack" icon:variant="micro"
                             class="cursor-pointer">
                    Merevisi
                </flux:button>
            </flux:modal.trigger>
        </x-slot:action>
    @endif
    @if ($this->case->status == 'rejected')
        <x-slot:menuInfo>
            <flux:callout icon="information-circle" color="red" class="mt-2">
                <flux:callout.heading>Kasus Ditolak</flux:callout.heading>
                <flux:callout.text>
                    {{ $this->case->validations->last()->comment }}
                </flux:callout.text>
            </flux:callout>
        </x-slot:menuInfo>
    @endif
    <livewire:detail-case :id="$this->id"/>
    <flux:modal name="modal-revision" class="md:w-xl" variant="flyout">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Revisi Kasus</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">Periksa kembali informasi pengajuan kasus dan
                    dokumen pendukung sebelum mengirimkan data.
                </flux:text>
            </div>
            <form wire:submit="revised">
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
                <div class="flex gap-2 mt-4">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
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
