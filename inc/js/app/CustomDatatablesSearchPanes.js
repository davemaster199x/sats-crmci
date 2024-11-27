/**
 * Standard Format : Use CamelCase for the function name and the file name
 */
import {getPagesOptions} from "./PageOptions.js";

/**
 * Read this url before modifying the CustomSearchPanes
 * https://learn.jquery.com/code-organization/concepts/
 * @type {{init: init}}
 */
const CustomDatatablesSearchPanes = (function ()
{

    /**
     * Base Init
     */
    const init = function () {
        $(document).ready(function () {
            if(jQuery('#datatable').length){
                initDatatable();
            }
        });
    };

    const initDatatable = function(){

        if (window.location) {
            const pathname = window.location.pathname;
            let { dtOptions } = getPagesOptions(pathname);
            let dataTableConfigs = {};

            /**
             * Datatables Configs
             */
            //if Ajax is set, we set the ajax configs (url, type, dataType)
            if (dtOptions.ajax) {
                dataTableConfigs.ajax = {
                    url: dtOptions.ajax.url,
                    type: dtOptions.ajax.type,
                    dataType: dtOptions.ajax.dataType,
                    dataSrc: dtOptions.ajax.dataSrc
                };
            }

            /**
             * If processing is set, Enable or disable the display of a 'processing' indicator when the table is being processed (e.g. a sort)
             * for server-side processing
             */
            if (dtOptions.processing) {
                dataTableConfigs.processing = dtOptions.processing;
            }

            /**
             * Enable and configure the Responsive extension for DataTables.
             */
            if (dtOptions.responsive) {
                dataTableConfigs.responsive = dtOptions.responsive;
            }

            /**
             * Server-side processing option for handling large data sets. To be able to display all filtering options at the client-side
             */
            if (dtOptions.serverSide) {
                dataTableConfigs.serverSide = dtOptions.serverSide;
            }

            /**
             * Ordering (TRUE OR FALSE) : Enable or disable ordering of columns
             * by default, allows end users to click on the header cell for each column, ordering the table by the data in that column. The ability to order data can be disabled using this option.
             */
            if (dtOptions.ordering) {
                dataTableConfigs.ordering = dtOptions.ordering;
            }

            /**
             * Preselect : Set the searchPanes to be preselected
             * InitCollapsed : Set to true will collapse the searchPanes on load
             * Order : Set the Column Order of the searchPanes
             */
            if (dtOptions.searchPanes) {
                dataTableConfigs.searchPanes = {
                    initCollapsed: dtOptions.initCollapsed ?? true,
                    order: dtOptions.order,
                    preSelect: dtOptions.preselect,
                    layout: dtOptions.layout ?? 'columns-3'
                };
            }

            console.log(dtOptions.colReorder);
            // dtOptions.searchPanes.layout = dtOptions.layout;

            if (dtOptions.columnReorder) {
                dataTableConfigs.colReorder = dtOptions.columnReorder;
            }

            /**
             * ColumnDefs : Set the columnDefs for the searchPanes
             */
            if (dtOptions.columndefs) {
                dataTableConfigs.columnDefs = dtOptions.columndefs;
            }

            /**
             * Columns : allows you to set the column details
             */
            if (dtOptions.columns) {
                dataTableConfigs.columns = dtOptions.columns;
            }

            /**
             * Buttons : extends the options available to the end user for the table
             * Use Buttons Extension for DataTables
             */
            if (dtOptions.buttons) {
                dataTableConfigs.buttons = dtOptions.buttons;
            }

            /**
             * This callback allows you to 'post process' each row after it have been generated for each table draw
             */
            if (dtOptions.rowCallback && typeof dtOptions.rowCallback === 'function') {
                dataTableConfigs.rowCallback = dtOptions.rowCallback;
            }

            /**
             * this callback function allows you to modify the table header on every 'draw' event.
             */
            if (dtOptions.headerCallback && typeof dtOptions.headerCallback === 'function') {
                dataTableConfigs.headerCallback = dtOptions.headerCallback;
            }

            /**
             * this callback function allows you to modify the table footer on every 'draw' event.
             */
            if (dtOptions.footerCallback && typeof dtOptions.footerCallback === 'function') {
                dataTableConfigs.footerCallback = dtOptions.footerCallback;
            }

            /**
             * set DOM configs : Set the table control elements to appear on the page and in what order.
             * pageLength : Set the initial page length (number of rows per page).
             * lengthMenu : Set the available page lengths (per page).
             * language : Set the language strings used globally for all tables in the document.
             * stripeClasses : Set the zebra stripe class names for the rows in the table.
             */
            dataTableConfigs.dom = '<"dtHeader"fB>P<"dtFlexRow"il><t><"dtFlexRowCenter"p>';
            dataTableConfigs.pageLength = 100;
            dataTableConfigs.lengthMenu = [100, 250, 500];
            dataTableConfigs.language = {
                paginate: {
                    previous: "&laquo;",
                        next: "&raquo;",
                },
                lengthMenu: "_MENU_"
            };
            dataTableConfigs.stripeClasses = ['odd', 'odd', 'even', 'even'];

            let dtTable = jQuery('#datatable')
                .on('init.dt', function () {
                    console.log('init');

                    //Get the search field, set placeholder instructions
                    let input = jQuery('#datatable_filter input')
                        .attr('placeholder', 'Search Table')
                        .clone(true, true);

                    // remove label and focus on field on load
                    jQuery('#datatable_filter label').remove();
                    input.appendTo('#datatable_filter').focus();

                    let addBtn = jQuery('.addBtn');

                    if (addBtn.length) {
                        addBtn.appendTo('.dtHeader');
                    }

                    $('.dtsp-panesContainer').hide();

                    //if empty, we hide dtSeachpanes message
                    $('.dtsp-emptyMessage').hide();

                    $('<div id="btn-toggle-searchpanes" title="Toggle SearchPanes"><i class="fa fa-sliders" aria-hidden="true"></i></div>')
                        .on('click', function () {
                            $('.dtsp-panesContainer').toggle();
                        })
                        .appendTo('#datatable_filter');


                    // jQuery('#datatable th').css("background-color", "#00a8ff");

                    /**
                     * Dynamic Initialization for custom jQuery
                     * @type {function}
                     */
                    if (dtOptions.customInit && typeof dtOptions.customInit === 'function') {
                        dtOptions.customInit();
                    }
                })
                .on('draw.dt', function () {
                    console.log('draw');

                    // /**
                    //  * Dynamic Initialization for Datatable draw custom jQuery
                    //  */
                    // if (dtOptions.drawInit && typeof dtOptions.drawInit === 'function') {
                    //     dtOptions.drawInit(dtTable);
                    // }

                })
                .DataTable(dataTableConfigs);

        }
    }

    return {
        init: init
    };

})();

// Initialize ->> It's showtime :)
CustomDatatablesSearchPanes.init();
