@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (!empty($sessionNow))
            <div class="card mt-4">
                <marquee>
                    <div class="card-body">                    
                        <h4><i class="fa fa-info-circle"></i> Sesi kegiatan saat ini adalah {{ $sessionNow->name }}. Pengumpulan laporan dilakukan pada {{ $sessionNow->student_report_start }} sampai dengan {{ $sessionNow->student_report_end }}.</h4>                    
                    </div>
                </marquee>  
            </div>
        @endif

        <div class="card mt-4">
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
                <h4 class="mb-0">Sertifikat</h4>
            </div>
            <div class="card-body">
                @if ($allowCertificate)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="certificateTable">
                            <tr>
                                <td class="text-center">Sertifikat</td>
                                <td class="text-center">
                                    <a href="{{ URL::to('sertifikat/cetak/' . Crypt::encrypt($activity->id)) }}"
                                        class="btn btn-primary btn-sm" id="btnGenerateCertificate">Unduh Sertifikat</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        Sertifikat belum tersedia
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Laporan Kegiatan</h4>
                @if (!empty($sessionNow))
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary btn-sm" id="btnAddActivityReport">Tambah Laporan</button>
                    </div>
                @else
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-secondary btn-sm" disabled>Laporan belum dibuka</button>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="activityReportTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Sesi</th>
                                <th>Deskripsi Kegiatan</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Upload Tugas</h4>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm" id="btnAddActivityTask">Unggah Tugas</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="activityTaskTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
                        <input type="hidden" name="activity_session_id" value="{{ $sessionNow->id ?? null }}">
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="tgl_setor">Tanggal Lapor</label>
                                <input type="date" class="form-control" id="tgl_setor" name="tgl_setor"
                                    value="{{ date('Y-m-d') }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="description">Deskripsi Kegiatan</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="file">File <code>*Wajib JPG/JPEG/PNG.</code> <code>Ukuran:
                                        max:2048KB</code></label>
                                <input type="file" class="form-control" id="file" name="file" accept="image/*">
                            </div>
                            <div class="form-group mb-3">
                                <label>Preview</label>
                                <div>
                                    <img id="filePreview" src="#" alt="Preview"
                                        style="max-width: 100%; max-height: 200px; display: none;" />
                                </div>
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

        <div class="modal fade" id="activityTaskModal" tabindex="-1" aria-labelledby="activityTaskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activityTaskModalLabel">Unggah Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="activityTaskForm" enctype="multipart/form-data">
                        <input type="hidden" name="activity_id" value="{{ $activity->activity_id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">                        
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="task_tgl_setor">Tanggal Unggah</label>
                                <select class="form-control" id="task_tgl_setor" name="tgl_setor" required>
                                    <option value="">Pilih Tanggal</option>
                                    @php
                                        $loopDate = $startDate->copy();
                                    @endphp
                                    @while ($loopDate->lte($endDate))
                                        <option value="{{ $loopDate->toDateString() }}"
                                            {{ $loopDate->isToday() ? 'selected' : '' }}>
                                            {{ $loopDate->translatedFormat('d F Y') }}
                                        </option>
                                        @php
                                            $loopDate->addDay();
                                        @endphp
                                    @endwhile
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="task_description">Deskripsi Tugas</label>
                                <textarea class="form-control" id="task_description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="task_file">File <code>*Wajib JPG/PDF.</code> <code>Ukuran:
                                        max:2MB</code></label>
                                <input type="file" class="form-control" id="task_file" name="file"
                                    accept=".jpg,.jpeg,.pdf,image/jpeg,application/pdf" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Preview</label>
                                <div>
                                    <img id="taskFilePreview" src="#" alt="Preview"
                                        style="max-width: 100%; max-height: 200px; display: none;" />
                                    <div id="taskFilePdfLabel" class="text-muted" style="display: none;">
                                        File PDF siap diunggah.
                                    </div>
                                </div>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $('#activityReportTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ URL::to('aktivitas/get-activity/' . Crypt::encrypt($activity->activity_id)) }}",
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                },
                columns: [{
                        data: 'tgl_setor',
                        name: 'tgl_setor',
                        className: 'text-center'
                    },
                    {
                        data: 'session',
                        name: 'session',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
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
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    cache: false,
                    success: function(response) {
                        $('#activityReportModal').modal('hide');
                        $('#activityReportTable').DataTable().ajax.reload();
                        toastr.success('Laporan berhasil disimpan');
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('click', '.delete-report', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menghapus laporan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ URL::to('aktivitas/delete-activity-report') }}",
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                $('#activityReportTable').DataTable().ajax.reload();
                                toastr.success('Laporan berhasil dihapus');
                            },
                            error: function(xhr, status, error) {
                                toastr.error(xhr.responseJSON.message);
                            }
                        });
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('file');
                const previewImg = document.getElementById('filePreview');

                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            previewImg.src = ev.target.result;
                            previewImg.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        previewImg.src = '#';
                        previewImg.style.display = 'none';
                    }
                });

                const taskFileInput = document.getElementById('task_file');
                const taskPreviewImg = document.getElementById('taskFilePreview');
                const taskPdfLabel = document.getElementById('taskFilePdfLabel');

                taskFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    taskPreviewImg.src = '#';
                    taskPreviewImg.style.display = 'none';
                    taskPdfLabel.style.display = 'none';

                    if (!file) {
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        toastr.error('Ukuran file maksimal 2MB');
                        taskFileInput.value = '';
                        return;
                    }

                    const allowedTypes = ['image/jpeg', 'application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        toastr.error('File harus berformat JPG atau PDF');
                        taskFileInput.value = '';
                        return;
                    }

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            taskPreviewImg.src = ev.target.result;
                            taskPreviewImg.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        taskPdfLabel.style.display = 'block';
                    }
                });
            });

            $('#activityTaskTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ URL::to('aktivitas/get-activity-task/' . Crypt::encrypt($activity->activity_id)) }}",
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                },
                columns: [{
                        data: 'tgl_setor',
                        name: 'tgl_setor',
                        className: 'text-center'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });

            $('#btnAddActivityTask').click(function() {
                $('#activityTaskForm').trigger('reset');
                $('#taskFilePreview').hide().attr('src', '#');
                $('#taskFilePdfLabel').hide();
                $('#activityTaskModal').modal('show');
            });

            $('#activityTaskForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ URL::to('aktivitas/store-activity-task') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(response) {
                        $('#activityTaskModal').modal('hide');
                        $('#activityTaskTable').DataTable().ajax.reload();
                        toastr.success(response.message || 'Tugas berhasil diunggah');
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Gagal mengunggah tugas');
                    }
                });
            });

            $(document).on('click', '.delete-task', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menghapus tugas ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ URL::to('aktivitas/delete-activity-task') }}",
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(response) {
                                $('#activityTaskTable').DataTable().ajax.reload();
                                toastr.success(response.message || 'Tugas berhasil dihapus');
                            },
                            error: function(xhr) {
                                toastr.error(xhr.responseJSON?.message || 'Gagal menghapus tugas');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
