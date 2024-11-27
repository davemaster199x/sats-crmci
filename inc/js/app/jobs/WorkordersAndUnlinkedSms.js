import { getPreselectOptions } from "../DatatableConfigOptions.js";

const getWorkOrdersAndUnlinkedSms = function(columns) {
    return {
        ordering: true,
        preselect: getPreselectOptions(columns.preSelectColumns, ['Yes']),
        columndefs: [
            ...Array.from({ length: columns.totalColumns }, (_, i) => ({
                searchPanes: { show: true },
                targets: [i]
            })),{
                targets: [6],
                orderable: false
            },{
                targets: '_all',
                className: 'text-left dt-left'
            }
        ],
        buttons: [],
        customInit: () => {
            jQuery('.dtsp-panesContainer').hide();
        }
    };
};

export {
    getWorkOrdersAndUnlinkedSms
}