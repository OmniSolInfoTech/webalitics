<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>WEBALITICS Boilerplate</title>
        <link rel="stylesheet" href="{{ asset('webalitic-assets/style.css') }}">
        <!-- JQuery -->
        <script src="{{ asset('webalitic-assets/jquery-3.7.1.min.js') }}"></script>
        <!-- Bootstrap v5.3> -->
        <link href="{{ asset('webalitic-assets/bootstrap.min.css') }}" rel="stylesheet">
        <script src="{{ asset('webalitic-assets/bootstrap.bundle.min.js') }}"></script>
        <!-- DataTables -->
        <link href="{{ asset('webalitic-assets/datatables.min.css') }}" rel="stylesheet">
        <script src="{{ asset('webalitic-assets/datatables.min.js') }}"></script>
        <!-- ApexCharts -->
        <script src="{{ asset('webalitic-assets/apexcharts-bundle/dist/apexcharts.min.js') }}"></script>
        <!-- Leaflet -->
        <link rel="stylesheet" href="{{ asset('webalitic-assets/leaflet/leaflet.css') }}">
        <script src="{{ asset('webalitic-assets/leaflet/leaflet.js') }}"></script>
    </head>
    <body>
        <div class="container">
            @yield('content')
            @yield('script')
            </div>
        <script src="{{ asset('webalitic-assets/custom.js')}}"></script>
    </body>
</html>