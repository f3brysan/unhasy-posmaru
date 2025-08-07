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
                            <th>Status</th>
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
                    <input type="hidden" name="id" id="id">
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
                    data: 'status_btn',
                    name: 'status_btn',
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
            ],
            order: [
                [0, 'asc']
            ]
        });

        $('#tambahKegiatan').on('click', function() {
            $('#crudModalLabel').text('Tambah Kegiatan');
            $('#formKegiatan')[0].reset();
            $('#crudModal').modal('show');
        });        
        
        $(document).on('click', '.change-status', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            var $btn = $(this);

            if (status == 0) {
                var text = "Anda akan mengubah status kegiatan ini menjadi non aktif.";
            } else {
                var text = "Anda akan mengubah status kegiatan ini menjadi aktif.";
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lanjutkan eksekusi AJAX di bawah
                    $.ajax({
                        url: "{{ URL::to('kegiatan/change-status') }}",
                        type: "POST",
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(response) {
                            $('#myTable').DataTable().ajax.reload(null, false);
                            toastr.success(response.message, 'Success!');
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                                .responseJSON.message : 'Terjadi kesalahan';
                            toastr.error(msg, 'Oops!');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).data('id');
        $.ajax({
            url: "{{ URL::to('kegiatan/edit') }}", 
            type: "POST",
            data: {
                id: id,                                
            },
            dataType: "JSON",
            success: function(response) {
                var data = response.data;
                $('#crudModalLabel').text('Edit Kegiatan');
                $('#formKegiatan')[0].reset();
                $('#formKegiatan').find('[name="id"]').val(data.id);
                $('#formKegiatan').find('[name="name"]').val(data.name);
                $('#formKegiatan').find('[name="year"]').val(data.year);
                $('#formKegiatan').find('[name="registration_start_date"]').val(data.registration_start_date);
                $('#formKegiatan').find('[name="registration_end_date"]').val(data.registration_end_date);
                $('#formKegiatan').find('[name="activity_start_date"]').val(data.activity_start_date);
                $('#formKegiatan').find('[name="activity_end_date"]').val(data.activity_end_date);
                $('#formKegiatan').find('[name="student_report_start"]').val(data.student_report_start);
                $('#formKegiatan').find('[name="student_report_end"]').val(data.student_report_end);
                $('#formKegiatan').find('[name="id"]').val(data.id);
                $('#crudModal').modal('show');
            },
            error: function(xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan';
                toastr.error(msg, 'Oops!');
            }
        });
        });

        $("#formKegiatan").on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $('#btnSubmit').html('Saving..');
            $('#btnSubmit').attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ URL::to('kegiatan/store') }}",
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    toastr.success(response.message, 'Success!');
                    $('#crudModal').modal('hide');
                    $('#btnSubmit').html('Save Changes');
                    $('#btnSubmit').attr('disabled', false);
                    $('#formKegiatan')[0].reset();
                    $('#myTable').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON.message);
                    toastr.error(xhr.responseJSON.message, 'Oops!');
                    $('#btnSubmit').html('Save Changes');
                    $('#btnSubmit').attr('disabled', false);
                }
            });
        });
    </script>
@endpush
