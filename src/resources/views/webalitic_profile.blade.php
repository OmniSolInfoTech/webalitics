@extends('webalitics::boilerplate')

@section('title') Webalitic - Visitor Profile @endsection


@section('content')

{{--    @component('components.breadcrumb')--}}
{{--        @slot('li_1') VerifyID @endslot--}}
{{--        @slot('title') Webalitic - Visitor Profile @endslot--}}
{{--    @endcomponent--}}

    @php($geoip = json_decode($visitor_info->geoip))
    <div class="row align-items-center">
        <div class="col-4">
            <div>
                <h5 class="mb-0">Visitor Profile </h5>
            </div>
        </div>

    </div>
    <!-- end row -->
    <hr class="mb-4">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">IP Address</h4>
                    <ul>
                        <li>{{!empty($visitor_info->ip) ? $visitor_info->ip : "Unknown"}}</li>
                    </ul>
                    <h4 class="card-title mb-4">Browser/Version</h4>
                    <ul>
                        <li>{{!empty($visitor_info->browser) ? $visitor_info->browser : "Unknown"}} / {{!empty($visitor_info->version) ? $visitor_info->version : "Unknown"}}</li>
                    </ul>
                    <h4 class="card-title mb-4">OS</h4>
                    <ul>
                        <li>{{!empty($visitor_info->os) ? $visitor_info->os : "Unknown"}}</li>
                    </ul>
                    <h4 class="card-title mb-4">Desktop/Mobile</h4>
                    <ul>
                        <li>@if($visitor_info->m == 0) Desktop @else Mobile @endif</li>
                    </ul>
                    <h4 class="card-title mb-4">Page Visited</h4>
                    <ul>
                        <li>{{!empty($visitor_info->page) ? $visitor_info->page : "Unknown"}}</li>
                    </ul>
                    <h4 class="card-title mb-4">Referer</h4>
                    <ul>
                        <li>{{!empty($visitor_info->referer) ? $visitor_info->referer : "Unknown"}}</li>
                    </ul>
                    <h4 class="card-title mb-4">Geo IP Details</h4>
                    <ul>
                        <li>Timezone : {{isset($geoip->location->time_zone) ? $geoip->location->time_zone : "Unknown"}}</li>
                        <li>Continent : {{isset($geoip->continent->names->en) ? $geoip->continent->names->en : "Unknown"}}</li>
                        <li>Country : {{isset($geoip->country->names->en) ? $geoip->country->names->en : "Unknown"}}</li>
                        <li>Province/State : {{isset($geoip->subdivisions[0]->names->en) ? $geoip->subdivisions[0]->names->en : "Unknown"}}</li>
                        <li>City : {{isset($geoip->city->names->en) ? $geoip->city->names->en : "Unknown"}}</li>
                        <li>Postal Code : {{isset($geoip->postal->code) ? $geoip->postal->code : "Unknown"}}</li>
                    </ul>
                    <h4 class="card-title mb-4">User Agent Details</h4>
                    <ul>
                        {{!empty($visitor_info->u_agent) ? $visitor_info->u_agent : "Unknown"}}
                    </ul>
                    <h4 class="card-title mb-4">ISP</h4>
                    <ul>
                        {{isset($geoip->traits) ? $geoip->traits->autonomous_system_organization : "Unknown"}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Map Positioning</h4>
                    <div id="leaflet-map-marker" class="leaflet-map" style="height: 600px;"></div>
                </div>
            </div>
        </div>
    </div>

    <br/>
    <br/>
@endsection
@section('script')
    <script>
        var map = L.map('leaflet-map-marker').setView([{{$geoip->location->latitude}}, {{$geoip->location->longitude}}], 12);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        var marker = L.marker([{{$geoip->location->latitude}}, {{$geoip->location->longitude}}]).addTo(map);
        var circle = L.circle([{{$geoip->location->latitude}}, {{$geoip->location->longitude}}], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 500
        }).addTo(map);
    </script>
@endsection
