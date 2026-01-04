<x-front-layout title="Order-Details">

    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Order Details #{{ $order->number }}</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li><a href="{{ route('products.index') }}">Shop</a></li>
                            <li>Order</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>



    <section class="checkout-wrapper section">
        <div class="container">
            {{-- <div id="map"></div> --}}

            @if (!$order->delivery?->location)
                <div class="alert alert-warning">
                    <p> didn't found location</p>
                </div>
            @else
                <gmp-map id="order-map" zoom="15" map-id="DEMO_MAP_ID"
                    center="{{ $order->delivery->location['latitude'] }},{{ $order->delivery->location['longitude'] }}">

                    <div id="controls" slot="control-inline-start-block-start" class="map-control">
                        <strong>📍 موقع التوصيل</strong><br>
                        Order #{{ $order->id }}
                    </div>
                </gmp-map>
            @endif
        </div>
    </section>

    <script>
        (g => {
            var h, a, k, p = "The Google Maps JavaScript API",
                c = "google",
                l = "importLibrary",
                q = "__ib__",
                m = document,
                b = window;
            b = b[c] || (b[c] = {});
            var d = b.maps || (b.maps = {}),
                r = new Set,
                e = new URLSearchParams,
                u = () => h || (h = new Promise(async (f, n) => {
                    await (a = m.createElement("script"));
                    e.set("libraries", [...r] + "");
                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                    e.set("callback", c + ".maps." + q);
                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                    d[q] = f;
                    a.onerror = () => h = n(Error(p + " could not load."));
                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                    m.head.append(a)
                }));
            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                d[l](f, ...n))
        })
        ({
            key: "{{ config('services.google.api_key') }}",
            v: "weekly"
        });
    </script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>


    {{-- ===== Map Logic ===== --}}
    {{-- <script>
        async function initOrderMap() {

            const lat = Number("{{ $order->delivery->location['latitude'] ?? '' }}");
            const lng = Number("{{ $order->delivery->location['longitude'] ?? '' }}");

            if (!lat || !lng) return;

            const position = {
                lat,
                lng
            };

            const [{
                Map
            }, {
                AdvancedMarkerElement
            }] = await Promise.all([
                google.maps.importLibrary('maps'),
                google.maps.importLibrary('marker'),
            ]);

            const mapElement = document.getElementById('order-map');
            const map = mapElement.innerMap;

            map.setOptions({
                center: position,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });

            const marker = new AdvancedMarkerElement({
                map,
                position,
                title: 'موقع العميل',
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `
            <strong>Order #{{ $order->id }}</strong><br>
            حالة الطلب: {{ $order->status }}
        `
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });
        }

        initOrderMap();
    </script> --}}


    <script>
        let orderMarker;
        let orderMap;

        async function initOrderMap() {
            const lat = Number("{{ $order->delivery->location['latitude'] ?? '' }}");
            const lng = Number("{{ $order->delivery->location['longitude'] ?? '' }}");

            if (!lat || !lng) return;

            const position = {
                lat,
                lng
            };

            const [{
                Map
            }, {
                AdvancedMarkerElement
            }] = await Promise.all([
                google.maps.importLibrary('maps'),
                google.maps.importLibrary('marker'),
            ]);

            const mapElement = document.getElementById('order-map');

            orderMap = mapElement.innerMap;

            orderMap.setOptions({
                center: position,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });


            orderMarker = new AdvancedMarkerElement({
                map: orderMap,
                position: position,
                title: 'موقع العميل',
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<strong>Order #{{ $order->id }}</strong><br>حالة الطلب: {{ $order->status }}`
            });

            orderMarker.addListener('click', () => {
                infoWindow.open(orderMap, orderMarker);
            });
        }

        initOrderMap();

        // ----- Pusher Logic -----
        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: 'eu',
            channelAuthorization: {
                endpoint: "/broadcasting/auth",
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}"
                }
            }
        });

        var channel = pusher.subscribe('deliveries');
        // var channel = pusher.subscribe('private-deliveries.{{ $order->id }}'); // look at channels.php
        channel.bind('delivery.location.updated', function(data) {
            console.log("تحديث الموقع:", data.latitude, data.longitude);

            if (orderMarker) {
                const newPos = {
                    lat: Number(data.latitude),
                    lng: Number(data.longitude)
                };

                orderMarker.position = newPos;

                // orderMap.panTo(newPos); 
            }
        });
    </script>

    @push('styles')
        <style>
            gmp-map {
                display: block;
                height: 420px;
                width: 100%;
                border-radius: 14px;
                overflow: hidden;
                margin-top: 20px;
            }

            .map-control {
                background: #fff;
                padding: 10px 14px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
                font-size: 14px;
            }
        </style>
    @endpush

</x-front-layout>
