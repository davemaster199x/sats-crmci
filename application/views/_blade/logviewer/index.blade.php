@extends('_blade/_template/default')
@section('content')
    {{$html}}
@endsection
@section('scripts')
<script>
    $(document).ready(function () {

        $('.table-container tr .expand').on('click', function () {
            $('#' + $(this).data('display')).toggle();
        });

        $('#table-log').DataTable({
            "order": [
                [1, 'desc']
            ],
            "pageLength": "50",
            "lengthMenu": [
                [50,100,250,500, -1],
                [50,100,250,500, 'All']
            ],
            "columnDefs": [
                {
                    "targets": 0,
                    "orderable": false
                },
                {
                    "targets": 2,
                    "orderable": false
                },
                {
                    "targets": 3,
                    "orderable": false
                },
            ],
            "stateSave": true,
            "stateSaveCallback": function (settings, data) {
                window.localStorage.setItem("datatable", JSON.stringify(data));
            },
            "stateLoadCallback": function (settings) {
                var data = JSON.parse(window.localStorage.getItem("datatable"));
                if (data) data.start = 0;
                return data;
            }
        });
        $('#delete-log, #delete-all-log').click(function () {
            return confirm('Are you sure?');
        });
    });
</script>
@endsection