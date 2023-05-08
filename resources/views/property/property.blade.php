@extends('layout.app')
@section('title')
    Property Management | {{ getGuard() }}
@endsection('title')

@section('page-styles')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection('page-styles')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                <div class="col-md-10">
                    <h4 class="m-0 font-weight-bold text-primary">{{ __('main_table.property.table_title') }}</h4>
                </div>
                @include('property.property-add')
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                @include('property.property-list')
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
            propertyrList();
            $('.house-owner').select2({
                dropdownParent: $('#houseOwnerModal')
            });
        });


        function propertyrList() {
            var table = $('#property-list-table').DataTable({
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
                "sAjaxSource": "{{url('/')}}/{{app()->getLocale()}}/property/list",
                columns: [
                    {data: "id"},
                    {data: "title"},
                    {data: "house_owner"},
                    {data: "description"},
                    {data: "action"},
                ]
            });
        }
    </script>

@endsection('script')




