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
                            <div>{{ $activity->activity->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tahun:</strong>
                            <div>{{ $activity->activity->year }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Mulai Kegiatan:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->activity->activity_start_date)->format('d F Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Tanggal Selesai Kegiatan:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->activity->activity_end_date)->format('d F Y') }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Batas Awal Pendaftaran:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->activity->registration_start_date)->format('d F Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Batas Akhir Pendaftaran:</strong>
                            <div>{{ \Carbon\Carbon::parse($activity->activity->registration_end_date)->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Batas Awal Absensi:</strong>
                            <div>{{ $activity->activity->student_report_start }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Batas Akhir Absensi:</strong>
                            <div>{{ $activity->activity->student_report_end }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <div>
                                @if ($activity->activity->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non Aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Diupdate Oleh:</strong>
                            <div>{{ $activity->activity->updated_by ?? '-' }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Data kegiatan tidak ditemukan.
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Laporan Kegiatan</h4>
                @if (date('Y-m-d H:i:s') >= $time['start'] && date('Y-m-d H:i:s') <= $time['end'])
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" id="btnAddActivityReport">Tambah Laporan</button>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="activityReportTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activityReportModal" tabindex="-1" aria-labelledby="activityReportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityReportModalLabel">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="activityReportForm" enctype="multipart/form-data">                    
                    <input type="hidden" name="activity_id" value="{{ $activity->activity_id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="tgl_setor">Tanggal Lapor</label>
                            <input type="date" class="form-control" id="tgl_setor" name="tgl_setor" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Deskripsi Kegiatan</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="file">File</label>
                            <input type="file" class="form-control" id="file" name="file" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('#activityReportTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ URL::to('aktivitas/get-activity/' . Crypt::encrypt($activity->activity_id)) }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'tgl_setor',
                    name: 'tgl_setor'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#btnAddActivityReport').click(function() {
            $('#activityReportForm').trigger('reset');
            $('#activityReportModal').modal('show');
        });

        $('#activityReportForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ URL::to('aktivitas/store-activity-report') }}";
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#activityReportModal').modal('hide');
                    $('#activityReportTable').DataTable().ajax.reload();
                }
            });
        });
    </script>
@endpush
