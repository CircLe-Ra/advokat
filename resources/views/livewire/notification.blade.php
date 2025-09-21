<?php

use App\Models\Chat;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public $unreadCount;

    public function mount(): void
    {
        $this->updateNotification();
    }

    #[On('update-notif')]
    public function updateNotification()
    {
        $this->unreadCount = Chat::where('user_id2', Auth::id())
            ->where('is_read', false)
            ->count();
    }

}; ?>

<div class="relative">
    <flux:tooltip :content="auth()->user()->hasRole('klien') ? 'Hubungi Petugas' : 'Hubungi Klien'" position="bottom">
        <flux:button size="sm"
                     class="{{ request()->routeIs('client.chat') || request()->routeIs('staff.chat') ? '!text-white bg-zinc-800/50' : '!text-white' }}"
                     href="{{ auth()->user()->hasRole('klien') ? route('client.chat') : route('staff.chat') }}"
                     icon="chat-bubble-left-right"
                     variant="subtle"
                     aria-label="Tombol obrolan"
                     wire:navigate>
        </flux:button>
    </flux:tooltip>

    @if($this->unreadCount > 0)
        <span class="absolute flex size-3 -top-1 -right-1">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-danger opacity-75"></span>
            <span class="relative inline-flex size-3 rounded-full bg-danger-content"></span>
        </span>
    @endif
</div>
