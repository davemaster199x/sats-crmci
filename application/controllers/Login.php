<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('staff_accounts_model');
    }

	public function index(){
        $this->output->enable_profiler(FALSE);

        // If user is not on the correct domain, redirect them before showing the login page, saves us htaccess rules
        if(!strpos($_SERVER['APP_URL'], $_SERVER['HTTP_HOST'])){
            redirect($_SERVER['APP_URL']);
        }

		$crm_login = trim($this->input->get_post('crm_login'));

		$this->form_validation->set_rules('username', 'Email', 'required');
		if( $crm_login != 1 ){
			$this->form_validation->set_rules('password', 'Password', 'required');

			if($this->session->userdata('loginFailedCounter')==3){ //validate captcha if failed login == 3
				$this->form_validation->set_rules('g-recaptcha-response','reCaptcha','required|callback_validate_recaptcha');
			}

		}

		if ( $this->form_validation->run() == false ){

			$data['title'] = $this->config->item('company_name_short').' CRM';
			$this->load->view('templates/main_header', $data);
			$this->load->view('login/index',$data);
			$this->load->view('templates/main_footer');

		}else{

			if( hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']) ) {
				
				// authenticate user
				$this->authenticate();
				
			}			

		}


	}

    public function authenticate()
    {
        // form input
        $username = trim(rawurldecode($this->input->get_post('username')));
        $password = trim(rawurldecode($this->input->get_post('password')));
        $expand_menu = trim(rawurldecode($this->input->get_post('expand_menu')));
        $page = trim(rawurldecode($this->input->get_post('page')));
        $page_params = trim(rawurldecode($this->input->get_post('page_params')));
        $country_id = $this->config->item('country');
        $staff_id = $this->input->get_post('staff_id');

        // get user data via username
        $params = array(
            'sel_query' => '
				sa.`StaffID`,
				sa.`ClassID`,
				sa.`Email`,
				sa.`password_new`,
				sp.`crm_login_restricted`
			',
            'joins' => [
                'country_access',
                'crm_login_restricted'
            ],
            'email' => $username,
            'country_id' => $country_id,
            'staff_id' => $staff_id,
            'active' => 1,
            'deleted' => 0,
            'display_query' => 0
        );


        $skip_auth = ($staff_id > 0) ? true : false;

        // get user details
        $staff_account_sql = $this->staff_accounts_model->get_staff_accounts($params);

        // If account does not exist, send back to login
        if (!$staff_account_sql->num_rows()) {
            $this->session->set_flashdata('account_doesnt_exist', 1);
            redirect('/');
        }

        // Account does exist, so lets continue checking credentials
        $staff_account = $staff_account_sql->row();

        // has "Prohibit tech from using web CRM" permission
        if ($staff_account->crm_login_restricted == 1) { // tech is prohibited to login to web version

            $this->session->set_flashdata('prohib_using_web_crm', 1);
            redirect('/');
        }

        // create session array
        $session_data = [
            'staff_id' => $staff_account->StaffID,
            'user_last_active' => time()
        ];

        // Skip password checking as user came from crm old site
        if ($skip_auth) {
            // set session
            $this->session->set_userdata($session_data);

            // crm menu to be expanded
            //$this->session->set_flashdata('expand_menu', $expand_menu);

            $page_params2 = str_replace(':', '=', $page_params); // replace : with =
            $page_params3 = str_replace('-', '&', $page_params2); // replace - with &

            //$redirect_page = ( isset($page) && $page != '' )?$page.( ( $this->input->get_post('page_params') != '' )?"/?{$page_params3}":null ):'bookings/view_schedule';
            $redirect_page = (isset($page) && $page != '') ? $page . (($this->input->get_post(
                        'page_params'
                    ) != '') ? "/?{$page_params3}" : null) : '/home';
            redirect($redirect_page);
        }

        // If password is incorrect
        if (!password_verify($password, $staff_account->password_new)) {
            $this->session->set_flashdata('password_incorrect', 1);
            redirect('/');
        }

        ///////////////////////////////////////////////////
        // Now the only possibility left is success

        // set session
        $this->session->set_userdata($session_data);

        // redirect techs to their home view
        if ($staff_account->ClassID == 6) {
            $redirect_page = 'home/index';
        } else {
            $redirect_page = '/home';
        }

        // capture login
        $logging_sql = "
            INSERT INTO 
            `crm_user_logins`(
                `user`,
                `ip`,
                `date_created`
            )
            VALUES(
                " . $staff_account->StaffID . ",
                '" . $_SERVER["REMOTE_ADDR"] . "',
                '" . date('Y-m-d H:i:s') . "'
            )
            ";
        $this->db->query($logging_sql);

        redirect($redirect_page);
    }
}
