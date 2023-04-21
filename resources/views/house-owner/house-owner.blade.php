@extends('admin.layout.app')
@section('title')
    Owner Management | {{ getGuard() }}
@endsection('title')

@section('page-styles')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection('page-styles')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                @include('house-owner.house-owner-add')
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                @include('house-owner.house-owner-list')
            </div>
        </div>
    </div>
@endsection('content')

@section('page-script')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
@endsection('page-script')

@section('script')
    <script>
        $(document).ready(function () {
            ownerList()
        });


        function ownerList() {
            var table = $('#owner-list-table').DataTable({
                "processing": true,
                "serverSide": true,
                paging: true,
                "searching": true,
                "ordering": false,
                "info": true,
                "lengthChange": true,
                "bProcessing": true,
                "bServerSide": true,
                "destroy": true,
                "sAjaxSource": "{{url('/')}}/house-owner/list",
                columns: [
                    {data: "id"},
                    {data: "name"},
                    {data: "email"},
                    {data: "action"},
                ]
            });
        }
    </script>

@endsection('script')




