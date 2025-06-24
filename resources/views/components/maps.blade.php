<div x-data="mapComponent()" x-init="initMap" wire:ignore>
    <div id="map" class="w-full h-96" x-ref="map"></div>
</div>

@pushonce('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('mapComponent', () => ({
                initMap() {
                    const loader = new Loader({
                        apiKey: "{{ config('services.maps.key') }}",
                        version: "weekly",
                    });

                    loader.load().then(async () => {
                        const { Map } = await google.maps.importLibrary("maps");

                        new Map(this.$refs.map, {
                            center: { lat: -8.501717, lng: 140.3975859 },
                            zoom: 13,
                        });
                    });
                }
            }));
        });
    </script>
@endpushonce
