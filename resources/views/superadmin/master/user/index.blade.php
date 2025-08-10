@extends('layouts.main')
@push('css')
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ URL::to('/') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ URL::to('/') }}/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pengguna</h5>
                <button class="btn btn-primary" id="btnAddUser">Tambah Pengguna</button>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table" id="myTable">
                    <thead>
                        <tr>
                            <th class="text-center">No Induk</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Peran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddUser" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddUserLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAddUser">
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_induk">No Induk</label>
                                    <input type="text" class="form-control" id="no_induk" name="no_induk" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Peran</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">Pilih Peran</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ URL::to('/') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4"></script>
    <script>
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ URL::to('master/pengguna') }}",
                type: 'GET'
            },
            columns: [{
                    data: 'no_induk',
                    name: 'no_induk',
                    className: 'text-center'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'role',
                    name: 'role',
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

        $('#btnAddUser').click(function() {
            $('#formAddUser')[0].reset();
            $('#modalAddUser').modal('show');
        });

        $('#formAddUser').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: "{{ URL::to('master/pengguna/store') }}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $('#modalAddUser').modal('hide');
                        $('#formAddUser')[0].reset();
                        $('#myTable').DataTable().ajax.reload();
                        toastr.success(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                    toastr.error(xhr.responseJSON.message);
                }
            });
        });

        $(document).on('click', '.editUser', function() {
            $('#id').val($(this).data('id'));
            $.ajax({
                url: "{{ URL::to('master/pengguna/edit') }}",
                type: "POST",
                data: {
                    id: $(this).data('id'),
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    $('#no_induk').val(response.data.no_induk);
                    $('#name').val(response.data.name);
                    $('#role').val(response.data.roles[0].name);
                    $('#modalAddUser').modal('show');
                }
            });            
        });

        $(document).on('click', '.resetPassword', function() {
            Swal.fire({
                title: "Reset Password?",
                text: "Apakah anda yakin ingin mereset password ini?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, reset !"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ URL::to('master/pengguna/reset-password') }}",
                        data: {
                            id: $(this).data('id'),
                        },
                        dataType: "JSON",
                        success: function(response) {
                            Swal.fire({
                                title: "Sukses!",
                                text: response.message,
                                icon: "success"
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.loginAs', function() {
            var name = $(this).data('name');
            Swal.fire({
                title: "Login As",
                text: "Apakah anda yakin ingin melakukan login sebagai " + name + "?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, login !"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ URL::to('login-as') }}",
                        data: {
                            id: $(this).data('id'),
                        },
                        dataType: "JSON",
                        success: function(response) {
                            Swal.fire({
                                title: "Sukses!",
                                text: response.message,
                                icon: "success"
                            });

                            setTimeout(function() {
                                window.location.href = "{{ URL::to('beranda') }}";
                            }, 1000);
                        }
                    });
                }
            });
        });
    </script>
@endpush
