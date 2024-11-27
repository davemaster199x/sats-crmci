/**
     * SAS only
     * This DOM function will ticked and populate price on relevant alarms when 'Insert QLD Alarms/Insert SA Alarms' button ticked
     * More details about selected alarm > https://smokealarmtestingservices.sharepoint.com/:x:/r/sites/CRMDevelopment/_layouts/15/Doc.aspx?sourcedoc=%7B12896656-8C34-451B-905A-B8D10796323F%7D&file=Alarm%20Pricing%20SAS.xlsx&action=default&mobileredirect=true
     * 
     * @param string val
     * @param mixed btnEl
     * @param string type > This will tell if event is add/edit from add agency or edit agency
     * 
     * @return [type]
     */
function insert_or_clear_qld_or_sa_alarms(btnEl, val, type)
{

    var qld_alarm = [
        {
            "id": "2",
            "price": "229.00"
        },
        {
            "id": "10",
            "price": "229.00"
        },
        {
            "id": "12",
            "price": "229.00"
        },
        {
            "id": "20",
            "price": "124.00"
        },
        {
            "id": "21",
            "price": "124.00"
        },
        {
            "id": "22",
            "price": "124.00"
        },
        {
            "id": "23",
            "price": "40.00"
        },
        {
            "id": "24",
            "price": "229.00"
        },
        {
            "id": "26",
            "price": "229.00"
        },
        {
            "id": "31",
            "price": "80.00"
        },
        {
            "id": "34",
            "price": "175.00"
        },
        {
            "id": "39",
            "price": "175.00"
        }
    ];

    var sa_alarm = [
        {
            "id": "2",
            "price": "229.00"
        },
        {
            "id": "7",
            "price": "229.00"
        },
        {
            "id": "19",
            "price": "50.00"
        },
        {
            "id": "21",
            "price": "99.00"
        },
        {
            "id": "33",
            "price": "30.00"
        },
        {
            "id": "34",
            "price": "99.00"
        },
        {
            "id": "36",
            "price": "50"
        },
    ];

    btnEl.classList.toggle("active");

    if(btnEl.classList.contains('active')){
       
        //Insert QLD Alarms button clicked
        if(val == 1){
           
            $('.hid_alarm_pwr_id').each(function(){
                var el = $(this);
                var elParents = el.parents('.tr_main_tt');
                if(jQuery.inArray(el.val(), qld_alarm.map(col => col['id'])) !== -1){
                    let alarm_price_arr = qld_alarm.filter(v => v.id == el.val()).map(col => col['price']);

                    //Separate tweak due to different fields attributes each page
                    if(type == 'add'){
                        //Tweak from add/agency page
                        elParents.find('.alarm_approve').prop('checked', true);
                        elParents.find('.price_div').show().find('.alarm_price ').val(alarm_price_arr);
                        elParents.find('.is_approved').val(1); //important flag in /agency/add_agency page otherwise it will not save to DB
                    }else if(type == 'edit'){
                        //Tweak from agency detail or edit agency page
                        elParents.find('.agency_alarm_approve').prop('checked', true);
                        elParents.removeClass('fadedText').find('.alarm_price ').val(alarm_price_arr);
                        elParents.find('.alarm_checked').val(1); //important flag in /agency/view_agency_details page otherwise it will not save to DB
                    }
                   
                }
            })

        }else if(val == 2){
        //Insert SA Alarms button clicked

            $('.hid_alarm_pwr_id').each(function(){
                var el = $(this);
                var elParents = el.parents('.tr_main_tt');
                if(jQuery.inArray(el.val(), sa_alarm.map(col => col['id'])) !== -1){
                    let alarm_price_arr = sa_alarm.filter(v => v.id == el.val()).map(col => col['price']);
                    
                    //Separate tweak due to different fields attributes each page
                    if(type == 'add'){
                        elParents.find('.alarm_approve').prop('checked', true).find('.price_div').show();
                        elParents.find('.price_div').show().find('.alarm_price ').val(alarm_price_arr);
                        elParents.find('.is_approved').val(1); //important flag in /agency/add_agency page otherwise it will not save to DB
                    }else if(type == 'edit'){
                        elParents.find('.agency_alarm_approve').prop('checked', true).find('.price_div').show();
                        elParents.removeClass('fadedText').find('.alarm_price ').val(alarm_price_arr);
                        elParents.find('.alarm_checked').val(1); //important flag in /agency/view_agency_details page otherwise it will not save to DB
                    }
                }
            })

        }

    }else{
    //Clear selected alarms on taping again

        if(val == 1){
        //Clear QLD alarm checkboxes
            $('.hid_alarm_pwr_id').each(function(){
                var el = $(this);
                var elParents = el.parents('.tr_main_tt');
                if(jQuery.inArray(el.val(), qld_alarm.map(col => col['id'])) !== -1){

                    //Separate tweak due to different fields attributes each page
                    if(type == 'add'){
                        elParents.find('.alarm_approve').prop('checked', false);
                        elParents.find('.price_div').hide().find('.alarm_price ').val("");
                        elParents.find('.is_approved').val(0);
                    }else if(type == 'edit'){
                        elParents.find('.agency_alarm_approve').prop('checked', false);
                        elParents.addClass('fadedText').find('.alarm_price ').val("");
                        elParents.find('.alarm_checked').val(0);
                    }
                }
            })

        }else if(val == 2){
        //Clear SA alarm checkboxes

            $('.hid_alarm_pwr_id').each(function(){
                var el = $(this);
                var elParents = el.parents('.tr_main_tt');
                if(jQuery.inArray(el.val(), sa_alarm.map(col => col['id'])) !== -1){

                    //Separate tweak due to different fields attributes each page
                    if(type == 'add'){
                        elParents.find('.alarm_approve').prop('checked', false);
                        elParents.find('.price_div').hide().find('.alarm_price ').val("");
                        elParents.find('.is_approved').val(0);
                    }else if(type == 'edit'){
                        elParents.find('.agency_alarm_approve').prop('checked', false);
                        elParents.addClass('fadedText').find('.alarm_price ').val("");
                        elParents.find('.alarm_checked').val(0);
                    }
                }
            })

        }

        //change button style back to outline
        btnEl.style.background = 'transparent';
        btnEl.style.color = '#00607f';

    }
    
}