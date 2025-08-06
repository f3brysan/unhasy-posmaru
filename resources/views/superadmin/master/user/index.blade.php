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
@endsection
@push('js')
    <script src="{{ URL::to('/') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                        success: function (response) {
                            console.log(response);
                            
                        }
                    });
                    // Swal.fire({
                    //     title: "Deleted!",
                    //     text: "Your file has been deleted.",
                    //     icon: "success"
                    // });
                }
            });
        });
    </script>
@endpush
