@props(['menuInfo' => null])
<flux:navlist wire:ignore class=" border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Status Kasus">
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.pending')" :current="request()->routeIs('staff.case.pending')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.case.pending') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Menunggu
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.verified')" :current="request()->routeIs('staff.case.verified')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.case.verified') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Terverifikasi
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.rejected')" :current="request()->routeIs('staff.case.rejected')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.case.rejected') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Ditolak
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.accepted')" :current="request()->routeIs('staff.case.accepted')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.case.accepted') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Diterima
        </flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('staff.case.closed')" :current="request()->routeIs('staff.case.closed')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('staff.case.closed') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Ditutup
        </flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
@isset($menuInfo)
    {{ $menuInfo }}
@endisset
