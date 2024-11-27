<div class="box-typical box-typical-padding">

    <?php

    // breadcrumbs template
    $bc_items = array(
        array(
            'title' => $title,
            'status' => 'active',
            'link' => "/benchmark/index"
        )
    );
    $bc_data['bc_items'] = $bc_items;
    $this->load->view('templates/breadcrumbs', $bc_data);
    ?>

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