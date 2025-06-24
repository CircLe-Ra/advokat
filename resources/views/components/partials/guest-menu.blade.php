<flux:navbar class="-mb-px max-lg:hidden ">
    <flux:navlist.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>Beranda</flux:navlist.item>
    <flux:navlist.item icon="church" :href="route('schedule')" :current="request()->routeIs('schedule')" wire:navigate>Jadwal Pemuda Gereja</flux:navlist.item>
    <flux:navlist.item icon="presentation-chart-line" :href="route('activity')" :current="request()->routeIs('activity*')" wire:navigate>Kegiatan</flux:navlist.item>
</flux:navbar>
