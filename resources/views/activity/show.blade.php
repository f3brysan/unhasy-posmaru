@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Detail Kegiatan</h4>
            </div>
            <div class="card-body">
                @if ($activity)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nama Kegiatan:</strong>
                            <div>{{ $activity->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tahun:</strong>
                            <div>{{ $activity->year }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Mulai Kegiatan:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->activity_start_date)->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Selesai Kegiatan:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->activity_end_date)->format('d F Y') }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Batas Awal Pendaftaran:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->registration_start_date)->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Batas Akhir Pendaftaran:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->registration_end_date)->format('d F Y') }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Batas Awal Absensi:</strong>
                            <div>{{ $activity->student_report_start }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Batas Akhir Absensi:</strong>
                            <div>{{ $activity->student_report_end }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <div>
                                @if ($activity->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non Aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Diupdate Oleh:</strong>
                            <div>{{ $activity->updated_by ?? '-' }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Data kegiatan tidak ditemukan.
                    </div>
                @endif
                <a href="{{ url('kegiatan') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
@endsection
