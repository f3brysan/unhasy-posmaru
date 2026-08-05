@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card mb-4">
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
                                    <div>{{ \Carbon\Carbon::parse($activity->registration_start_date)->format('d F Y') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Batas Akhir Pendaftaran:</strong>
                                    <div>{{ \Carbon\Carbon::parse($activity->registration_end_date)->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Batas Awal Absensi:</strong>
                                    <div>{{ $activity->student_report_start }}</div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Batas Akhir Absensi:</strong>
                                    <div>{{ $activity->student_report_end }}</div>
                                </div>
                            </div> --}}
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
                                <div class="col-md-4">
                                    <strong>Ukuran Font:</strong>
                                    <div>{{ $activity->font_size }}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Kordinat X:</strong>
                                    <div>{{ $activity->x_coordinate }}</div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Kordinat Y:</strong>
                                    <div>{{ $activity->y_coordinate }}</div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Data kegiatan tidak ditemukan.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @hasrole('superadmin|baak')
                            <a href="javascript:void(0)" class="btn btn-primary float-end" id="btnEditActivity">Edit
                                Kegiatan</a>
                        @endhasrole
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Sesi Kegiatan</h4>
                    </div>
                    <div class="card-body">
                        @hasrole('superadmin|baak')
                            <div class="d-flex justify-content-end mb-3">
                                <button class="btn btn-primary" id="btnAddActivitySession">Tambah Sesi</button>
                            </div>
                        @endhasrole
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="activitySessionsTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Sesi</th>
                                        <th class="text-center">Waktu Mulai</th>
                                        <th class="text-center">Waktu Selesai</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Peserta</h4>
                        @hasrole('superadmin|baak|panitia')
                            <button class="btn btn-primary" id="btnAddParticipant"
                                data-activity-id="{{ $activity->id ?? '' }}">Tambah
                                Peserta</button>
                        @endhasrole
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="participantsTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Prodi/Fakultas</th>
                                        <th class="text-center">Total Laporan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Laporan Kegiatan</h4>
                    </div>
                    @php
                        // Ambil tanggal mulai dan akhir event
                        $startDate = \Carbon\Carbon::parse($activity->activity_start_date ?? null);
                        $endDate = \Carbon\Carbon::parse($activity->activity_end_date ?? null);

                        $dates = [];
                        if ($startDate && $endDate && $startDate->lte($endDate)) {
                            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                                $dates[] = $date->format('Y-m-d');
                            }
                        }
                    @endphp

                    <div class="card-body">
                        @if (count($dates))
                            @php
                                $currentDate = now()->format('Y-m-d');
                                $activeIndex = array_search($currentDate, $dates);
                                if ($activeIndex === false) {
                                    $activeIndex = 0; // Default to first tab if current date not found
                                }
                            @endphp
                            <ul class="nav nav-tabs" id="reportDateTabs" role="tablist">
                                @foreach ($dates as $i => $date)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if ($i == $activeIndex) active @endif"
                                            id="tab-{{ $date }}" data-bs-toggle="tab"
                                            data-bs-target="#tab-pane-{{ $date }}" type="button" role="tab"
                                            aria-controls="tab-pane-{{ $date }}"
                                            aria-selected="{{ $i == $activeIndex ? 'true' : 'false' }}">
                                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content mt-3" id="reportDateTabsContent">
                                @foreach ($dates as $i => $date)
                                    <div class="tab-pane fade @if ($i == $activeIndex) show active @endif"
                                        id="tab-pane-{{ $date }}" role="tabpanel"
                                        aria-labelledby="tab-{{ $date }}">
                                        {{-- Konten laporan untuk tanggal {{ $date }} --}}

                                        @if (!empty($reports[$date]))
                                            <table class="table table-bordered table-striped data-table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">NIM</th>
                                                        <th class="text-center">Nama</th>
                                                        <th class="text-center">Prodi/Fakultas</th>
                                                        <th class="text-center">Bukti Kehadiran</th>
                                                        <th class="text-center">Bukti Tugas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($reports[$date] as $report)
                                                        <tr>
                                                            <td class="text-center">{{ $report->user->no_induk }}</td>
                                                            <td>{{ $report->user->name }}</td>
                                                            <td class="text-center">
                                                                {{ $report->user->biodata->prodi->prodi }}
                                                                <br>
                                                                Fakultas{{ $report->user->biodata->fakultas->fakultas }}
                                                            </td>
                                                            <td class="text-center">

                                                                @if (!empty($fileReports[$date][$report->user->id]))
                                                                    @foreach ($fileReports[$date][$report->user_id] as $file)
                                                                        <a href="{{ asset($file->picture) }}"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-primary"><i
                                                                                class="fa fa-eye"></i>&nbsp;Lihat</a>
                                                                        <p class="small mt-2">
                                                                            {{ Carbon\Carbon::parse($report->updated_at)->format('d M Y H:i') }}
                                                                        </p>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if (!empty($fileTasks[$date][$report->user_id]))
                                                                    @foreach ($fileTasks[$date][$report->user_id] as $task)
                                                                        <a href="{{ asset($task->picture) }}"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-primary"><i
                                                                                class="fa fa-eye"></i>&nbsp;Lihat</a>
                                                                    @endforeach
                                                                    <p class="small mt-2">
                                                                        {{ Carbon\Carbon::parse($task->tgl_setor)->format('d M Y H:i') }}
                                                                    </p>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="alert alert-warning text-center">
                                                <i class="fa fa-exclamation-triangle"></i> Belum ada data untuk ditampilkan
                                                pada tanggal
                                                <strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong>.
                                            </div>
                                        @endif

                                        {{-- Anda bisa menampilkan data laporan per tanggal di sini --}}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Tanggal kegiatan tidak tersedia.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Add Participant --}}
    <div class="modal fade" id="addParticipantModal" tabindex="-1" aria-labelledby="addParticipantModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addParticipantModalLabel">Tambah Peserta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form id="formAddParticipant">
                    <div class="modal-body">
                        <input type="hidden" name="activity_id" value="{{ Crypt::encrypt($activity->id) }}">
                        <div class="mb-3">
                            <label for="participant_nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="participant_nim" name="nim" required>
                        </div>
                        <div class="mb-3">
                            <label for="participant_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="participant_name" name="name" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Add Participant --}}

    {{-- Modal CRUD --}}
    <div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crudModalLabel">Edit Kegiatan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formKegiatan">
                    <input type="hidden" name="id" id="id" value="{{ $activity->id }}">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="name">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $activity->name }}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="year">Tahun</label>
                            <select name="year" id="year" class="form-control form-select" required>
                                <option value="">Silahkan Pilih</option>
                                @for ($year = date('Y'); $year > date('Y') - 5; $year--)
                                    <option value="{{ $year }}" {{ $activity->year == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label for="registration_start_date">Tanggal Awal Pendaftaran</label>
                                    <input type="date" class="form-control" id="registration_start_date"
                                        name="registration_start_date" value="{{ $activity->registration_start_date }}"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label for="registration_end_date">Tanggal Akhir Pendaftaran</label>
                                    <input type="date" class="form-control" id="registration_end_date"
                                        name="registration_end_date" value="{{ $activity->registration_end_date }}"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label for="activity_start_date">Tanggal Mulai Kegiatan</label>
                                    <input type="date" class="form-control" id="activity_start_date"
                                        name="activity_start_date" value="{{ $activity->activity_start_date }}" required>
                                </div>
                                <div class="col-6">
                                    <label for="activity_end_date">Tanggal Selesai Kegiatan</label>
                                    <input type="date" class="form-control" id="activity_end_date"
                                        name="activity_end_date" value="{{ $activity->activity_end_date }}" required>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label for="student_report_start">Batas Awal Absensi</label>
                                    <input type="text" class="form-control timepicker" id="student_report_start"
                                        name="student_report_start" value="{{ $activity->student_report_start }}"
                                        required>
                                </div>
                                <div class="col-6">
                                    <label for="student_report_end">Batas Akhir Absensi</label>
                                    <input type="text" class="form-control timepicker" id="student_report_end"
                                        name="student_report_end" value="{{ $activity->student_report_end }}" required>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-4">
                                    <label for="certificate_template">Kordinat X</label>
                                    <input type="text" class="form-control" id="coordinate_x" name="coordinate_x"
                                        value="{{ $activity->x_coordinate }}">
                                </div>
                                <div class="col-4">
                                    <label for="certificate_template">Kordinat Y</label>
                                    <input type="text" class="form-control" id="coordinate_y" name="coordinate_y"
                                        value="{{ $activity->y_coordinate }}">
                                </div>
                                <div class="col-4">
                                    <label for="certificate_template">Font Size</label>
                                    <input type="text" class="form-control" id="font_size" name="font_size"
                                        value="{{ $activity->font_size }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="link_wag">Link Grup WA</label>
                            <textarea class="form-control" id="link_wag" name="link_wag" rows="3">{{ $activity->link_wag }}</textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="status">Template Sertifikat</label>
                            <input type="file" class="form-control" id="certificate_template"
                                name="certificate_template">
                        </div>
                        <div class="form-group mb-2">
                            <div class="mb-2">
                                <label for="certificate_preview" class="form-label">Preview Sertifikat (JPG)</label>
                                <div id="certificate_preview_container"
                                    style="{{ $activity->bg_certificate ? 'display:block' : 'display:none' }}">
                                    <img id="certificate_preview" src="{{ asset($activity->bg_certificate) ?? '#' }}"
                                        alt="Preview Sertifikat" class="img-fluid border" style="max-height:200px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal CRUD --}}

    <!-- Modal Participant -->
    <div class="modal fade" id="modalParticipant" tabindex="-1" aria-labelledby="modalParticipantLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formParticipant">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalParticipantLabel">Edit Peserta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="participant_name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="participant_name_edit"
                                name="participant_name_edit" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="uniform_size" class="form-label">Ukuran Seragam</label>
                            <select class="form-select" id="uniform_size" name="uniform_size" required>
                                <option value="" selected disabled>Pilih Ukuran</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">XXXL</option>
                                <option value="XXXXL">XXXXL</option>
                                <option value="XXXXXL">XXXXXL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Participant -->

    {{-- Modal Add Activity Session --}}
    <div class="modal fade" id="addActivitySessionModal" tabindex="-1" aria-labelledby="addActivitySessionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addActivitySessionModalLabel">Tambah Sesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formActivitySession">
                    @csrf
                    <input type="hidden" name="id" id="id_activity_session">
                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Sesi</label>
                            <input type="text" class="form-control" id="name_activity_session" name="name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="student_report_start" class="form-label">Waktu Mulai</label>
                            <input type="text" class="form-control timepicker"
                                id="student_report_start_activity_session" name="student_report_start" required>
                        </div>
                        <div class="mb-3">
                            <label for="student_report_end" class="form-label">Waktu Selesai</label>
                            <input type="text" class="form-control timepicker"
                                id="student_report_end_activity_session" name="student_report_end" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Add Activity Session --}}
@endsection
@push('js')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.timepicker').timepicker({
                zindex: 9999999,
                timeFormat: 'HH:mm',
                interval: 30,
                minTime: '00:00',
                maxTime: '23:59',
                defaultTime: '08:00',
                startTime: '00:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            $('.data-table').DataTable({
                responsive: true,
            });

            $('#participantsTable').DataTable({
                scrollX: true,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ URL::to('kegiatan/participants/' . Crypt::encrypt($activity->id)) }}",
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                },
                columns: [{
                        data: 'nim',
                        name: 'nim',
                        className: 'text-center'
                    },
                    {
                        data: 'faculty',
                        name: 'faculty',
                        className: 'text-center'
                    },
                    {
                        data: 'total_report',
                        name: 'total_report',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    }
                ]
            });

            $('#activitySessionsTable').DataTable({
                scrollX: true,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ URL::to('kegiatan/activity-sessions/' . Crypt::encrypt($activity->id)) }}",
                    type: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name',
                        className: 'text-center'
                    },
                    {
                        data: 'student_report_start',
                        name: 'student_report_start',
                        className: 'text-center'
                    },
                    {
                        data: 'student_report_end',
                        name: 'student_report_end',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    }
                ]
            });

            $('#btnAddParticipant').on('click', function() {
                $('#addParticipantModal').modal('show');
            });

            $('#formAddParticipant').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    url: "{{ url('kegiatan/add-participant') }}",
                    method: "POST",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        console.log(res);
                        toastr.success(res.message);
                        $('#addParticipantModal').modal('hide');
                        $('#formAddParticipant')[0].reset();
                        $('#participantsTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $('#formActivitySession').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    url: "{{ url('kegiatan/store-activity-session') }}",
                    method: "POST",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        console.log(res);
                        toastr.success(res.message);
                        $('#addActivitySessionModal').modal('hide');
                        $('#formActivitySession')[0].reset();
                        $('#activitySessionsTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $('#participant_nim').on('keyup', function() {
                var nim = $(this).val();
                $('#participant_name').val('');

                // Clear previous timeout if it exists
                if (typeof this.delayTimer !== 'undefined') {
                    clearTimeout(this.delayTimer);
                }

                // Set new timeout
                this.delayTimer = setTimeout(() => {
                    $.ajax({
                        url: "{{ url('master/pengguna/get-participant') }}",
                        method: "POST",
                        data: {
                            nim: nim
                        },
                        dataType: "JSON",
                        success: function(res) {
                            if (res.status == 'success') {
                                $('#participant_name').val(res.data.name);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseJSON);
                            $('#participant_name').val('');
                            toastr.error(xhr.responseJSON.message);
                        }
                    });
                }, 1000);
            });

            $('#btnEditActivity').on('click', function() {
                $('#editActivityModal').modal('show');
            });

            $('#btnAddActivitySession').on('click', function() {
                $('#formActivitySession')[0].reset();
                $('#addActivitySessionModal').modal('show');
            });

            $("#formKegiatan").on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $('#btnSubmit').html('Saving..');
                $('#btnSubmit').attr('disabled', true);

                $.ajax({
                    type: "POST",
                    url: "{{ URL::to('kegiatan/store') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);
                        toastr.success(response.message, 'Success!');
                        $('#crudModal').modal('hide');
                        $('#btnSubmit').html('Save Changes');
                        $('#btnSubmit').attr('disabled', false);
                        $('#formKegiatan')[0].reset();
                        $('#editActivityModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON.message);
                        toastr.error(xhr.responseJSON.message, 'Oops!');
                        $('#btnSubmit').html('Save Changes');
                        $('#btnSubmit').attr('disabled', false);
                    }
                });
            });

            $(document).on('click', '.edit', function() {
                $('#formParticipant')[0].reset();
                let id = $(this).data('id');
                console.log(id);
                $.ajax({
                    url: "{{ URL::to('kegiatan/edit-participant') }}",
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    data: {
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('#nim').val(response.data.nim);
                        $('#participant_name_edit').val(response.data.participant_name);
                        $('#uniform_size').val(response.data.uniform_size);
                        $('#phone_number').val(response.data.hp);
                        $('#gender').val(response.data.gender);
                        $('#modalParticipant').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $('#formParticipant').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();
                $.ajax({
                    url: "{{ URL::to('kegiatan/update-participant') }}",
                    method: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(response) {
                        toastr.success(response.message, 'Success!');
                        $('#modalParticipant').modal('hide');
                        $('#formParticipant')[0].reset();
                        $('#participantsTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('click', '.amnesti', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin ingin memberikan amnesti?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Berikan Amnesti',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ URL::to('kegiatan/amnesti') }}",
                            type: 'POST',
                            data: {
                                id: id
                            },
                            dataType: "JSON",
                            success: function(response) {
                                toastr.success(response.message, 'Success!');
                                $('#participantsTable').DataTable().ajax.reload(null,
                                    false);
                            },
                            error: function(xhr, status, error) {
                                toastr.error(xhr.responseJSON.message, 'Oops!');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.edit-activity-session', function() {
                $('#formActivitySession')[0].reset();
                let id = $(this).data('id');
                console.log(id);
                $.ajax({
                    url: "{{ URL::to('kegiatan/edit-activity-session') }}",
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('#name_activity_session').val(response.data.name);
                        $('#student_report_start_activity_session').val(response.data
                            .student_report_start);
                        $('#student_report_end_activity_session').val(response.data
                            .student_report_end);
                        $('#id_activity_session').val(response.data.id);
                        $('#addActivitySessionModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseJSON);
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });

            $(document).on('click', '.delete-activity-session', function() {
                let id = $(this).data('id');
                console.log(id);
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menghapus sesi ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ URL::to('kegiatan/delete-activity-session') }}",
                            type: 'POST',
                            data: {
                                id: id
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                toastr.success(response.message, 'Success!');
                                $('#activitySessionsTable').DataTable().ajax.reload(
                                    null, false);
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseJSON);
                                toastr.error(xhr.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('certificate_template');
            const previewContainer = document.getElementById('certificate_preview_container');
            const previewImg = document.getElementById('certificate_preview');
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type === 'image/jpeg') {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        previewImg.src = ev.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewImg.src = '#';
                    previewContainer.style.display = 'none';
                }
            });
        });
    </script>
@endpush
