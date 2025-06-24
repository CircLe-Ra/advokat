<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new
#[\Livewire\Attributes\Title('Pengaturan - Profil')]
class extends Component {

    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    #[\Livewire\Attributes\On('save')]
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $this->dispatch('action-toast-closed');
        $this->dispatch('toast', message: 'Data berhasil disimpan');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="mt-2">
    <x-partials.sidebar menu="setting" active="Pengaturan / Profil">
        <x-card label="Profil" sub-label="Manajemen Profil dan Pengaturan Akun">
        <form class="w-[50%] space-y-6">
            <flux:input wire:model="name" wire:keyup="$js.fieldChanged" :label="__('Name')" type="text" autocomplete="off" required autofocus />

            <div>
                <flux:input wire:model="email" wire:keyup="$js.fieldChanged" :label="__('Email')" type="email" required />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>
        </form>
        <livewire:settings.delete-user-form />
        </x-card>
    </x-partials.sidebar>
</section>
@script
    <script>
        $js('fieldChanged', () => {
            if($wire.name !== @js(Auth::user()->name) || $wire.email !== @js(Auth::user()->email)) {
                $wire.dispatch('action-toast', {
                    onConfirm: () => {
                        $wire.dispatch('save');
                    },
                    onCancel: () => {
                        $wire.name = @js(Auth::user()->name);
                        $wire.email = @js(Auth::user()->email);
                    }
                });
            }else if($wire.name === @js(Auth::user()->name) || $wire.email === @js(Auth::user()->email)){
                $wire.dispatch('action-toast-closed');
            }

        });
    </script>
@endscript
