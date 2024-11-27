<link rel="stylesheet" href="/inc/css/calendar.css" />
<script src="/inc/js/lib/flatpickr/monthSelect.js"></script>
<script src="/inc/js/lib/jquery-doubleScroll/jquery.doubleScroll.js"></script>
<div class="box-typical box-typical-padding">

	<?php 
// breadcrumbs template
$bc_items = array(
    array(
        'title' => $title,
        'status' => 'active',
        'link' => "/calendar/view_tech_calendar"
    )
);
$bc_data['bc_items'] = $bc_items;
$this->load->view('templates/breadcrumbs', $bc_data);

?>


<header id="calendar-header">

    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
        </div>

        <input class="flatpickr-input" type="hidden" data-id="altinput" value="">
        <input id="month_selection" class="form-control input" placeholder="<?php echo $current_month_year; ?>" tabindex="0" type="text" readonly="readonly">
    </div>


    <?php foreach($filters as $class_id => $staff_class): ?>
    <div class="btn-group">

        <button type="button" class="btn">
            <?php echo $staff_class['ClassName']; ?>
            <span class="count-active"></span>/<?php echo count($staff_class['staff']); ?>
        </button>
        <button type="button" class="btn dropdown-toggle dropdown-toggle-split" data-display="static" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>

        <div class="dropdown-menu">
            <?php foreach($staff_class['staff'] as $staff_id => $staff_account_name): ?>
            <button type="button" class="btn" data-filter="false" data-staff-account-id="<?php echo $staff_id; ?>">
                <?php echo $staff_account_name; ?>
            </button>
            <?php endforeach; ?>
        </div>

    </div>
    <?php endforeach; ?>


    <div class="calendar-header-exports">
        <a class="inline_fancybox" href="javascript:void(0);" data-src="#fancybox-export-csv" data-auto-focus="false">
            <i class="fa fa-file-excel-o"></i> CSV Export
        </a>

        <div id="fancybox-export-csv">
            <h5>Export Calendar to CSV</h5>
            <?php echo form_open('/calendar/export', ['id'=> 'payroll_export_form', 'class' => 'payroll_export_form']); ?>
            <div id="fancybox-export-csv-dates" class="fancybox-export-csv-row">
                <input data-allow-input="true" type="text" name="payroll_from" class="form-control flatpickr flatpickr-input" value="<?php echo $last_week_start->format('d/m/Y') ?>" />
                <div>TO</div>
                <input data-allow-input="true" type="text" name="payroll_to" class="form-control flatpickr flatpickr-input" value="<?php echo $last_week_end->format('d/m/Y') ?>" />
            </div>
            <div id="fancybox-export-csv-options" class="fancybox-export-csv-row">
                <label>
                    <input type="radio" name="staff_filter" value="selected" checked="checked"> Export Selected Staff
                </label>

                <label>
                    <input type="radio" name="staff_filter" value="all"> Export All Staff
                </label>
            </div>
            <div id="fancybox-export-csv-footer" class="fancybox-export-csv-row">
                <button type="submit" class="btn">Download CSV</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</header>

<main class="body-typical-body">
    <div id="calendar-wrapper">
        <div id="calendar">
            <div class="calendar-row calendar-row-dates calendar-row-sticky-top">
                <div class="calendar-cell calendar-cell-date-day">
                    <input type="text" id="calendar-search" placeholder="Type To Filter Staff..." />
                </div>
                <?php foreach($dates as $date): ?>
                <div class="calendar-cell calendar-cell-date-day">
                    <div class="calendar-cell-day"><?php echo $date->format('D'); ?></div>
                    <div class="calendar-cell-date"><?php echo $date->format('j<\s\up>S</\s\up>'); ?></div>
                </div>
                <?php endforeach; ?>
            </div>


            <?php $prev_class_id = null; ?>
            <?php foreach($calendar as $staff_id => $user): ?>
                <?php // if the staff class changes, insert a staff_class row ?>
                <?php if($user['ClassID'] != $prev_class_id): ?>
                <div class="calendar-row calendar-row-staff-class staff-class-<?php echo $user['ClassID']; ?>">
                    <div class="calendar-cell sticky-left">
                        <?php echo $filters[ $user['ClassID'] ]['ClassName']; ?>
                    </div>
                    <div class="calendar-cell"></div>
                </div>
                <?php $prev_class_id = $user['ClassID']; ?>
                <?php endif; ?>


                <?php // user row ?>
                <div class="calendar-row calendar-row-staff"  data-staff-account-id="<?php echo $user['StaffID']; ?>">
	                <?php // first cell - user name / working days ?>
                    <div class="calendar-cell calendar-cell-staff-names">
                        <a href="/users/view/<?php echo $staff_id; ?>" target="_blank">
                            <span class="calendar-cell-firstname"><?php echo $user['FirstName']; ?></span><br/>
                            <span class="calendar-cell-lastname"><?php echo $user['LastName']; ?></span><br/>
                        </a>
                        <div class="calendar-cell-working-days">
                            <?php echo $user['working_days_label']; ?>
                        </div>
                    </div>
	                <?php // remaining cells - date range ?>
                    <?php foreach($user['users_calendar'] as $date => $date_data): ?>
                    <div class="calendar-cell <?php echo $date_data['leave_class'] . ' ' .  $date_data['public_holiday_class'] . ' ' . $date_data['highlight_class'] . ' ' . $date_data['expand_class']; ?>" data-date="<?php echo $date; ?>">
                        <div class="calendar-cell-inner">
                        <?php foreach($date_data['events'] as $event): ?>
                            <p class="<?php echo $event['accomodation_class'] ?? ''; ?>">
                            <?php if(!empty($event['public_holiday'])): ?>
                                <?php echo $event['public_holiday']; ?>

	                        <?php else: ?>
                                <a data-fancybox="" data-type="ajax" rel="<?php echo $event['calendar_id']; ?>" href="javascript:;" data-src="/calendar/add_calendar_entry_static?id=<?php echo $event['calendar_id']; ?>&staff_id=<?php echo $staff_id; ?>">
                                    <?php echo (!is_null($event['accomodation']) ? '<span class="fa fa-home"></span>' : '') . ' ' . $event['region']; ?>
                                </a>

                            <?php endif; ?>
                            </p>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<!-- Fancybox Start -->
<a href="javascript:;" id="about_page_fb_link" class="fb_trigger" data-fancybox data-src="#about_page_fb">Trigger the fancybox</a>							
<div id="about_page_fb" class="fancybox" style="display:none;" >
	<h4><?php echo $title; ?></h4>
	<p>This is the staff calendar used to track days worked, holidays, events and travel.</p>
	<p>Click the month year button top left to choose a month/year.</p>
	<p>You can filter the results by user type or by staff name</p>
	<p>
        Public Holidays no longer need to be added, they are shown automatically via an api service.
        Ensure you set up a users home address to ensure this sets the correct state holidays
    </p>
    <p>
        Working days are listed for each user, an * indicates their details is blank and its defaulted to M T W T F
    </p>

    <h4>Highlighting</h4>
	<ul>
        <li>
            GREEN - A column of green indicates the current day
        </li>
        <li>
            GRAY -  Days that are not included within the users "Working Days". Events will appear at 50% opacity, as they may not really apply and might need tidying up.
        </li>
        <li>
            RED - Public Holidays or Marked As Leave on a working day
        </li>
    </ul>
    <br><br>

    <h4>Days with 3+ Events</h4>
    <p>
        If a user has a day with 3+ events on it, it will appear inset with a inner shadow, simply hover over the cell and it will expand
    to show all events for that day which can then be edited / deleted to tidy up
    </p>

    <h4>Accommodation Legend</h4>
    <p>
        This is meant to be for techs and shows if a tech is needing accommodation, and the status of organising it.
    </p>
    <p class="accomodation-required"><span class="fa fa-home"></span> Required</p>
    <p class="accomodation-pending"><span class="fa fa-home"></span> Pending</p>
    <p class="accomodation-booked"><span class="fa fa-home"></span> Booked</p>
</div>
<!-- Fancybox END -->


<script>
let debug_calendar = 0;
// Staff Search Input - allows staff to quickly search for a name in the list
$(document).ready(function(){


    $('#calendar-wrapper').doubleScroll({
        contentElement: '#calendar', // Widest element, if not specified first child element will be used
        scrollCss: {
            'overflow-x': 'auto',
            'overflow-y': 'auto'
        },
        contentCss: {
            'overflow-x': 'auto',
            'overflow-y': 'auto'
        },
        onlyIfScroll: true, // top scrollbar is not shown if the bottom one is not present
        resetOnWindowResize: true // recompute the top ScrollBar requirements when the window is resized
    });

    // custom jquery "icontains" which is case insenstive
    jQuery.expr[':'].icontains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
            .indexOf(m[3].toUpperCase()) >= 0;
    };

    $("#calendar-search").on('keyup', function() {
        var search_string = $(this).val().toLowerCase();
        if(search_string.length > 0){
            $(".calendar-row-staff").hide();
            $(".calendar-row-staff:icontains('" + search_string + "')").show();
        } else {
            $(".calendar-row-staff").show();
        }
    });

    // Staff Class Filtering
    // load from cookie and set, automatically sets all if no cookie found
    initFilters();


    // Prevent the filter dropdown from hiding when any button is clicked inside
    $('body').on("click", "#calendar-header .dropdown-menu", function (e) {
        $(this).parent().is(".show") && e.stopPropagation();
    });



    // Double clicking a cell displays a modal to add an event for that day
    $(".calendar-cell").on('dblclick', function() {
        console.log('dbl');
        $.fancybox.open({
            src  : "/calendar/add_calendar_entry_static?startdate="+ $(this).data('date') +"&staff_id=" + $(this).parent().data('staff-account-id'),
            type : 'ajax',
            opts : {
                afterShow : function( instance, current ) {
                    console.info( 'done!' );
                }
            }
        });

    });

    // Month Picker in header
    let flatpickrMonthSelect = $('#month_selection').flatpickr({
        plugins: [
            new monthSelectPlugin({
                shorthand: false, //defaults to false
                dateFormat: "F Y", //defaults to "F Y"
                altFormat: "Y m", //defaults to "F Y"
            })
        ],
        onChange: function(selectedDates, dateStr, instance) {
            // console.log(typeof selectedDates);
            // console.log(selectedDates);
            // console.log(dateStr);
            // console.log(instance);

            let dateObj = selectedDates[0];
            //console.log(instance);

            // month is tricky, it requires a 0 fill and also + 1 due to how months are counted in js
            let month = ( parseInt(dateObj.getMonth()) + 1 ).toString().padStart(2, "0");
            window.location='/calendar/view_tech_calendar/'+ month + '/' + dateObj.getFullYear();
        },
    });



    // Export payroll modal
    $(".inline_fancybox").fancybox({
        'hideOnContentClick': true,
        'width': 500,
        'height': 'auto',
        'autoSize': false,
        'autoDimensions':false
    });
});


// To turn on debugging, set the debug_calendar=1 above
// Note that i have seen an issue with duplicate cookies being created for some reason, just delete both cookies and it works again
function initFilters(){
    loadFilters();

    // This handles the btnGroup buttons that will toggle the entire group off or on
    // if any are on, it will turn off, otherwise it will turn on
    $('#calendar-header > .btn-group > button:not(.dropdown-toggle-split)').on('click', function(e){
        console.log('clicking top level button');
        let btnGroup = $(this).parent();
        let count = getBtnGroupActiveFilterCount(btnGroup);
        let btns = btnGroup.find('button[data-filter]');
        let active = true;
        if(count){
            active = false;
        }
        btns.data('filter', active);
        btns.attr('data-filter', active);

        saveFilters();
    });

    // this handles the user btns
    $("#calendar-header > .btn-group > .dropdown-menu > button").on('click', function(e) {
        console.log('clicking button');
        // Toggle the data-filter attribute on click
        $(this).data('filter', ($(this).data('filter') ? false : true));
        $(this).attr('data-filter', $(this).data('filter'));

        saveFilters();
    });
}

// this loads the cookie and then applies the filters
// if cookie doesnt exist yet, it shows all
function loadFilters(){
    let filter_cookie = Cookies.get('calendar-filter-ids');
    if(debug_calendar){
        console.group('loadFilters');
        console.log('filter_cookie',filter_cookie);
    }
    //filter_cookie = {};
    if(filter_cookie === undefined) {
        // show all by default
        $('button[data-filter]').data('filter', true);
        $('button[data-filter]').attr('data-filter', true);
    } else {
        let ids = JSON.parse(filter_cookie);
        if(debug_calendar){
            console.log('filters',ids);
        }
        //
        $('button[data-filter]').each(function(){
            let id = $(this).data('staff-account-id');
            if(ids.includes(id)){
                $(this).data('filter', true);
                $(this).attr('data-filter', true);
            }
        });
    }

    saveFilters();
    if(debug_calendar){
        console.groupEnd('loadFilters');
    }
}

// This saves the selected ids to a cookie
function saveFilters(){
    if(debug_calendar){
        console.group('saveFilters');
    }

    let ids = [];
    $('button[data-filter]').each(function(){
        if($(this).data('filter')){
            let id = $(this).data('staff-account-id');
            ids.push(id);
        }
    });

    let filter_cookie = JSON.stringify(ids);
    if(debug_calendar){
        console.log('saveFilters filter_cookie length',ids.length);
        console.log('saveFilters filter_cookie',filter_cookie);
    }


    // turn array into string for cookie as only strings are supported
    // set the domain so a user can have their set filters per site
    Cookies.set('calendar-filter-ids', filter_cookie, { domain: '<?php echo $_SERVER['SERVER_NAME']; ?>', secure: true });

    // send array of ids to apply function
    applyFilters(ids);
    if(debug_calendar){
        console.groupEnd();
    }
}
// This applies the styles to the filters and the calendar
// highlights buttons and toggles calendar staff rows
// also updates the numbers
function applyFilters(ids){
    if(debug_calendar){
        console.group('applyFilters');
        console.log('ids', ids);
        console.log('typeof', typeof ids);
    }

    $(".calendar-row-staff").hide();
    ids.forEach(function(id) {
        let selector ='div[data-staff-account-id=' + id + ']';
        $(selector).show();
    });

    $('#calendar-header .btn-group').each(function(){
        let count = getBtnGroupActiveFilterCount($(this));
        if(count){
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
        $(this).find('.count-active').text(count)
    });
    if(debug_calendar){
        console.groupEnd();
    }
}

function getBtnGroupActiveFilterCount(btnGroup){
    return btnGroup.find('[data-filter=true]').length;
}
</script>