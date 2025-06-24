<flux:navbar class="-mb-px max-lg:hidden ">
        <flux:navbar.item icon="gauge" :href="route('client.dashboard')" :current="request()->routeIs('client.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
        <flux:navbar.item icon="scale" :href="route('client.case')" :current="request()->routeIs('client.case*')" wire:navigate>Kasus</flux:navbar.item>
</flux:navbar>
