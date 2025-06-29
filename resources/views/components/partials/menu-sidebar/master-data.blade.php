@props(['menuInfo' => null])
<flux:navlist wire:ignore class=" border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Advokat">
        <flux:navlist.item class="py-5 text-base" :href="route('staff.master-data.lawyer')" :current="request()->routeIs('staff.master-data.lawyer')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.master-data.lawyer') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Pengacara</flux:navlist.item>
    </flux:navlist.group>
    <flux:navlist.group expandable heading="Pengguna">
        <flux:navlist.item class="py-5 text-base" href="{{ route('staff.master-data.user') }}" :current="request()->routeIs('staff.master-data.user')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.master-data.user') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Manajemen Akun
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" href="{{ route('staff.master-data.role') }}" :current="request()->routeIs('staff.master-data.role')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.master-data.role') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Manajemen Peran
        </flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
@isset($menuInfo)
    {{ $menuInfo }}
@endisset
