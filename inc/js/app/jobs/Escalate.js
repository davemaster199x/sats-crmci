import { getPreselectOptions } from "../DatatableConfigOptions.js";

const getEscalateJobs = function(columns){
    return {
        ordering: true,
        // columnOrder: ['Agency', 'Phone'],
        preselect:getPreselectOptions(columns.preSelectColumns),
        columndefs: [
            ...Array.from({ length: columns.totalColumns }, (_, i) => ({
                searchPanes: {
                    show: false
                },
                targets: [i]
            })),
            { orderable: false, targets: [3,4,5,7,8] },
        ],
        //@TODO change this to dynamic datatables in the future
        // columndefs: [
        //     { searchPanes: { show: false }, targets: [0] },
        //     { searchPanes: { show: false }, targets: [9] },
        //     { searchPanes: { show: true, initCollapsed: true }, visible:false, targets: [10] },
        //     { searchPanes: { show: true, initCollapsed: true }, targets: [1] },
        //     { searchPanes: { show: true, initCollapsed: true }, targets: [2] }
        // ],
        buttons: [],
        customInit: () => {
            // Hide searchpanes
            jQuery('#datatable_filter').hide();
        }
    };
}


export {
    getEscalateJobs
}