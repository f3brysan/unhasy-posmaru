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
@endsection
@push('js')
    <script src="{{ URL::to('/') }}/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#myTable').DataTable({
            scrollX: true,
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ URL::to('daftar-kegiatan') }}",
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
                
    </script>
@endpush
