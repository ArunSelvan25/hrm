@extends('layout.app')
@section('title') Tenant Management | {{ getGuard() }} @endsection('title')

@section('page-styles')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection('page-styles')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                @include('tenant.tenant-add')
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                @include('tenant.tenant-list')
            </div>
        </div>
    </div>
@endsection('content')

@section('page-script')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

{{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>--}}

{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>--}}

{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
@endsection('page-script')

@section('script')
    <script>
        $(document).ready(function() {
            tenantList();
            $('.property-select').select2({
                dropdownParent: $('#tenantModal')
            });
        });


        function tenantList()
        {
            var table  = $('#tenant-list-table').DataTable( {
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
                "sAjaxSource": "{{url('/')}}/{{app()->getLocale()}}/tenant/list",
                columns: [
                    { data: "id" },
                    { data: "property_name" },
                    { data: "name" },
                    { data: "email" },
                    { data: "phone" },
                    { data: "action" },
                ]
            } );
        }
    </script>

@endsection('script')




