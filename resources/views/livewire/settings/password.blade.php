<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    #[\Livewire\Attributes\On('update-password')]
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::min(3), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('toast', message: 'Sandi berhasil diubah');
    }

}; ?>

<section class="mt-2">
    <x-partials.sidebar menu="setting" active="Pengaturan / Penampilan">
        <x-card :label="__('Update password')" :sub-label="__('Ensure your account is using a long, random password to stay secure')">
            <form class="mt-6 w-[50%] space-y-6">
                <flux:input
                    wire:model="current_password"
                    :label="__('Current password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    wire:keyup="$js.fieldChanged"
                />
                <flux:input
                    wire:model="password"
                    :label="__('New password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    wire:keyup="$js.fieldChanged"
                />
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    wire:keyup="$js.fieldChanged"
                />

            </form>
        </x-card>
    </x-partials.sidebar>
</section>
@pushonce('scripts')
    @script
        <script>
            document.addEventListener('livewire:navigated', () => {
                $js('fieldChanged', () => {
                    if($wire.current_password !== "" || $wire.password !== "" || $wire.password_confirmation !== "") {
                        $wire.dispatch('action-toast', {
                            onConfirm: () => {
                                $wire.dispatch('update-password');
                            },
                            onCancel: () => {
                                $wire.current_password = "";
                                $wire.password = "";
                                $wire.password_confirmation = "";
                            }
                        });
                    }else if($wire.current_password === "" || $wire.password === "" || $wire.password_confirmation === ""){
                        $wire.dispatch('action-toast-closed');
                    }

                });
            }, { once: true });
        </script>
    @endscript
@endpushonce
