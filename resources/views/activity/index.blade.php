@extends('layouts.main')
@push('css')
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ URL::to('/') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ URL::to('/') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5>Data Kegiatan Posmaru</h5>
            </div>
            <div class="card-body">
                <button class="btn btn-primary" id="tambahKegiatan">Tambah</button>
            </div>
            <div class="card-datatable table table-responsive pt-0">
                <table class="datatables-basic table table-responsive" id="myTable">
                    <thead>
                        <tr>
                            <th>Nama Kegiatan</th>
                            <th>Tahun</th>
                            <th>Peserta</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal CRUD --}}
    <div class="modal fade" id="crudModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="crudModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formKegiatan">
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="name">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="year">Tahun</label>
                            <select name="year" id="year" class="form-control form-select" required>
                                <option value="">Silahkan Pilih</option>
                                @for ($year = date('Y'); $year > date('Y') - 5; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label for="registration_start_date">Tanggal Awal Pendaftaran</label>
                                    <input type="date" class="form-control" id="registration_start_date"
                                        name="registration_start_date" required>
                                </div>
                                <div class="col-6">
                                    <label for="registration_end_date">Tanggal Akhir Pendaftaran</label>
                                    <input type="date" class="form-control" id="registration_end_date"
                                        name="registration_end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label for="activity_start_date">Tanggal Mulai Kegiatan</label>
                                    <input type="date" class="form-control" id="activity_start_date"
                                        name="activity_start_date" required>
                                </div>
                                <div class="col-6">
                                    <label for="activity_end_date">Tanggal Selesai Kegiatan</label>
                                    <input type="date" class="form-control" id="activity_end_date"
                                        name="activity_end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="row">
                                <div class="col-6">
                                    <label for="student_report_start">Batas Awal Absensi</label>
                                    <input type="text" class="form-control timepicker" id="student_report_start"
                                        name="student_report_start" required>
                                </div>
                                <div class="col-6">
                                    <label for="student_report_end">Batas Akhir Absensi</label>
                                    <input type="text" class="form-control timepicker" id="student_report_end"
                                        name="student_report_end" required>
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
@endsection
@push('js')
    <script src="{{ URL::to('/') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <script>
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

        $('#myTable').DataTable({
            scrollX: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ URL::to('kegiatan') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'year',
                    name: 'year',
                    className: 'text-center'

                },
                {
                    data: 'peserta',
                    name: 'peserta',
                    className: 'text-center'

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ],
            order: [
                [0, 'asc']
            ]
        });

        $('#tambahKegiatan').on('click', function() {
            $('#crudModal').modal('show');
        });

        $("#formKegiatan").on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $('#btnSubmit').html('Saving..');
            $('#btnSubmit').attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ URL::to('kegiatan/store') }}",
                data: formData,
                dataType: "JSON",
                success: function (response) {
                    console.log(response);                                        
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseJSON.message);
                    toastr.error(xhr.responseJSON.message, 'Oops!');
                    $('#btnSubmit').html('Save Changes');
                    $('#btnSubmit').attr('disabled', false);
                }
            });
        });
    </script>
@endpush
