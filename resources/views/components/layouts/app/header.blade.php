<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 {{ auth()->user()->roles()->first()->name === 'klien' ? 'bg-blue-500' : (auth()->user()->roles()->first()->name === 'pengacara' ? 'bg-red-600' : (auth()->user()->roles()->first()->name === 'staf' ? 'bg-teal-600' : (auth()->user()->roles()->first()->name === 'pimpinan' ? 'bg-orange-600' : 'bg-zinc-50'))) }} dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('goto') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <x-app-logo />
            </a>

            <flux:spacer />

            <x-partials.menu />

            <flux:spacer />

            <x-partials.menu-icon />

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="end">
                <flux:profile
                    class="cursor-pointer"
                    :initials="auth()->user()->initials()"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    @if(auth()->user()->roles()->first()->name === 'klien')
                                    <flux:avatar size="xl" class="size-full" :src="asset('storage/' . auth()->user()->client->client_image)" :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                    @elseif(auth()->user()->roles()->first()->name === 'pengacara')
                                    <flux:avatar size="xl" class="size-full" :name="auth()->user()->name" :src="asset('storage/' . auth()->user()->lawyer->photo)" :initials="auth()->user()->initials()" />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('home')" icon="home" wire:navigate>Halaman Utama</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('goto') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item icon="layout-grid" :href="route('goto')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        <x-toaster />
        @fluxScripts
        {!! ToastMagic::scripts() !!}
        @stack('scripts')
        <script>
            document.addEventListener('livewire:navigated', () => {
                if(document.querySelector('meta[name="user-id"]')) {
                    const user_id = document.querySelector('meta[name="user-id"]').content;
                    const beamsTokenProvider = new PusherPushNotifications.TokenProvider({
                        url: "/pusher/beams-auth",
                        queryParams: {
                            user_id: `user-${user_id}`
                        }
                    });
                    beamsClient.start()
                        .then(() => beamsClient.setUserId(`user-${user_id}`, beamsTokenProvider))
                        .then(() => console.log('Successfully registered and subscribed!'))
                        .catch(console.error);

                    navigator.serviceWorker.register('/service-worker.js')
                        .then(registration => {
                            console.log('Service Worker registered with scope:', registration.scope);
                        })
                        .catch(error => {
                            console.error('Service Worker registration failed:', error);
                        });
                }
            }, { once: true });
        </script>
    </body>
</html>
