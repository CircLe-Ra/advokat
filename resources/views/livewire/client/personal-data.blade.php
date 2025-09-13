<?php

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[\Livewire\Attributes\Title('Data Pribadi')]
class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $identity = '';
    public string $phone = '+62';
    public string $address = '';
    public string $place_of_birth = '';
    public string $date_of_birth = '';
    public $identity_image = '';
    public $client_image = '';
    public string $identity_image_preview = '';
    public string $client_image_preview = '';
    public $image;

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->identity = Auth::user()->client->identity ?? '';
        $this->phone = Auth::user()->client->phone ?? '';
        $this->address = Auth::user()->client->address ?? '';
        $this->place_of_birth = Auth::user()->client->place_of_birth ?? '';
        $this->date_of_birth = Auth::user()->client->date_of_birth ?? '';
        $this->identity_image_preview = Auth::user()->client->identity_image ?? '';
        $this->client_image_preview = Auth::user()->client->client_image ?? '';
    }

    public function updatePersonalData(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'identity' => ['required', 'string', 'max:16', 'min:16', 'unique:' . Client::class . ',identity,' . Auth::user()->client?->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'place_of_birth' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date'],
            'identity_image' => [$this->identity_image_preview ? 'nullable' : 'required', 'max:2048'],
            'client_image' => [$this->client_image_preview ? 'nullable' : 'required', 'max:2048'],
        ]);

        try {
            $data = [
                'identity' => $this->identity,
                'phone' => $this->phone,
                'address' => $this->address,
                'place_of_birth' => $this->place_of_birth,
                'date_of_birth' => $this->date_of_birth,
            ];

            if ($this->identity_image) {
                if ($this->identity_image_preview) {
                    Storage::disk('public')->delete($this->identity_image_preview);
                }
                $this->identity_image = $this->identity_image->store('identity', 'public');
                $data['identity_image'] = $this->identity_image;
            }

            if ($this->client_image) {
                if ($this->client_image_preview) {
                    Storage::disk('public')->delete($this->client_image_preview);
                }
                $this->client_image = $this->client_image->store('client', 'public');
                $data['client_image'] = $this->client_image;
            }
            DB::transaction(function () use ($data) {
                Auth::user()->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);
                Client::updateOrCreate([
                    'user_id' => Auth::user()->id,
                ], $data);
            });
            $this->dispatch('pond-reset');
            $this->dispatch('toast', message: 'Data pribadi berhasil di perbarui', type: 'success');
            $this->redirect('/client/case/personal-data', navigate: true);
        } catch (Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }

    }

    public function deleteImage($id, $type): void
    {
        $column = $type == 'identity' ? 'identity_image' : 'client_image';
        try {
            $image = Client::find($id);
            if ($image) {
                Storage::delete($image->$column);
                $image->update([$column => null]);
            }
            $this->dispatch('toast', message: 'Berhasil dihapus');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error', duration: 5000);
        }
    }

    public function showImage($id, $type): void
    {
        $column = $type == 'identity' ? 'identity_image' : 'client_image';
        $this->image = Client::where('id', $id)->first()->$column;
        Flux::modal('modal-show-image')->show();
    }

}
?>

<x-partials.sidebar position="right" menu="case" active="Data Pribadi">
    <x-card :label="__('Data Pribadi')" sub-label="Perbaharui data pribadi anda untuk kelengkapan penagajuan kasus">
        <form class="mt-6 space-y-6" wire:submit="updatePersonalData">
            <div class="flex flex-col xl:flex-row xl:justify-items-stretch ">
                <div class="grid gap-4">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <flux:input
                            wire:model="name"
                            label="Nama Lengkap"
                            type="text"
                        />
                        <flux:input
                            wire:model="email"
                            label="Email"
                            type="email"
                        />
                    </div>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <flux:input
                            wire:model="identity"
                            label="Nomor KTP"
                            type="text"
                        />
                        <flux:input
                            wire:model="phone"
                            label="Nomor Telepon"
                            type="text"
                            mask="+62 999-9999-9999"
                        />
                    </div>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <flux:input
                            wire:model="place_of_birth"
                            label="Tempat Lahir"
                            type="text"
                        />
                        <flux:input
                            wire:model="date_of_birth"
                            label="Tanggal Lahir"
                            type="date"
                        />
                    </div>
                    <div>
                        <flux:textarea
                            wire:model="address"
                            label="Alamat"
                            autocomplete
                        />
                    </div>
                </div>
                <flux:separator vertical variant="subtle" class="hidden xl:block mx-6"/>
                <div class="w-full xl:w-[50%]">
                    <x-filepond wire:model="identity_image" label="KTP (Max 2MB)"/>
                    @if($this->identity_image_preview)
                        <div
                            class="mb-6 relative overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                            <img wire:click="showImage({{ auth()->user()->client->id }}, 'identity')"
                                 class="object-cover w-full h-15 rounded-lg"
                                 src="{{ asset('storage/'.$this->identity_image_preview) }}" alt="">
                        </div>
                    @endif
                    <x-filepond wire:model="client_image" label="Foto Anda (Max 2MB)"/>
                    @if($this->client_image_preview)
                        <div
                            class="relative mb-6 overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                            <img wire:click="showImage({{ auth()->user()->client?->id }}, 'client')"
                                 class="object-cover w-full h-15 rounded-lg"
                                 src="{{ asset('storage/'.$this->client_image_preview) }}" alt="">
                        </div>
                    @endif
                    <flux:button variant="primary" class="w-full mt-3" type="submit">
                        {{ __('Perbarui Data') }}
                    </flux:button>
                </div>
            </div>
        </form>
    </x-card>

    <flux:modal name="modal-show-image" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Foto</flux:heading>
            </div>
            <div>
                <img class="object-cover w-full" src="{{ asset('storage/'.$this->image) }}" alt="">
            </div>
        </div>
    </flux:modal>
</x-partials.sidebar>
