@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mb-4">
            <div class="col-md-4">
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
                                    <div>{{ \Carbon\Carbon::parse($activity->registration_start_date)->format('d F Y') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Batas Akhir Pendaftaran:</strong>
                                    <div>{{ \Carbon\Carbon::parse($activity->registration_end_date)->format('d F Y') }}
                                    </div>
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
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Peserta</h4>
                        <button class="btn btn-primary" id="btnAddParticipant"
                            data-activity-id="{{ $activity->id ?? '' }}">Tambah
                            Peserta</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="participantsTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">NIM</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Prodi/Fakultas</th>
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
                                                        <th class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($reports[$date] as $report)
                                                        <tr>
                                                            <td class="text-center">{{ $report->user->no_induk }}</td>
                                                            <td>{{ $report->user->name }}</td>
                                                            <td class="text-center">{{ $report->user->biodata->prodi->prodi }}
                                                            <br>
                                                            Fakultas{{ $report->user->biodata->fakultas->fakultas }}
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ asset($report->picture) }}" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i>&nbsp;Lihat</a>
                                                                <p class="small mt-2">{{ Carbon\Carbon::parse($report->updated_at)->format('d M Y H:i') }}</p>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary">Edit</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="alert alert-warning text-center">
                                               <i class="fa fa-exclamation-triangle"></i> Belum ada data untuk ditampilkan pada tanggal
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
@endsection
@push('js')
            <script>
                $(document).ready(function() {
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
                            type: 'GET'
                        },
                        columns: [{
                                data: 'nim',
                                name: 'nim',
                                className: 'text-center'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'faculty',
                                name: 'faculty',
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

                });
            </script>
        @endpush