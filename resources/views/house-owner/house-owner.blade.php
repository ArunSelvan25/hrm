@extends('layout.app')
@section('title')
    Owner Management | {{ getGuard() }}
@endsection('title')

@section('page-styles')
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    	
<link href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/datatables.min.css" rel="stylesheet" />
@endsection('page-styles')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                <div class="col-md-10">
                    <h4 class="m-0 font-weight-bold text-primary">{{ __('main_table.house_owner.table_title') }}</h4>
                </div>
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
     
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/datatables.min.js"></script>
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
                dom: 'lfBtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        },
                        className: "white",
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2]
                        }
                    },
                    {
                        extend: 'colvis',
                        collectionLayout: 'fixed columns',
                        collectionTitle: 'Column visibility control'
                    },
                    {
                extend: 'colvisGroup',
                text: 'Name info',
                show: [ 0, 1 ],
                hide: [ 2, 3 ]
            },
            {
                extend: 'colvisGroup',
                text: 'Eamil info',
                show: [ 0, 2],
                hide: [ 1, 3 ]
            },
            {
                extend: 'colvisGroup',
                text: 'Show all',
                show: ':hidden'
            }
                ],
                "sAjaxSource": "{{url('/')}}/{{app()->getLocale()}}/house-owner/list",
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




