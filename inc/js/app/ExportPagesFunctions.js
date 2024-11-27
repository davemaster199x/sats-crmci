/**
 * Standard Format : Use CamelCase for the function name and the file name
 *
 * Import Preset Options, getBaseUrl
 *
 * Created by Ram Alcantara on 2024-01-12
 */
import {
    getPreselectOptions,
    getBaseUrl
} from './DatatableConfigOptions.js'

/**
 * Overdue QLD Jobs Datatable Options
 * @param columns
 * @returns {{buttons: [{extend: string, filename: string, className: string, text: string}], preselect: {column, rows: *[]}[], ordering: boolean, rowCallback: (function(*, *): *), columndefs: [...{searchPanes: {show: boolean}, targets: [number]}[],{className: string, targets: string},{visible: boolean, searchPanes: {show: boolean}, targets: number[]},{orderable: boolean, searchPanes: {show: boolean}, targets: number[]},{visible: boolean, searchPanes: {show: boolean}, targets: number[]},{type: string, targets: number[], render: ((function(*): (*|undefined))|*), order: (number|string)[][]}]}}
 */
const getOverdueQLDJobsOptions = function(columns) {
    return {
        ordering: true,
        preselect: getPreselectOptions(columns.preSelectColumns),
        columndefs: [
            ...Array.from({ length: columns.totalColumns }, (_, i) => ({
                searchPanes: {
                    show: true,
                    initCollapsed: true
                },
                targets: [i]
            })),
            { targets: '_all', className: 'text-left dt-left' },
            { searchPanes: { show: false }, targets: [8], visible: false },
            { searchPanes: { show: false }, targets: [9], orderable: false },
            { searchPanes: { show: false }, targets: [10], visible: false }            
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> Export to Excel',
                filename: 'Overdue QLD Jobs'
            }
        ],
        rowCallback: function(row, data) {
            const url = window.location.href;
            const baseUrl = getBaseUrl(url);
            let appendUrlPath = "";

            /**
             * Make sure to adjust data[index] if you modify the table rows
             * Add hyperlink to Property Address row data
             */
            if (data[1]) {
                let property_id = data[10];
                appendUrlPath = `${baseUrl}/properties/details/?id=${property_id}`;
                $('td:eq(1)', row).html(`<a href='${appendUrlPath}'>${data[1]}</a>`);
            }

            /**
             * Make sure to adjust data[index] if you modify the table rows
             * Add hyperlink to Job Type row data
             */
            if (data[4]) {
                let job_id = $(row).find('td:eq(4)').attr('td-data');
                appendUrlPath = `${baseUrl}/jobs/details/${job_id}`;
                $('td:eq(4)', row).html(`<a href='${appendUrlPath}'>${data[4]}</a>`);
            }

            return row;
        },
        customInit: () => {
            jQuery('.dtsp-panesContainer').hide();
        }
    };
};

/**
 * Overdue QLD Jobs Datatable Options
 * @param columns
 * @returns {{buttons: *[], ordering: boolean, columndefs: [...{searchPanes: {show: boolean}, targets: [number]}[],{orderable: boolean, targets: number[]},{className: string, targets: string}]}}
 */
const getPropertyMissedRenewalOptions = function(columns) {
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
        buttons: []
    };
};

/**
 * Agency List Datatable Options
 * You may be able to modify the columns Title by changing the {columns:[title: 'Sample Title', data: 'sample_data']}
 * @param columns
 * @returns {{serverSide: boolean, select: {column, rows: *[]}[], buttons: [{extend: string, filename: string, exportOptions: {columns: [{data: string},{data: string},{data: string},{data: string},{data: string},null]}, className: string, text: string},{extend: string, filename: string, className: string, text: string}], columns: [{data: string},{data: string},{data: string},{data: string},{data: string},null,null,null,null,null,null], responsive: boolean, processing: boolean, ajax: {url: string, dataSrc: string}, columnDefs: *[], footerCallback: footerCallback}}
 * @param {{totalColumns: number, preSelectColumns: number}} columns
 */
const getAgencyListOptions = function(columns) {
    return {
        ajax: {
            url: "/ajax/agency_ajax/get_ajax_active_agency_list",
            type: "GET",
            dataType: "json",
        },
        processing: true,
        columns: [
            { data: 'agency_name' },        // index0
            { data: 'abn' },                // index1
            { data: 'phone' },              // index2
            { data: 'agency_contact' },     // index3
            { data: 'sales_rep' },          // index4
            { data: 'state' },              // index5
            { data: 'subregion_name' },     // index6
            { data: 'last_contact' },       // index7
            { data: 'activated_date'},      // index8
            { title: "<i class='font-icon font-icon-home text-green'></i>", data: 'property_count' },     // index9
            { title: "<i class='font-icon font-icon-home'></i>", data: 'tot_properties' },            // index10
            {
                title: "Country ID",
                data: 'country_id',
                visible: false,
                render: function (data) {
                    if (data !== null) {
                        return data === "1" ? "AU" : "NZ";
                    }
                }
            },
            { title: "Agency Emails", data: 'agency_emails', visible: false},
            { title: "Status", data: 'status', visible: false},
            { title: "Agency ID", data: 'agency_id', visible: false},
            { title: "Legal Name", data: 'legal_name', visible: false},
            { title: "Address 1", data: 'address_1', visible: false},
            { title: "Address 2", data: 'address_2', visible: false},
            { title: "Suburb", data: 'address_3', visible: false},
            { title: "Address", data: 'agency_address', visible: false},
            { title: "Account Emails", data: 'account_emails', visible: false}, // index20
            { title: "Postcode", data: 'postcode', visible: false},
            { title: "Contact Email", data: 'contact_email', visible: false},
            { title: "Agency Priority", data: 'priority', visible: false},
        ],
        preselect: getPreselectOptions(columns.preSelectColumns),
        columndefs: [
            {
                searchPanes: {
                    show: true,
                    initCollapsed: true
                },
                targets: [0,4,5,6,23]
            },
            {
                searchPanes: {
                    show: false
                },
                targets: [
                    1, 2, 3, 7, 8, 9, 10, 11, 12,
                    13, 14, 15, 16, 17, 18, 19, 20, 21, 22
                ]
            },
            {
                visible: false,
                targets: [
                    11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23
                ]
            },
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> Mailer',
                filename: 'mailer',
                exportOptions: {
                    columns: [0, 5, 11, 12, 13, 14, 8],
                    modifier: {
                        search: 'applied',
                        order: 'applied',
                    },
                    orthogonal: 'export',
                }
            },
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary export-view-agencies',
                text: '<i class="fa fa-file-excel-o"></i> View Agencies',
                action: function (e, dt, button, config) {
                    // Custom AJAX call for export
                    $.ajax({
                        url: "/ajax/agency_ajax/get_export_view_agencies_button",
                        type: "POST",
                        success: function (response) {
                            // Get Current Date
                            let currentDate = new Date();
                            let day = currentDate.getDate();
                            let month = currentDate.getMonth() + 1; // Note: months are zero-based
                            let year = currentDate.getFullYear();
                            let strDate = `${day}-${month}-${year}`;

                            // The download should be initiated by the server, no need to handle response here
                            let blob = new Blob([response]);
                            let link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = `view_agencies_${strDate}.csv`;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        },
                        error: function (xhr, error, thrown) {
                            // Handle errors
                            console.error("Error during export:", error);
                        }
                    });
                }
            }
        ],
        rowCallback: function(row, data) {
            const url = window.location.href;
            const baseUrl = getBaseUrl(url);
            let appendUrlPath = "";

            if (data.agency_id !== null) {
                let agency_id = data.agency_id;
                appendUrlPath = `${baseUrl}/agency/view_agency_details/${agency_id}`;
                $(row).find("td:eq(0)").html(`<a href='${appendUrlPath}'>${data.agency_name}</a>`);
            }

            return row;
        },
        footerCallback: function() {
            let api = this.api();
            let columnsData = [9, 10];

            columnsData.forEach(function (index) {
                if (index >= 0 && index < api.columns().count()) {
                    let total = api.column(index, { page: 'current' }).data().reduce(function (a, b) {
                        return a + (parseFloat(b) || 0);
                    }, 0);

                    $(api.column(index, { page: 'current' }).footer()).html(total);
                }
            });
        }
    };
};

/**
 * Get Jobs Platform Invoice Page
 * @param columns
 * @returns {{buttons: [{extend: string, filename: string, exportOptions: {columns: number[]}, className: string, text: string}], preselect: {column, rows: *[]}[], columndefs: [...{searchPanes: {show: boolean}, targets: [number]}[],{searchPanes: {show: boolean}, targets: number[]},{searchPanes: {show: boolean}, targets: number[]},{searchPanes: {show: boolean}, targets: number[]},{searchPanes: {show: boolean}, targets: number[]},{searchPanes: {show: boolean}, targets: number[]},null,null,null,null,null,null,null,null,null]}}
 */
const getPlatformInvoicingOptions = function(columns){
    return {
        preselect: getPreselectOptions(columns.preSelectColumns),
        columndefs: [
            {
                searchPanes: {
                    show: false
                },
                targets: [3,4,5,6,7,8,9,10,11,12,13,14]
            },
            ...Array.from({ length: columns.totalColumns }, (_, i) => ({
                searchPanes: {
                    show: true,
                    initCollapsed: true
                },
                targets: [i]
            })),
            { orderSequence: [ "asc" ], targets: [2] },
            {
                orderable: false,
                targets: [6,7,8,9,10,11,12,13,14]
            }
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel-o"></i> Export to Excel',
                filename: 'Platform Invoicing',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            },
        ]
    }
}

/**
 * Export Pages Functions
 */
export {
    getOverdueQLDJobsOptions,
    getPropertyMissedRenewalOptions,
    getAgencyListOptions,
    getPlatformInvoicingOptions
};

