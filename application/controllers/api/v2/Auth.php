<?php

use App\Exceptions\HttpException;

class Auth extends MY_ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->allowedActions = ['login', 'header_check'];
    }

    public function login()
    {
        $this->api->assertMethod('post');

        $this->form_validation->set_data($this->api->getPostData());

        $this->form_validation->set_rules([
            [
                'field' => 'email',
                'rules' => 'required|valid_email',
            ],
            [
                'field' => 'password',
                'rules' => 'required',
            ],
        ]);

        $this->api->validateForm();

        $email = trim($this->api->getPostData('email'));
        $password = trim($this->api->getPostData('password'));
        $app_version = $this->api->getPostData('app_version');
        $ip = $this->api->getPostData('ip');
        $lat = $this->api->getPostData('lat');
        $lng = $this->api->getPostData('lng');

        //$email = trim($_POST['email']);
        //$password = trim($_POST['password']);

        $country_id = $this->config->item('country');

        $params = array(
            'sel_query' => '
                sa.`StaffID`,
                sa.`ClassID`,
                sa.`Email`,
                sa.`FirstName`,
                sa.`LastName`,
                sa.`TechID`,
                sa.`password_new`,
                sa.`profile_pic`,
                sa.`is_electrician`
            ',
            'joins' => array('country_access'),
            'email' => $email,
            'country_id' => $country_id,
            'staff_id' => null,
            'active' => 1,
            'deleted' => 0,
            'display_query' => 0
        );

        // get user details
        $userAccountResult = $this->staff_accounts_model->get_staff_accounts($params);
        //echo $this->db->last_query();

        if ($userAccountResult->num_rows() > 0) {
            $userAccount = $userAccountResult->row();

            $hashedPassword = $userAccount->password_new;

            if ($userAccount->ClassID == 6 && password_verify($password, $hashedPassword)) {
                //echo "IF PASSED!!";

                $this->api->setStatusCode(200);
                $this->api->setSuccess(true);
                $this->api->setMessage('Login successful.');

                $date = date('m/d/Y h:i:s a', time());

                $crm_user_logins = array(
                    'user' => $userAccount->StaffID,
                    'app_version' => $app_version,
                    'ip'  => $ip,
                    'lat' => $lat,
                    'lng' => $lng
                );

                $this->db->set($crm_user_logins)
                    ->insert("crm_user_logins");

                $this->api->putData('logged_user', [
                    'staff_id' => $userAccount->StaffID,
                    'class_id' => $userAccount->ClassID,
                    'email' => $userAccount->Email,
                    'first_name' => $userAccount->FirstName,
                    'last_name' => $userAccount->LastName,
                    'tech_id' => $userAccount->TechID,
                    'profile_pic' => $userAccount->profile_pic,
                    'is_electrician' => $userAccount->is_electrician,
                ]);

                $this->load->model('tech_model');
                $kmsResult = $this->tech_model->getKmsByStaffId($userAccount->StaffID);

                if (($kmsArr = $kmsResult->row_array())) {
                    $kms = [
                        'kms' => $kmsArr['kms'],
                        'last_updated' => $this->system_model->formatDate($kmsArr['kms_updated']),
                        'vehicle_id' => $kmsArr['v_vehicle_id'],
                    ];
                } else {
                    $kms = [
                        'kms' => null,
                        'last_updated' => null,
                        'vehicle_id' => null,
                    ];
                }

                $this->api->putData('kms', $kms);


                if ($kms['vehicle_id']) {
                    $vehicle_id = $kms['vehicle_id'];
                    $techStockData  = $this->db->select('tech_stock_id, date')->from('tech_stock')->where('vehicle', $vehicle_id)->order_by('date', 'DESC')->limit(1)->get()->row_array();
                    $techStockDate = new DateTimeImmutable($techStockData['date'], new DateTimeZone(date_default_timezone_get()));
                    $next7Days = $techStockDate->modify('+7 days')->format('Y-m-d');

                    $techStock = [
                        'original_date' => $techStockDate->format('Y-m-d'),
                        'next_schedule' => $next7Days,
                    ];
                    $last_stock_update_date = new DateTime($techStockDate->format('Y-m-d')." 12:00:01", new DateTimeZone(date_default_timezone_get()));
                } else {
                    $techStock = [
                        'original_date' => null,
                        'next_schedule' => null,
                    ];
                    $current_tmp_date = new DateTime(" 12:00:01", new DateTimeZone(date_default_timezone_get()));
                    $last_stock_update_date = new DateTime($current_tmp_date->modify('-7 days')->format('Y-m-d')." 12:00:01", new DateTimeZone(date_default_timezone_get()));
                }

                /*
                * Weekly stock update reminder message
                */
                $current_date = new DateTime(" 12:00:01", new DateTimeZone(date_default_timezone_get()));
                $diffDays = $current_date->diff($last_stock_update_date)->format("%a");
                $current_date_weekday_no = $current_date->format('N');
                if($current_date_weekday_no == 3 && $diffDays > 0){
                    // Check with today is Wednesday
                    $techStock["week_stock_reminder_message"] = "This is a reminder that your weekly stocktake is due by 12pm";
                } else if ($current_date_weekday_no == 4 && $diffDays > 1){
                    // Check with today is Thursday
                    $techStock["week_stock_reminder_message"] = "This is a reminder that your weekly stocktake was due by 12pm, yesterday";
                } else {
                    $days_ago = "";
                    if($current_date_weekday_no == 7){
                        // Check with today is Sunday
                        $days_ago = 4;
                        if($diffDays < 5){
                            $days_ago = "";
                        }
                    } else if ($current_date_weekday_no == 1){
                        // Check with today is Monday
                        $days_ago = 5;
                        if($diffDays < 6){
                            $days_ago = "";
                        }
                    } else if ($current_date_weekday_no == 2){
                        // Check with today is Monday
                        $days_ago = 6;
                        if($diffDays < 7){
                            $days_ago = "";
                        }
                    } else if ($current_date_weekday_no == 5 && $diffDays > 2){
                        // Check with today is Friday
                        $days_ago = 2;
                    } else if ($current_date_weekday_no == 6 && $diffDays > 3){
                        // Check with today is Saturday
                        $days_ago = 3;
                    }
                    $techStock["week_stock_reminder_message"] = ($days_ago > 0 ? "This is a reminder that your weekly stocktake was due by 12pm, ".$days_ago." days ago" : "");
                }

                /*
                * Monthly stock update reminder message
                */
                $last_working_day_date = new DateTime(" 12:00:01", new DateTimeZone(date_default_timezone_get()));
                if($current_date->format("Y-m") == $last_stock_update_date->format("Y-m")){
                    $last_working_day_date->modify('last day of this month');
                }else{
                    $last_working_day_date->modify('last month');
                    $last_working_day_date->modify('last day of this month');
                }

                if($last_working_day_date->format("l") == "Saturday"){
                    $last_working_day_date->modify('-1 days');
                } elseif ($last_working_day_date->format("l") == "Sunday"){
                    $last_working_day_date->modify('-2 days');
                }

                $techStock["month_stock_reminder_message"] = "";
                $stock_update_diff_days = $last_stock_update_date->diff($last_working_day_date)->format("%r%a");
                $current_date_diff_days = $last_working_day_date->diff($current_date)->format("%r%a");
                
                if( $last_working_day_date->format("Y-m-d") == $current_date->format("Y-m-d") && $stock_update_diff_days >= 1 ) {
                    $techStock["month_stock_reminder_message"] =  "This is a reminder that your monthly stocktake is due by 12pm";
                } elseif( $current_date_diff_days == 1 && $stock_update_diff_days >= 1 ) {
                    $techStock["month_stock_reminder_message"] = "This is a reminder that your monthly stocktake was due by 12pm, yesterday";
                } elseif( $current_date_diff_days >= 2 && $stock_update_diff_days >= 1 ) {
                    $techStock["month_stock_reminder_message"] = "This is a reminder that your monthly stocktake was due by 12pm, ".$current_date_diff_days." days ago";
                }
                    
                $this->api->putData('tech_stock', $techStock);

                /*
                * Certificate due date reminder message
                */
                $this->load->model('Certifications_model');
                $cert_data = $this->Certifications_model
                    ->as_array()
                    ->fields("count(certifications.id) as total_record")
                    ->join('certification_types as ct', 'ct.id = certifications.certification_id', 'LEFT')
                    ->join('jobs as j', 'j.id = certifications.job_id AND j.assigned_tech = '.$userAccount->StaffID, 'INNER')
                    ->where(["DATE(DATE_ADD(j.`date`, INTERVAL ct.`time_to_complete` DAY)) = " => date("Y-m-d")])
                    ->where_status("open","submitted","send_back")
                    ->get();
                
                $cert_reminder_message = "";
                if($cert_data["total_record"] > 0){
                    $cert_reminder_message = "This is a reminder that your ".$cert_data["total_record"]." certification due date is today";
                }

                $this->api->putData('certification_due_reminder_message', $cert_reminder_message);
                $this->api->putData('country_id', $this->config->item('country'));

                $this->api->putData('token', Authorization::generateToken([
                    'staff_id' => $userAccount->StaffID,
                    'class_id' => $userAccount->ClassID,
                    'timestamp' => time() + ($this->config->item('token_timeout') * 60 * 60),
                    'createdAt' => date('U'), // add timestap of creation to check login time 
                    'type' => 'tech',
                ]));

                return;
            }
        }

        $this->api->setStatusCode(200);
        $this->api->setSuccess(false);
        $this->api->setMessage('Invalid e-mail or password.');
    }

    public function refresh_token()
    {
        $staffId = $this->api->getJWTItem('staff_id');
        $classId = $this->api->getJWTItem('class_id');

        $this->api->putData('token', Authorization::generateToken([
            'staff_id' => $staffId,
            'class_id' => $classId,
            'timestamp' =>  time() + ($this->config->item('token_timeout') * 60 * 60),
            'createdAt' => date('U'),  // add timestap of creation to check login time 
            'type' => 'tech',
        ]));

        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
    }

    public function logout()
    {
        // invalidate token, maybe?
    }

    ##############################################
    # Used to check connectivity & is server UP
    #
    ##############################################
    public function header_check()
    {
        $this->api->putData("server_time", date('c'));
        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
    }
}
