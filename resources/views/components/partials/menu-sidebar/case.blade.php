@props(['menuInfo' => null])
<flux:navlist wire:ignore class=" border border-zinc-100 bg-zinc-100 shadow-md dark:border-zinc-700 dark:bg-zinc-900 p-4 rounded-lg">
    <flux:navlist.group expandable heading="Kasus">
        <flux:navlist.item class="py-5 text-base" :href="route('client.case')" :current="request()->routeIs('client.case') || request()->routeIs('client.case.detail-case')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('client.case') || request()->routeIs('client.case.detail-case') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Pengajuan Kasus</flux:navlist.item>
        <flux:navlist.item class="py-5 text-base" :href="route('client.case.personal-data')" :current="request()->routeIs('client.case.personal-data')" wire:navigate>
            <div class="absolute -inset-y-[1px] w-[2px] -start-7 ms-4 {{ request()->routeIs('client.case.personal-data') ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-700' }}" ></div>
            Data Pribadi
        </flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>
@isset($menuInfo)
    {{ $menuInfo }}
@endisset
