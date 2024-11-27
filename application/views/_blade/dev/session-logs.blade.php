@extends('_blade/_template/default')
@section('content')




        <div id="mainContent" class="homepage">
            <table id="table-post" class="table" style="max-width:100%;">
                <thead>
                <th style="width:50%;">Url</th>
                <th>Count</th>
                <th>Milliseconds</th>
                <th>seconds</th>
                </thead>
            </table>
        </div>


    <script>
        $('#table-post').DataTable({
            processing: true,
            serverSide: true,
            'pageLength': 50,
            'lengthChange': true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                url: '/benchmark/ajax/', // Change with your own
                method: 'GET', // You are freely to use POST or GET
            }
        })
    </script>
@endsection