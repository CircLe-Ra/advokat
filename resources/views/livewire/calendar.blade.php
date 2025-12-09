<?php

use Livewire\Volt\Component;

new class extends Component {

    public string $url;
    public string $client = '';
    public string $lawyer = '';
    public string $title = '';
    public string $start = '';
    public string $end = '';
    public string $place = '';
    public string $status = '';
    public string $type = '';
    public string $caseTitle = '';
    public string $description = '';

    public $whois;

    public function mount($url):void
    {
        $this->url = $url;
        $this->whois = auth()->user()->roles()->first()->name;
    }

    public function __reset(): void
    {
        $this->reset(['title', 'start', 'end', 'place', 'status', 'type', 'caseTitle', 'description']);
    }

}; ?>

<x-card label="Jadwal Pertemuan & Persidangan" sub-label="Daftar seluruh kasus yang anda tangani.">
    <div wire:ignore class="bg-zinc-50 dark:bg-zinc-900 p-4 rounded-lg border border-zinc-300 dark:border-zinc-600" >
        <div id="calendar"></div>
    </div>
    <flux:modal name="modal-work-schedule" @close="__reset">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Detail Jadwal {{ $this->type == 'meeting' ? 'Pertemuan' : 'Sidang' }}</flux:heading>
                <flux:text class="mt-2">Informasi kasus yang akan dilaksanakan</flux:text>
            </div>
            <div class="grid grid-cols-2 border border-zinc-300 dark:border-zinc-600">
                @if($this->whois == 'klien')
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Nama Pengacara</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ $this->lawyer }}</flux:text>
                </div>
                @elseif($this->whois == 'pengacara')
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Nama Klien</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ $this->client }}</flux:text>
                </div>
                @endif
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Judul</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ $this->title }}</flux:text>
                </div>
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Waktu</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ Carbon\Carbon::parse($this->start)->format('d F Y, H:i') }} WIT - {{ $this->end }}</flux:text>
                </div>
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Status</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ $this->status == 'Court' ? 'Sidang Pengadilan' : 'Pertemuan Pengacara dan Klien' }}</flux:text>
                </div>
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Kasus</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ $this->caseTitle }}</flux:text>
                </div>
                @if($this->place)
                    <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                        <flux:heading size="md" level="1">Tempat</flux:heading>
                    </div>
                    <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                        <flux:text>{{ $this->place }}</flux:text>
                    </div>
                @endif
                <div class="border-r border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:heading size="md" level="1">Deskripsi</flux:heading>
                </div>
                <div class="border-b border-zinc-300 dark:border-zinc-600 p-2">
                    <flux:text>{{ $this->description }}</flux:text>
                </div>
            </div>

        </div>
    </flux:modal>
</x-card>

@pushonce('scripts')
    @script
        <script>
            document.addEventListener('livewire:navigated', () => {
                const calendarElement = document.getElementById('calendar');

                    fetch(@js($this->url))
                    .then(response => response.json())
                    .then(({ data }) => {
                        const events = data.legalCases.reduce((accum, legalCase) => {
                            const meetingEvents = legalCase.meeting_schedules.map(meeting => ({
                                client: legalCase.client,
                                lawyer: legalCase.lawyer,
                                title: meeting.title,
                                start: meeting.start,
                                end: meeting.end,
                                status: 'Meeting',
                                type: 'meeting',
                                caseTitle: legalCase.title,
                                description: meeting.status,
                            }));

                            const courtEvents = legalCase.court_schedules.map(court => ({
                                client: legalCase.client,
                                lawyer: legalCase.lawyer,
                                title: court.title,
                                start: court.start,
                                end: court.end,
                                place: court.place,
                                status: 'Court',
                                type: 'court',
                                caseTitle: legalCase.title,
                                description: `${court.status} - Tempat: ${court.place}`,
                            }));
                            return accum.concat(meetingEvents, courtEvents);
                        }, []);

                        new Calendar(calendarElement, {
                            locale: idLocale,
                            plugins: [multiMonthPlugin, timeGridPlugin, dayGridPlugin],
                            initialView: 'multiMonthYear',
                            headerToolbar: {
                                left: 'prev,next,today',
                                center: 'title',
                                right: 'timeGridDay,timeGridWeek,dayGridMonth,multiMonthYear'
                            },
                            events: events,
                            editable: false,
                            droppable: false,
                            eventClick: async function(info) {
                                $wire.dispatch('toast', {
                                    message: 'Megambil Informasi...',
                                    type: 'info',
                                    duration: 1000
                                });
                                const event = info.event;
                                const startDate = event.start ? event.start.toLocaleString() : "Tidak ada waktu mulai";
                                const endDate = event.end ? event.end.toLocaleString() : "Sampai selesai";

                                await $wire.set('client', event.extendedProps.client);
                                await $wire.set('lawyer', event.extendedProps.lawyer);
                                await $wire.set('title', event.title);
                                await $wire.set('start', startDate);
                                await $wire.set('end', endDate);
                                await $wire.set('place', event.extendedProps.place);
                                await $wire.set('status', event.extendedProps.status);
                                await $wire.set('type', event.extendedProps.type);
                                await $wire.set('caseTitle', event.extendedProps.caseTitle);
                                await $wire.set('description', event.extendedProps.description);

                                $wire.dispatch('action-toast-closed');
                                Flux.modal('modal-work-schedule').show();
                            },
                        }).render();
                    })
                    .catch(error => console.error('Error loading events:', error));

            }, {once: true});
        </script>
    @endscript
@endpushonce
