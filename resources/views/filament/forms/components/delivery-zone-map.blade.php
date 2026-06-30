@php
    $googleMapsKey = $googleMapsKey ?? config('services.google_maps.key');
    $storeLat = (float) ($storeLat ?? 43.8407468);
    $storeLng = (float) ($storeLng ?? 25.9536970);
    $storeLogoUrl = asset('images/logo-map.png');
@endphp

<div
    wire:ignore
    x-data="deliveryZoneEditor({
        state: @entangle($statePath),
        storeLat: {{ $storeLat }},
        storeLng: {{ $storeLng }},
    })"
    class="space-y-3"
>
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div x-ref="map" class="h-96 w-full bg-gray-100"></div>
    </div>

    <div class="flex flex-wrap gap-2">
        <button type="button" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200" x-on:click.prevent="resetToDefault()">
            Възстанови по подразбиране
        </button>
        <button type="button" class="rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:bg-red-950 dark:text-red-300" x-on:click.prevent="clearPolygon()">
            Изчисти полигона
        </button>
        <button type="button" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200" x-on:click.prevent="removeLastPoint()" x-show="pointCount() > 0">
            Премахни последна точка
        </button>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400">
        <span x-text="pointCount()"></span> точки.
        Кликнете върху картата, за да добавите точка. Влачете ъглите на полигона, за да коригирате границата.
        {{ \App\Support\DeliveryZone::boundaryDescription() }}
    </p>

    <p x-show="loadError" x-cloak class="text-sm text-danger-600" x-text="loadError"></p>
</div>

@if (! $googleMapsKey)
    <p class="mt-2 text-sm text-danger-600">Добавете GOOGLE_MAPS_API_KEY в .env, за да редактирате района на картата.</p>
@endif

@if ($googleMapsKey)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}" async defer></script>
@endif

<script>
    window.ensureGoogleMapsReady = window.ensureGoogleMapsReady || function () {
        if (window.__googleMapsReadyPromise) {
            return window.__googleMapsReadyPromise;
        }

        window.__googleMapsReadyPromise = new Promise((resolve, reject) => {
            let attempts = 0;
            const maxAttempts = 150;

            const check = () => {
                attempts++;

                if (typeof google !== 'undefined' && google.maps && typeof google.maps.Map === 'function') {
                    resolve(google.maps);
                    return;
                }

                if (attempts >= maxAttempts) {
                    reject(new Error('Google Maps не се зареди.'));
                    return;
                }

                setTimeout(check, 100);
            };

            check();
        });

        return window.__googleMapsReadyPromise;
    };

    if (!window.__deliveryZoneEditorRegistered) {
        window.__deliveryZoneEditorRegistered = true;

        document.addEventListener('alpine:init', () => {
            Alpine.data('deliveryZoneEditor', ({ state, storeLat, storeLng }) => ({
                state,
                storeLat,
                storeLng,
                points: [],
                map: null,
                polygon: null,
                storeMarker: null,
                pathListeners: [],
                syncing: false,
                syncTimer: null,
                loadError: '',

                init() {
                    this.points = this.normalizePoints(this.state);
                    this.$nextTick(() => this.bootMap());

                    const form = this.$el.closest('form');
                    if (form) {
                        form.addEventListener('submit', () => this.flushToWire());
                    }
                },

                bootMap() {
                    window.ensureGoogleMapsReady()
                        .then(() => this.initMap())
                        .catch(() => {
                            this.loadError = 'Картата не се зареди. Проверете GOOGLE_MAPS_API_KEY и презаредете страницата.';
                        });
                },

                initMap() {
                    if (this.map) {
                        return;
                    }

                    this.map = new google.maps.Map(this.$refs.map, {
                        center: { lat: this.storeLat, lng: this.storeLng },
                        zoom: 14,
                        mapTypeControl: false,
                        streetViewControl: false,
                    });

                    this.storeMarker = new google.maps.Marker({
                        map: this.map,
                        position: { lat: this.storeLat, lng: this.storeLng },
                        title: 'Allo! Pizza',
                        icon: {
                            url: @js($storeLogoUrl),
                            scaledSize: new google.maps.Size(58, 58),
                            anchor: new google.maps.Point(11, 56),
                        },
                        zIndex: 1000,
                    });

                    this.drawPolygon(true);

                    this.map.addListener('click', (event) => {
                        if (this.syncing) {
                            return;
                        }

                        this.points.push({
                            lat: event.latLng.lat(),
                            lng: event.latLng.lng(),
                        });
                        this.drawPolygon(false);
                        this.flushToWire();
                    });

                    google.maps.event.trigger(this.map, 'resize');
                },

                normalizePoints(value) {
                    if (!Array.isArray(value)) {
                        return [];
                    }

                    return value.map((point) => ({
                        lat: parseFloat(point.lat),
                        lng: parseFloat(point.lng),
                    })).filter((point) => !Number.isNaN(point.lat) && !Number.isNaN(point.lng));
                },

                roundPoints(points) {
                    return points.map((point) => ({
                        lat: Math.round(point.lat * 1000000) / 1000000,
                        lng: Math.round(point.lng * 1000000) / 1000000,
                    }));
                },

                pointCount() {
                    return this.points.length;
                },

                drawPolygon(shouldFit) {
                    if (!this.map) {
                        return;
                    }

                    this.syncing = true;
                    this.destroyPolygon();

                    if (this.points.length >= 3) {
                        this.polygon = new google.maps.Polygon({
                            paths: this.points,
                            strokeColor: '#EB1C22',
                            strokeOpacity: 1,
                            strokeWeight: 3,
                            fillColor: '#EB1C22',
                            fillOpacity: 0.22,
                            editable: true,
                            draggable: false,
                            map: this.map,
                            zIndex: 2,
                        });

                        this.bindPathListeners();

                        if (shouldFit) {
                            this.fitToPoints();
                        }
                    } else {
                        this.map.setCenter({ lat: this.storeLat, lng: this.storeLng });
                        if (shouldFit) {
                            this.map.setZoom(14);
                        }
                    }

                    google.maps.event.trigger(this.map, 'resize');
                    this.syncing = false;
                },

                fitToPoints() {
                    if (!this.map || this.points.length === 0) {
                        return;
                    }

                    const bounds = new google.maps.LatLngBounds();
                    this.points.forEach((point) => bounds.extend(point));
                    bounds.extend({ lat: this.storeLat, lng: this.storeLng });
                    this.map.fitBounds(bounds, 48);
                },

                bindPathListeners() {
                    if (!this.polygon) {
                        return;
                    }

                    const path = this.polygon.getPath();
                    const sync = () => {
                        if (this.syncing) {
                            return;
                        }

                        this.readPathIntoPoints();
                        this.scheduleWireSync();
                    };

                    this.pathListeners = [
                        google.maps.event.addListener(path, 'set_at', sync),
                        google.maps.event.addListener(path, 'insert_at', sync),
                        google.maps.event.addListener(path, 'remove_at', sync),
                    ];
                },

                readPathIntoPoints() {
                    if (!this.polygon) {
                        return;
                    }

                    const path = this.polygon.getPath();
                    const points = [];

                    for (let i = 0; i < path.getLength(); i++) {
                        const latLng = path.getAt(i);
                        points.push({
                            lat: latLng.lat(),
                            lng: latLng.lng(),
                        });
                    }

                    this.points = this.roundPoints(points);
                },

                scheduleWireSync() {
                    clearTimeout(this.syncTimer);
                    this.syncTimer = setTimeout(() => this.flushToWire(), 400);
                },

                flushToWire() {
                    clearTimeout(this.syncTimer);
                    this.state = this.roundPoints(this.points);
                },

                destroyPolygon() {
                    this.pathListeners.forEach((listener) => google.maps.event.removeListener(listener));
                    this.pathListeners = [];

                    if (this.polygon) {
                        this.polygon.setMap(null);
                        this.polygon = null;
                    }
                },

                resetToDefault() {
                    this.points = this.roundPoints(@js(\App\Support\DeliveryZone::defaultPolygon()));
                    if (!this.map) {
                        this.bootMap();
                        return;
                    }
                    this.drawPolygon(true);
                    this.flushToWire();
                },

                clearPolygon() {
                    this.points = [];
                    if (!this.map) {
                        this.flushToWire();
                        return;
                    }
                    this.drawPolygon(true);
                    this.flushToWire();
                },

                removeLastPoint() {
                    this.points.pop();
                    if (!this.map) {
                        this.flushToWire();
                        return;
                    }
                    this.drawPolygon(false);
                    this.flushToWire();
                },
            }));
        });
    }
</script>
