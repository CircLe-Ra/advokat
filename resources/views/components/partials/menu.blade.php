<flux:navbar class="-mb-px max-lg:hidden ">
        @role('klien')
            <flux:navbar.item icon="gauge" :href="route('client.dashboard')" :current="request()->routeIs('client.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
            <flux:navbar.item icon="scale" :href="route('client.case')" :current="request()->routeIs('client.case*')" wire:navigate>Kasus</flux:navbar.item>
            <flux:navbar.item icon="chat-bubble-left-right" :href="route('chat')" :current="request()->routeIs('chat*')" wire:navigate>Tanya Staf</flux:navbar.item>
        @endrole
        @role('staf')
            <flux:navbar.item icon="gauge" :href="route('staff.dashboard')" :current="request()->routeIs('staff.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
            <flux:navbar.item icon="circle-stack" :href="route('staff.master-data.lawyer')" :current="request()->routeIs('staff.master-data*')" wire:navigate>Master Data</flux:navbar.item>
            <flux:navbar.item icon="briefcase" :href="route('staff.case.validation', ['status' => 'pending'])" :current="request()->routeIs('staff.case*')" wire:navigate>Pengajuan Kasus</flux:navbar.item>
        @endrole
        @role('pimpinan')
            <flux:navbar.item icon="gauge" :href="route('leader.dashboard')" :current="request()->routeIs('leader.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
        @endrole
</flux:navbar>

