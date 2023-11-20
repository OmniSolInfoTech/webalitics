@extends('layouts.master')

@section('title') Webalitic - Site Analytics @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') VerifyID @endslot
        @slot('title') Webalitic - Site Analytics @endslot
    @endcomponent

    <div class="row d-print-none search_box" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">
                        Search Visitors</h4>
                    <p class="card-title-desc">
                        Search visitors by data
                    </p>
                    <form class="row gy-2 gx-3 align-items-center custom-validation" method="post"
                          action="{{route('webalitic')}}">
                        @csrf
                        <div class="col-sm-auto">
                            <div class="input-daterange input-group" id="datepicker6" data-date-format="yyyy-mm-dd"
                                 data-date-autoclose="true" data-provide="datepicker"
                                 data-date-container='#datepicker6'>
                                <input type="text" class="form-control" name="startdate" placeholder="Start Date"
                                       data-parsley-errors-messages-disabled required/>
                                <input type="text" class="form-control" name="enddate" placeholder="End Date"
                                       data-parsley-errors-messages-disabled required/>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-primary processing">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">

                            <div class="d-flex flex-wrap">
                                <div class="me-3">
                                    <p class="text-muted mb-2">Visitors Today / Crawler </p>
                                    <h5 class="mb-0">{{$today}} / {{$is_bot}} <i class="bx bx-search search"></i></h5>
                                </div>

                                <div class="avatar-sm ms-auto">
                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                        <i class="bx bx-task"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card blog-stats-wid">
                        <div class="card-body">
                            <div class="d-flex flex-wrap">
                                <div class="me-3">
                                    <p class="text-muted mb-2">Total for Month</p>
                                    <h5 class="mb-0">{{$month_transactions}}</h5>
                                </div>

                                <div class="avatar-sm ms-auto">
                                    <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                        <i class="bx bx-stats"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Countries</h4>

                    <div id="pie_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Browsers</h4>

                    <div id="donut_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Desktop/Mobile</h4>

                    <div id="donut_chart_desktop_mobile" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Operating System</h4>

                    <div id="donut_chart_os" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="webalitic_table" class="table table-bordered dt-responsive table-responsive nowrap w-100 datatable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>DateTime</th>
                        <th>IP Address</th>
                        <th>Country</th>
                        <th>Desktop/Mobile</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i =1)
                    @foreach($transactions as $visit)
                        <tr>
                            <td>{{$i}}</td>
                            <td>{{$visit->created_at}}</td>
                            <td @if($visit->is_bot) style="background-color: #ec1c24; color: white" @endif>{{$visit->ip}}</td>
                            <td>{{$visit->country}}</td>
                            <td>@if($visit->m == "0") Desktop @elseif($visit->m == "1") Mobile @endif</td>
                            <td>
                                <button type="button" class="waves-effect btn-sm btn-primary"
                                        onclick="event.preventDefault(); document.getElementById('form_{{$visit->id}}').submit();">
                                    More Info
                                </button>
                                <form id="form_{{$visit->id}}" action="{{ route('webalitic-profile') }}" target="_blank" method="POST" style="display: none;">
                                    <input type="hidden" name="id" value="{{$visit->id}}"/>
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @php($i++)
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end row -->

@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            $('#webalitic_table').DataTable();

            var options = {
                chart: {
                    height: 320,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    data: <?php echo json_encode($cseries) ?>,
                    labels : <?php echo json_encode($clabels) ?>
                }],
                grid: {
                    borderColor: '#f1f1f1'
                },
                xaxis: {
                    categories: <?php echo json_encode($clabels) ?>
                }
            };
            var chart = new ApexCharts(document.querySelector("#pie_chart"), options);
            chart.render(); // Mixed chart

            var options = {
                chart: {
                    height: 350,
                    type: 'donut'
                },
                series: <?php echo json_encode($bseries) ?>,
                labels: <?php echo json_encode($blabels) ?>,
                colors: ["#34c38f", "#556ee6", "#f46a6a", "#50a5f1", "#f1b44c","#ebeced","#255BC7","#38C72A"],
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '14px',
                    offsetX: 0
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            height: 240
                        },
                        legend: {
                            show: false
                        }
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#donut_chart"), options);
            chart.render();

            var options = {
                chart: {
                    height: 350,
                    type: 'donut'
                },
                series: [{{$desktop}},{{$mobile}}],
                labels: ["Desktop", "Mobile"],
                colors: ["#34c38f", "#556ee6"],
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '14px',
                    offsetX: 0
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            height: 240
                        },
                        legend: {
                            show: false
                        }
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#donut_chart_desktop_mobile"), options);
            chart.render();

            var options = {
                chart: {
                    height: 350,
                    type: 'pie'
                },
                series: <?php echo json_encode($osseries) ?>,
                labels: <?php echo json_encode($oslabels) ?>,
                colors: ["#34c38f", "#556ee6", "#f46a6a", "#50a5f1", "#f1b44c", "#ebeced","#255BC7","#38C72A"],
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '14px',
                    offsetX: 0
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            height: 240
                        },
                        legend: {
                            show: false
                        }
                    }
                }]
            };
            var chart = new ApexCharts(document.querySelector("#donut_chart_os"), options);
            chart.render();

            $(".search").click(function(){
                $(".search_box").toggle();
            });

        });




    </script>
@endsection

