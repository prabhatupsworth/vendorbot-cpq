@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('latlon-help/leaflet.css')}}"/>
	<link rel="stylesheet" href="{{ asset('latlon-help/geosearch.css')}}"/>
	<link href="{{ asset('latlon-help/MarkerCluster.css')}}" rel="stylesheet" />
    <link href="{{ asset('latlon-help/MarkerCluster.Default.css')}}" rel="stylesheet" />
    <link href="{{ asset('latlon-help/Leaflet.DonutCluster.css')}}"  rel="stylesheet" />
<style>
    .donut-text {
    color: black;
    display: block;
    position: absolute;
    top: 50%;
    left: 0;
    z-index: 2;
    line-height: 0;
    width: 100%;
    text-align: center;
}
.donut-legend {
    background-color: rgba(255, 255, 255, 0.7);
    padding: 1px;
    white-space: nowrap;
}
    </style>
    <div class="page-wrapper">

        <div class="content">

            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h3 class="page-title mb-1">
                        Map With All Supplier

                        </h3>

                       

                    </div>

                   

                </div>

            </div>

            <div class="row">

            <div id="map" style="width:100%;height:700px;"></div>


            </div>

        </div>

    </div>
    
    @push('scripts')
    <!-- Make sure you put this AFTER Leaflet's CSS -->
	<script src="{{ asset('latlon-help/leaflet.js')}}"></script>
	<script src="{{ asset('latlon-help/bundle.min.js')}}"></script>
	<script src="{{ asset('latlon-help/leaflet.markercluster.js')}}"></script>
    <script src="{{ asset('latlon-help/Leaflet.DonutCluster.js')}}"></script>
    <script>
    const suppliers = @json($suppliers);

    var map = L.map('map').setView([{{ $centerLat }}, {{ $centerLon }}], 7);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var markers = L.markerClusterGroup();

    suppliers.forEach(function(supplier) {

        let marker = L.marker([
            parseFloat(supplier.lat),
            parseFloat(supplier.lon)
        ]);

        marker.bindPopup(
            '<b>' + supplier.name + '</b><br>' + (supplier.city || '')
        );

        markers.addLayer(marker);
    });

    map.addLayer(markers);
</script>
@endpush
@endsection
