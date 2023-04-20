@extends('admin.layout.app')
@section('title') Roles And Permission | {{ getGuard() }} @endsection('title')
@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                Add Role
            </div>
        </div>



        <div class="card-body">
            <div class="table-responsive">
                @include('roles-and-permission.roles-permission-list')
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

        });



        function roleList()
        {
            var table  = $('#role-list-table').DataTable( {
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
                "sAjaxSource": "{{url('/')}}/role-permission/list",
                columns: [
                    { data: "role" },
                    { data: "permission" },
                    { data: "action" },
                ]
            } );

            // $('.select2').select2({
            //     dropdownParent: $('#changePermission-1')
            // });
        }
    </script>

@endsection('script')
