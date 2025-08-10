@extends('layouts.main')
@push('css')
    <style>
        .bg-gradient-custom-orange {
            background: linear-gradient(to left, #ee7b5e, #f1957e) !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Profil Mahasiswa -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Profil Mahasiswa</h5>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">NIM</label>
                                    <div>{{ $biodata->user->no_induk ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Mahasiswa</label>
                                    <div>{{ $biodata->user->name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Prodi</label>
                                    <div>{{ $biodata->prodi->prodi ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fakultas</label>
                                    <div>{{ $biodata->fakultas->fakultas ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">No Telp</label>
                                    <div>{{ $biodata->hp ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Ukuran Tshirt</label>
                                    <div>{{ $biodata->chart_size ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Gender</label>
                                    <div>{{ $biodata->gender ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Profil Mahasiswa -->

            <!-- Website Analytics -->
            <div class="col-lg-6 mb-4">
                <!-- BEGIN card -->
                <div class="card mb-3 overflow-hidden fs-13px border-0 bg-gradient-custom-orange"
                    style="min-height: 199px;">
                    <!-- BEGIN card-img-overlay -->
                    <div class="card-img-overlay mb-n4 me-n4 d-flex" style="bottom: 0; top: auto;">
                        <img src="{{ URL::to('/') }}/assets/img/icon/order.svg" alt=""
                            class="ms-auto d-block mb-n3" style="max-height: 105px">
                    </div>
                    <!-- END card-img-overlay -->

                    <!-- BEGIN card-body -->
                    <div class="card-body position-relative">
                        <h5 class="text-white text-opacity-80 mb-3 fs-16px">{{ $myActivities->activity->name }}</h5>
                        <h6 class="text-white mt-n1">
                            {{ Carbon\Carbon::parse($myActivities->activity->activity_start_date)->format('d M Y') }} -
                            {{ Carbon\Carbon::parse($myActivities->activity->activity_end_date)->format('d M Y') }}</h6>
                        <div class="progress bg-black bg-opacity-50 mb-2" style="height: 6px">
                            @php
                                $start = Carbon\Carbon::parse($myActivities->activity->activity_start_date);
                                $now = Carbon\Carbon::now();
                                $end = Carbon\Carbon::parse($myActivities->activity->activity_end_date);
                                $dayCount = $end->diffInDays($start) + 1;
                                $dayNow = $now->diffInDays($start) + 1;
                                $progress = $dayNow / $dayCount * 100;
                            @endphp
                            <div class="progrss-bar progress-bar-striped bg-white" style="width: {{ $progress }}%">
                            </div>
                        </div>
                        <div class="text-white text-opacity-80 mb-4"><i class="fa fa-file-text"></i> {{ $dayCount - $dayNow }} Laporan Kegiatan lagi
                        </div>
                        <div><a href="{{ URL::to('aktivitas/' . Crypt::encrypt($myActivities->activity_id)) }}"
                                target="_blank" class="text-white d-flex align-items-center text-decoration-none">Lihat
                                Laporan
                                <i class="fa fa-chevron-right ms-2 text-white text-opacity-50"></i></a></div>
                    </div>
                    <!-- BEGIN card-body -->
                </div>
                <!-- END card -->
            </div>
            <!--/ Website Analytics -->

        </div>
    </div>
@endsection
