import { getPreselectOptions } from "../DatatableConfigOptions.js";

const getEmailTemplateLogs = function(columns){
    return {
        ajax: {
            url: "/email/datatable_email_logs",
            type: "POST",
            dataType: "json"
        },
        processing: true,
        // serverSide: true,
        // deferRender: true,
        columns: [
            { data: "details" },
            { data: "name" },
            { data: "created_date" }
        ],
        ordering: true,
        order: [[ 2, "desc" ]],
        layout: 'columns-3',
        columndefs: [
            { searchPanes: { show: false }, targets: [0,2] },
            ...Array.from({ length: columns.totalColumns }, (_, i) => ({
                searchPanes: {
                    show: true,
                    initCollapsed: true
                },
                targets: [i]
            })),
        ],
        buttons: [],
        customInit: () => {
            // Hide searchPanes
            jQuery('#datatable_filter').hide();

            //add datatables column class and remove the previous datatable layout
            let layout = jQuery(".dtsp-searchPanes .dtsp-searchPane");
            layout.eq(1).addClass('dtsp-columns-3');
            layout.eq(1).removeClass('dtsp-columns-1');
        }
    };
}

export {
    getEmailTemplateLogs
}