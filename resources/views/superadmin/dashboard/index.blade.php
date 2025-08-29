@extends('layouts.main')
@push('css')
    <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">

    <style>
        * {
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                "Apple Color Emoji",
                "Segoe UI Emoji",
                "Segoe UI Symbol",
                sans-serif;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 100%;
            margin: 1em auto;
        }

        #container {
            height: 400px;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid var(--highcharts-neutral-color-10, #e6e6e6);
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: var(--highcharts-neutral-color-60, #666);
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tbody tr:nth-child(even) {
            background: var(--highcharts-neutral-color-3, #f7f7f7);
        }

        .highcharts-description {
            margin: 0.3rem 10px;
        }


        @media (prefers-color-scheme: light) {
            body {
                background-color: rgb(255, 255, 255);
                color: #ffffff;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-2">
            <!-- View sales -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-12">
                            <div class="card-body text-nowrap">
                                <h5 class="card-title mb-0">{{ $activities->name ?? 'Tidak ada kegiatan aktif' }}</h5>
                                <p class="mb-2">
                                    {{ Carbon\Carbon::parse($activities->start_date)->translatedFormat('d F Y') }} -
                                    {{ Carbon\Carbon::parse($activities->end_date)->translatedFormat('d F Y') }}</p>
                                <a href="{{ url('kegiatan/show/' . Crypt::encrypt($activities->id)) }}"
                                    class="btn btn-primary">Lihat Kegiatan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- View sales -->
            <!-- Statistics -->
            <div class="col-xl-8 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Statistik</h5>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <div class="w-100">
                            <div class="row gy-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-primary me-4 p-2"><i
                                                class="fa-solid fa-users"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $participants }}</h5>
                                            <small>Peserta</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-info me-4 p-2"><i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $genderCount['L'] }}</h5>
                                            <small>Laki-laki</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-danger me-4 p-2"><i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $genderCount['P'] }}</h5>
                                            <small>Perempuan</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-success me-4 p-2"><i
                                                class="fa-solid fa-file-pen"></i></div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $activityReports }}</h5>
                                            <small>Laporan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics -->
            <!-- Chart -->
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Grafik</h5>
                    </div>
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container" class="highcharts-light"></div>                            
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/themes/adaptive.js"></script>
    

    <script>
        $(document).ready(function() {

            // Data retrieved from https://gs.statcounter.com/browser-market-share#monthly-202201-202201-bar

            // Create the chart
            Highcharts.chart('container', {
                chart: {
                    type: 'column',                    
                },

                title: {
                    text: 'Grafik Peserta Kegiatan'
                },                
                accessibility: {
                    announceNewData: {
                        enabled: true
                    }
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Total Peserta'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.0f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                        '<b>{point.y:.0f} peserta</b><br/>'
                },

                series: [{
                    name: 'Fakultas',
                    colorByPoint: true,
                    data: [
                        @foreach ($facultyChart as $faculty)
                            {
                                name: '{{ $faculty['nama'] }}',
                                y: {{ $faculty['total'] }},
                                drilldown: '{{ $faculty['nama'] }}'
                            },
                        @endforeach
                    ]
                }],
                drilldown: {
                    breadcrumbs: {
                        position: {
                            align: 'right'
                        }
                    },
                    series: [
                        @foreach ($facultyChart as $faculty)
                        {
                        name: '{{ $faculty['nama'] }}',
                        id: '{{ $faculty['nama'] }}',
                        data: [
                            @foreach ($faculty['prodi'] as $prodi)
                            [
                                '{{ $prodi['nama'] }}',
                                {{ $prodi['total'] }}
                            ],
                            @endforeach
                        ]
                    }, 
                    @endforeach
                    ]
                }
            });


        });
    </script>
@endpush
