<?php

use App\Facades\PusherBeams;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\ChatNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public ?int $client = 0;
    public $messages;
    public $messageToSend;

    public function mount($client = null): void
    {
        if ($client) {
            $this->client = $client;
            $this->messages = $this->loadMessages($client);
            Chat::where('user_id', $client)->where('user_id2', auth()->id())->update(['is_read' => true]);
        }
    }

    public function loadMessages($client)
    {
        $userId = Auth::id();
        return Chat::where(function ($query) use ($userId, $client) {
            $query->where('user_id', $userId)->where('user_id2', $client);
        })->orWhere(function ($query) use ($userId, $client) {
            $query->where('user_id', $client)->where('user_id2', $userId);
        })->orderBy('created_at', 'asc')->get();
    }

    #[On('update-message-admin')]
    public function updateMessageList()
    {
        $this->redirect(route('staff.chat', $this->client), navigate: true);
    }

    #[Computed]
    public function clients()
    {
        return User::whereHas('roles', fn($query) => $query->where('name', 'klien'))
            ->whereHas('sentMessages', fn($query) => $query->where('user_id2', auth()->id()))
            ->with(['sentMessages' => fn($q) => $q->where('user_id2', auth()->id())->latest()
            ])->get()
            ->sortByDesc(fn($user) => optional($user->sentMessages->first())->created_at)
            ->values();
    }

    public function send()
    {
        try {
            Chat::create([
                'user_id' => Auth::id(),
                'user_id2' => $this->client,
                'message' => $this->messageToSend,
            ]);
            $user = User::find($this->client);
            $user->notify(new ChatNotification(
                role: 'client',
                from: Auth::id(),
                message: $this->messageToSend,
                to: $this->client
            ));

            PusherBeams::send(
                user_id: $this->client,
                title: 'Pesan Baru',
                body: $this->messageToSend,
                deep_link: route('client.chat', Auth::id()),
                is_user: true
            );

            $this->messages = $this->loadMessages($this->client);
            $this->reset(['messageToSend']);

//            $this->dispatch('toast', message: 'Pesan berhasil dikirim');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Pesan harus diisi', type: 'error');
            return;
        }
    }

}; ?>

<div>
    <x-partials.breadcrumbs active="Konsultasi Kasus"/>
    <div class="flex antialiased text-zinc-800 mt-2">
        <div class="flex flex-row h-full w-full space-x-2">
            <div>
                <div class="flex flex-col w-64 border-zinc-200dark:border-zinc-700 flex-shrink-0 space-y-2">
                    <div
                        class="flex flex-col items-center border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-full py-6 px-4 rounded-lg">
                        <div wire:ignore
                             class="h-20 w-20 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                            <flux:avatar size="xl" class="size-full" :name="auth()->user()->name"
                                         :initials="auth()->user()->initials()"/>
                        </div>
                        <div
                            class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</div>
                    </div>
                    <div
                        class="flex flex-col h-full  p-6 rounded-lg border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex flex-row items-center justify-between text-xs">
                            <span class="font-bold text-zinc-900 dark:text-zinc-50">Kontak Klien</span>
                        </div>
                        <div class="flex flex-col space-y-1 mt-4 -mx-2 overflow-y-auto">
                            @foreach($this->clients as $client)
                                <a wire:navigate href="{{route('staff.chat', ['client' => $client->id])}}"
                                   class="flex flex-row items-center hover:bg-zinc-300 dark:hover:bg-zinc-700 hover:text-accent py-2 px-2 rounded-xl p-2 {{ $client->id == $this->client ? 'bg-zinc-300 text-accent dark:bg-zinc-700 dark:text-accent' : 'text-zinc-900 dark:text-zinc-50' }}">
                                    <flux:avatar class="rounded-full overflow-hidden" :initials="$client->initials()"/>
                                    <div class="ml-2 text-sm font-semibold ">{{$client->name}}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col flex-auto h-[calc(100vh-150px)] ">
                <div
                    class="flex flex-col flex-auto flex-shrink-0 rounded-lg border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 h-full p-4">
                    <div class="flex flex-col h-full overflow-x-auto my-4">
                        <div class="flex flex-col h-full">
                            <div class="grid grid-cols-12 gap-y-2">
                                @if($this->client)
                                    @foreach($this->messages as $message)
                                        @if($message->user_id !== auth()->id())
                                            <div class="col-start-1 col-end-8 p-3 rounded-lg">
                                                <div class="flex flex-row items-center">
                                                    <div
                                                        class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                                        <div>{{$message->message}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-start-6 col-end-13 p-3 rounded-lg">
                                                <div class="flex items-center justify-start flex-row-reverse">
                                                    <div
                                                        class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                                        <div>
                                                            {{ $message->message }}
                                                        </div>
                                                        <div
                                                            class="absolute text-xs bottom-0 right-0 -mb-5 mr-2 text-zinc-500">
                                                            {{ $message->is_read ? 'Dibaca' : 'Terkirim' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div
                                        class="col-start-1 col-end-13 p-3 rounded-lg text-center text-zinc-900 dark:text-white">
                                        Silahkan pilih klien dan mulai obrolan.
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @if($this->client)
                        <div
                            class="flex flex-row items-center h-16 rounded-xl dark:bg-zinc-600 bg-zinc-100 w-full px-2">
                            <div class="flex-grow">
                                <div class="relative w-full">
                                    <input type="text" wire:model="messageToSend"
                                           class="flex w-full dark:text-zinc-100 border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800 rounded-lg focus:outline-none focus:border-indigo-300 pl-4 h-10"/>
                                    <button
                                        class="absolute flex items-center justify-center h-full w-12 right-0 top-0 text-zinc-400 hover:text-zinc-600">
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="ml-4">
                                <flux:button variant="primary" class="ml-4" icon:trailing="paper-airplane" wire:click="$js.sendMessage">
                                    <div id="loader" class="hidden">
                                        <flux:icon.loader-circle class="animate-spin  mr-3 h-5 w-5 text-white" />
                                    </div>
                                    <div id="text_loader" class="block">Kirim</div>
                                </flux:button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>


@pushonce('scripts')
    @script
    <script>
        document.addEventListener('livewire:navigated', () => {
            const messagesContainer = document.querySelector('.flex.flex-col.h-full.overflow-x-auto');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            $js('sendMessage', async () => {
                let loader = document.querySelector('#loader');
                loader.style.display = 'block';
                let text_loader = document.querySelector('#text_loader');
                text_loader.style.display = 'none';
                await $wire.send();
                if (messagesContainer) {
                    loader.style.display = 'none';
                    text_loader.style.display = 'block';
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            });

        }, {once: true});
    </script>
    @endscript
@endpushonce
