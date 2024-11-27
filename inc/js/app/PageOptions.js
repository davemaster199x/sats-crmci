/**
 * Standard Format : Use CamelCase for the function name and the file name
 *
 * Please Read the following before adding a new page
 * https://www.w3schools.com/js/js_es5.asp
 * https://www.w3schools.com/js/js_es6.asp
 * https://www.w3schools.com/js/js_modules.asp
 *
 * Import pages functions
 * columns :{
 *     preSelectColumns: 0, // column index to preselect OR Remove this key if you don't want to preselect
 *     totalColumns: 9 // total number of columns that you will need to loop in Array.from
 * }
 *
 * dtOptions - to call the function that will return the options \n
 * @fileoverview PageOptions.js - The purpose of this js file is to provide the functionality for the sats pages.
 *
 * Created by Ram Alcantara 2024-01-12
 */

import {
    getOverdueQLDJobsOptions,
    getPropertyMissedRenewalOptions,
    getAgencyListOptions,
    getPlatformInvoicingOptions
} from "./ExportPagesFunctions.js";
import { getEscalateJobs } from "./jobs/Escalate.js";
import { getWorkOrdersAndUnlinkedSms } from "./jobs/WorkordersAndUnlinkedSms.js";
import { getEmailTemplateLogs } from "./email/EmailTemplateLogs.js";
import { getEmailTemplatesOptions } from "./email/EmailTemplates.js";


export function getPagesOptions(pathname) {

    let columns = {};
    let dtOptions = {
        layout: '',
        buttons: []
    };

    switch(pathname){
        case '/email/view_email_templates':
        case '/email/view_email_templates/template':
            columns = {
                preSelectColumns: 4,
                totalColumns: 4
            };
            dtOptions = getEmailTemplatesOptions(columns);
            break;
        case '/email/view_email_templates/logs':
            columns = {
                preSelectColumns: 0,
                totalColumns: 3
            };
            dtOptions = getEmailTemplateLogs(columns);
            break;
        case '/custom/duplicate_properties':
            // Handle '/custom/duplicate_properties' case if needed
            break;
        case '/daily/overdue_qld_jobs':
            columns = {
                preSelectColumns: 4,
                totalColumns: 9
            };
            dtOptions = getOverdueQLDJobsOptions(columns);
            break;
        case '/agency/view_agencies_v2':
            columns = {
                preSelectColumns: 0,
                totalColumns: 20
            };
            dtOptions = getAgencyListOptions(columns);
            break;
        case '/sms/workorders_and_unlinked_sms':
            columns = {
                preSelectColumns: 6,
                totalColumns: 6
            };
            dtOptions = getWorkOrdersAndUnlinkedSms(columns);
            break;
        case '/reports/property_missed_renewal':
            columns = {
                totalColumns: 6
            };
            dtOptions = getPropertyMissedRenewalOptions(columns);
            break;
        case '/jobs/platform_invoicing':
            columns = {
                preSelectColumns: 2,
                totalColumns: 11
            }
            dtOptions = getPlatformInvoicingOptions(columns);
            break;
        case '/jobs/escalate':
            columns = {
                preSelectColumns: 0,
                totalColumns: 10
            };
            dtOptions = getEscalateJobs(columns);
            break;
        // Add more cases

        default:
            // Handle default case if needed
            break;
    }

    return { dtOptions };
}