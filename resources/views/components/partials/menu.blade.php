<flux:navbar class="-mb-px max-lg:hidden ">
        @role('klien')
            <flux:navbar.item icon="gauge" :href="route('client.dashboard')" :current="request()->routeIs('client.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
            <flux:navbar.item icon="scale" :href="route('client.case')" :current="request()->routeIs('client.case*')" wire:navigate>Kasus</flux:navbar.item>
            <flux:navbar.item icon="calendar-days" :href="route('client.schedule')" :current="request()->routeIs('client.schedule*')" wire:navigate>Jadwal</flux:navbar.item>
        @endrole
        @role('staf')
            <flux:navbar.item icon="gauge" :href="route('staff.dashboard')" :current="request()->routeIs('staff.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
            <flux:navbar.item icon="circle-stack" :href="route('staff.master-data.lawyer')" :current="request()->routeIs('staff.master-data*')" wire:navigate>Master Data</flux:navbar.item>
            <flux:navbar.item icon="briefcase" :href="route('staff.case.validation', ['status' => 'pending'])" :current="request()->routeIs('staff.case*')" wire:navigate>Pengajuan Kasus</flux:navbar.item>
            <flux:navbar.item icon="network" :href="route('staff.active.case')" :current="request()->routeIs('staff.active*')" wire:navigate>Penanganan Kasus</flux:navbar.item>
        @endrole
        @role('pimpinan')
            <flux:navbar.item icon="gauge" :href="route('leader.dashboard')" :current="request()->routeIs('leader.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
            <flux:navbar.item icon="briefcase" :href="route('leader.case.validation', ['status' => 'verified'])" :current="request()->routeIs('leader.case*')" wire:navigate>Pengajuan Kasus</flux:navbar.item>
            <flux:navbar.item icon="presentation-chart-line" :href="route('leader.active.case')" :current="request()->routeIs('leader.active.case*')" wire:navigate>Kasus</flux:navbar.item>
        @endrole
        @role('pengacara')
            <flux:navbar.item icon="gauge" :href="route('lawyer.dashboard')" :current="request()->routeIs('lawyer.dashboard')" wire:navigate>Dashboard</flux:navbar.item>
            <flux:navbar.item icon="briefcase" :href="route('lawyer.case')" :current="request()->routeIs('lawyer.case*')" wire:navigate>Kasus</flux:navbar.item>
            <flux:navbar.item icon="calendar-days" :href="route('lawyer.schedule')" :current="request()->routeIs('lawyer.schedule*')" wire:navigate>Jadwal</flux:navbar.item>
        @endrole
</flux:navbar>

