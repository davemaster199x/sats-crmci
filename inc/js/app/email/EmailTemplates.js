import { getPreselectOptions } from "../DatatableConfigOptions.js";

/**
 * Email Template Datatable Options
 * @param columns
 * @returns {{preselect: {column, rows: *[]}[], columndefs: [{searchPanes: {show: boolean}, targets: number[]},{searchPanes: {show: boolean}, targets: number[]},{searchPanes: {show: boolean}, targets: number[]}]}}
 */
const getEmailTemplatesOptions = function(columns){
    return {
        preselect: getPreselectOptions(columns.preSelectColumns),
        columnReorder: true,
        columndefs: [
            {
                targets: [1, 2, 3, 4], // Corrected column order
                searchPanes: {
                    show: true,
                    initCollapsed: true,
                    order: ['Type', 'Subject', 'Call Centre', 'Active'],
                }
            }
        ],
        buttons: []
    };
}

export {
    getEmailTemplatesOptions
}