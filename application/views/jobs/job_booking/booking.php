<div id="create_booking_fb" style="width:565px;min-height:950px;">
   
    <div class="booking_type_div form-group">
        <div class="btn-group" data-toggle="buttons">
            <label class="btn custom_tab_menu btn-primary-outline booking_tab_menu" id='phone_booking_tab'>
                <input type="radio" name="booking_type" id="booking_type_pb" autocomplete="off" checked=""> Phone Booking
            </label>
            <label class="btn custom_tab_menu btn-primary-outline booking_tab_menu" id='en_notice_tab'>
                <input type="radio" name="booking_type" id="booking_type_en" autocomplete="off"> Entry Notice
            </label>
            <label class="btn custom_tab_menu btn-primary-outline booking_tab_menu" id='dk_tab'>
                <input type="radio" name="booking_type" id="booking_type_dk" autocomplete="off"> Door Knock
            </label>
        </div>
    </div>

    <div class="booking_tab_content" id="ajax_booking_tab_content"></div>

</div>

<script type="text/javascript">

    $(document).ready(function(){

        //Booking custom tab script on load
        $('.job_details_box_tab .booking-group').hide()
        $('.phone_booking_tab_content').show();
        $('.booking_tab_menu:first').addClass('focus active');
       // $('.booking_tab_menu:not(:first)').addClass('inactive');

        $('#ajax_booking_tab_content').load('/jobs/ajax_job_booking_phone',{job_id:<?php echo $job_id ?>}, function(response, status, xhr){
            console.log('Success');
        });
        //Booking custom tab script on load end

        $('.booking_tab_menu').click(function(e){

            var node = $(this);
            var id = node.attr('id');

            //node.removeClass('active inactive focus');

            if(node.hasClass('active')){
                return false;
            }else{

               // console.log('not active');

                $('#ajax_booking_tab_content').html("");

                if(id=="phone_booking_tab"){ //load phone booking form fields
                    $('#ajax_booking_tab_content').load('/jobs/ajax_job_booking_phone',{job_id:<?php echo $job_id ?>}, function(response, status, xhr){
                        console.log('Success');
                    });
                }else if(id=="en_notice_tab"){ //load EN form fields
                    $('#ajax_booking_tab_content').load('/jobs/ajax_job_booking_en',{job_id:<?php echo $job_id ?>}, function(response, status, xhr){
                        console.log('Success');
                    });
                }else if(id=="dk_tab"){ //load DK form fields
                    $('#ajax_booking_tab_content').load('/jobs/ajax_job_booking_dk',{job_id:<?php echo $job_id ?>}, function(response, status, xhr){
                        console.log('Success');
                    });
                }
                
            }

        })
        //Booking custom tab script end

        

    })

</script>