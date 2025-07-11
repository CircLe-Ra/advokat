@props(['url' => null])
<div class="bg-zinc-50 dark:bg-zinc-900 p-4 rounded-lg border border-zinc-300 dark:border-zinc-600" >
    <div id="calendar"></div>
</div>

@pushonce('scripts')
    <script>
        document.addEventListener('livewire:navigated', () => {
            const calendarElement = document.getElementById('calendar');

            fetch(@js($url))
                .then(response => response.json())
                .then(({ data }) => {
                    new Calendar(calendarElement, {
                        locale: idLocale,
                        plugins: [multiMonthPlugin],
                        events: data.meeting_schedules, // Menampilkan data events yang sudah dimuat
                        editable: true,       // Mengaktifkan fitur drag & drop
                        droppable: true,      // Membolehkan penempatan event
                        // dateClick: this.onDateClick, // Fungsi ketika klik tanggal
                        // eventClick: this.onEventClick, // Fungsi ketika klik event
                        // eventDrop: this.onEventDrop, // Fungsi ketika event dipindahkan
                    }).render();
                })
                .catch(error => console.error('Error loading events:', error));

        }, {once: true});
    </script>
@endpushonce
