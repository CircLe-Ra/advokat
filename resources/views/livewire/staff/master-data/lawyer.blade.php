<?php

use App\Models\Client;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new
#[\Livewire\Attributes\Title('Master Data - Pengacara')]
class extends Component {
    use WithFileUploads;

    public ?int $id = null;
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $photo;
    public ?string $photo_preview = null;
    public ?string $image = null;
    public bool $showModalConfirm = false;

    #[\Livewire\Attributes\Computed]
    public function lawyers()
    {
        return Lawyer::latest()->get();
    }

    public function __reset()
    {
        $this->reset(['id', 'name', 'phone', 'photo', 'email', 'password', 'password_confirmation']);
        $this->dispatch('pond-reset');
        $this->resetValidation(['name', 'phone', 'email', 'password', 'password_confirmation']);
    }

    public function store(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => [$this->id ? 'nullable' : 'required', 'max:2048'],
            'email' => [$this->id ? 'nullable' : 'required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->id ?? null)],
            'password' => [$this->id ? 'nullable' : 'required', 'string', 'min:3', 'confirmed'],
        ]);

        try {
            if ($this->id) {
                if ($this->photo) {
                    if ($this->photo_preview) {
                        Storage::disk('public')->delete($this->photo_preview);
                    }
                    $validated['photo'] = $this->photo->store('lawyer', 'public');
                }
                DB::transaction(function () use ($validated) {
                    $filtered = Arr::except($validated, ['email', 'password']);
                    $lawyer = Lawyer::find($this->id);
                    $lawyer->user->update(['name' => $validated['name']]);
                    $lawyer->update($filtered);
                });
            } else {
                if ($this->photo) {
                    $validated['photo'] = $this->photo->store('lawyer', 'public');
                }

                DB::transaction(function () use ($validated) {
                    $validated['password'] = Hash::make($validated['password']);
                    $user = User::create([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'password' => $validated['password'],
                    ])->assignRole('pengacara');
                    $validated['user_id'] = $user->id;
                    $filtered = Arr::except($validated, ['name', 'email', 'password']);
                    Lawyer::create($filtered);
                });
            }
            unset($this->lawyers);
            $this->__reset();
            Flux::modal('modal-lawyer')->close();
            $this->dispatch('toast', message: 'Berhasil disimpan');
        } catch (\Throwable $th) {
            $this->dispatch('toast', message: $th->getMessage(), type: 'error');
        }
    }

    public function edit(Lawyer $lawyer): void
    {
        $this->id = $lawyer->id;
        $this->name = $lawyer->user->name;
        $this->phone = $lawyer->phone;
        $this->photo_preview = $lawyer->photo;

        Flux::modal('modal-lawyer')->show();
    }

    public function detail(Lawyer $lawyer): void
    {
        $this->id = $lawyer->id;
        $this->name = $lawyer->user->name;
        $this->phone = $lawyer->phone;
        $this->photo_preview = $lawyer->photo;

        Flux::modal('modal-detail-lawyer')->show();
    }

    public function showImage($id): void
    {
        $this->image = Lawyer::where('id', $id)->first()->photo;
        Flux::modal('modal-show-image')->show();
    }

    public function delete(User $user): void
    {
        $this->id = $user->id;
        $this->name = $user->name;
        $this->showModalConfirm = true;
    }

    public function destroy(): void
    {
        try {
            User::destroy($this->id);
            $this->showModalConfirm = false;
            $this->__reset();
            $this->dispatch('toast', message: 'Data berhasil dihapus');
        } catch (\Throwable $e) {
            $this->showModalConfirm = false;
            $this->__reset();
            $this->dispatch('toast', message: 'Data gagal dihapus', type: 'error');
        }
    }


}; ?>

<x-partials.sidebar position="right" menu="master-data" active="Advokat / Data Pengacara">
    <x-slot:action>
        <flux:modal.trigger name="modal-lawyer">
            <flux:button variant="primary" size="sm">
                <flux:icon.plus variant="micro"/>
                Tambah
            </flux:button>
        </flux:modal.trigger>
    </x-slot:action>
    <x-table thead="#,Nama Lengkap, Nomor Telepon, Foto," :action="false" main-class="border dark:border-zinc-700"
             label="Pengacara" sub-label="Data pengacara Advokat Kai And Riel Law Firm">
        @if($this->lawyers->count())
            @foreach($this->lawyers as $lawyer)
                <tr class="bg-white {{ $loop->last ? '' : 'border-b' }}  dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                    <th scope="col" class="px-6 py-3">
                        {{ $loop->iteration }}
                    </th>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $lawyer->user->name }}
                    </td>
                    <td class="px-6 py-4 text-nowrap">
                        {{ $lawyer->phone ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <flux:avatar size="lg" src="{{ asset('storage/'.$lawyer->photo) }}"/>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <flux:dropdown>
                            <flux:button size="sm" icon:trailing="chevron-down" variant="filled">Aksi</flux:button>
                            <flux:menu>
                                <flux:menu.item icon:variant="micro" icon="eye" wire:click="detail({{ $lawyer->id }})">
                                    Detail
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" icon="pencil" wire:click="edit({{ $lawyer->id }})">
                                    Ubah Data Lawyer
                                </flux:menu.item>
                                <flux:menu.separator/>
                                <flux:menu.item icon:variant="micro" variant="danger" icon="trash"
                                                wire:click="delete({{ $lawyer->user->id }})">Hapus
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="bg-white dark:bg-zinc-800 dark:border-zinc-700 border-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                <td colspan="8" class="text-center py-3">Tidak ada data</td>
            </tr>
        @endif
    </x-table>
    <flux:modal name="modal-lawyer" class="w-md" :dismissible="false" @close="__reset" variant="flyout">
        <form wire:submit="store">
            <div class="space-y-6 mb-6 ">
                <div class="-mt-2">
                    <flux:heading size="lg" level="1">Pengacara</flux:heading>
                    <flux:text class="mt-2">Tambahkan baru atau edit data pengacara.</flux:text>
                </div>
                <flux:input label="Nama" wire:model="name"/>
                <flux:input label="Nomor Telepon" wire:model="phone" mask="+62 999-9999-9999" value="+62"/>
                <x-filepond wire:model="photo" label="Foto Pengacara"/>
                @if($this->photo_preview)
                    <div
                        class="relative overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                        <img wire:click="showImage({{ $this->id }})"
                             class="object-cover w-full h-15 mb-6 rounded-lg"
                             src="{{ asset('storage/'.$this->photo_preview) }}" alt="photo">
                    </div>
                @endif
                @if(!$this->id)
                    <flux:separator text="Akun"/>
                    <flux:input label="Email" wire:model="email"/>
                    <div class="grid grid-cols-2 gap-2">
                        <flux:input label="Sandi" wire:model="password" type="password"/>
                        <flux:input label="Konfirmasi Sandi" wire:model="password_confirmation" type="password"/>
                    </div>
                @endif
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">Simpan</flux:button>
            </div>
        </form>
    </flux:modal>
    <flux:modal name="modal-show-image" class="md:w-7xl">
        <div class="space-y-6 mb-6">
            <div>
                <flux:heading size="lg" level="1">Foto</flux:heading>
            </div>
            @if($this->image)
                <div>
                    <img class="object-cover w-full" src="{{ asset('storage/'.$this->image) }}" alt="">
                </div>
            @endif
        </div>
    </flux:modal>
    <flux:modal name="modal-detail-lawyer" class="w-xl" :dismissible="false" @close="__reset">
        <form>
            <div class="space-y-6 mb-6 ">
                <div class="-mt-2">
                    <flux:heading size="lg" level="1">Pengacara</flux:heading>
                    <flux:text class="mt-2">Detail informasi pengacara Advokat Kai And Riel Law Firm</flux:text>
                </div>
                <flux:input label="Nama" wire:model="name" disabled/>
                <flux:input label="Nomor Telepon" wire:model="phone" mask="+62 999-9999-9999" value="+62" disabled/>
                @if($this->photo_preview)
                    <div
                        class="relative overflow-hidden rounded-lg hover:scale-105 transition duration-300 ease-in-out cursor-pointer hover:shadow-lg dark:hover:shadow-none border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                        <img wire:click="showImage({{ $this->id }})"
                             class="object-cover w-full h-15 mb-6 rounded-lg"
                             src="{{ asset('storage/'.$this->photo_preview) }}" alt="photo">
                    </div>
                @endif
            </div>
            <div class="flex gap-2">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="filled">Tutup</flux:button>
                </flux:modal.close>
            </div>
        </form>
    </flux:modal>
    <x-confirm wire:model.self="showModalConfirm" :trash="false"/>
</x-partials.sidebar>
