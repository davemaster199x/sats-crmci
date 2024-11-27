<?php

/**
 * @property Email_model $email_model
 * @property gherxlib $gherxlib
 * @property System_model $system_model
 * @property JcClass $jcclass
 */
class Email extends MY_Controller {


	/**
	 * @var int seconds for cache ttl
	 */
	private $cacheTTL = 3600;

	/**
	 * @var string name of cache file for template types
	 */
	private $cacheIdTemplateTypes = 'table_email_templates_type';

	/**
	 * @var string name of cache file for template tag
	 */
	private $cacheIdTemplateTags = 'table_email_templates_tag';

	/**
	 * @var array staff categories that have edit permissions
	 */
	private $canEditStaffClassIds = [2,3,9,10];

	/**
	 * @var boolean can the current user edit?
	 */
	private $canEdit = false;

	/**
	 * @var array breadcrumbs moved out of view to here as its global
	 */
	private $breadcrumbs = 	[
		[
			'title' => 'Email Templates',
			'link' => "/email/view_email_templates"
		],
	];



    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('pagination');
        $this->load->helper('url');
        $this->load->model('email_model');
        $this->load->model('system_model');
		$this->load->driver('cache',  ['adapter' => 'file']);
		if(in_array($this->system_model->getStaffClassID(), $this->canEditStaffClassIds)){
			$this->canEdit = true;
		}

    }

    public function view_email_templates($tab="template") {

        
        $data['title'] = "Email Templates";

        if($tab == 'template'){
            $et_params = [
                'echo_query' => 0,
                'sort_list' => [
					[
						'order_by' => 'et.`temp_type`',
						'sort' => 'ASC'
					],
					[
						'order_by' => 'et.`active`',
						'sort' => 'DESC'
					],
					[
						'order_by' => 'et.`template_name`',
						'sort' => 'ASC'
					]
				]
			];
            $email_templates = $this->email_model->get_email_templates($et_params)->result_array();
            $data['templates'] = $email_templates;

			$total_rows = count($email_templates);
    
            $pagi_links_params_arr = [];
            $pagi_link_params = '/email/view_email_templates/?' . http_build_query($pagi_links_params_arr);
    
            // pagination settings
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'offset';
            $config['total_rows'] = $total_rows;
            $config['per_page'] = $per_page;
            $config['base_url'] = $pagi_link_params;
    
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
    
            // pagination count
            $pc_params = [
                'total_rows' => $total_rows,
                'offset' => $offset,
                'per_page' => $per_page
			];
            $data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
        }

        $data['class_id'] = $this->system_model->getStaffClassID();
        $data["tab"] = $tab;

        $this->load->view('templates/inner_header', $data);
        $this->load->view('emails/views/view_email_templates', $data);
        $this->load->view('templates/inner_footer', $data);
    }

    public function datatable_email_logs() {
        $title = 87;

        $columns = [
            0 => 'details', 
            1 => 'name',
            2 => 'created_date'
		];

		$limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
  
        $totalData = $this->email_model->all_logs_count($title);
            
        $totalFiltered = $totalData; 
            
        if(empty($this->input->post('search')['value'])) {            
            $logs = $this->email_model->all_logs($title,$limit,$start,$order,$dir);
        } else {
            $search = $this->input->post('search')['value']; 
            $logs =  $this->email_model->logs_search($title,$limit,$start,$search,$order,$dir);
            $totalFiltered = $this->email_model->logs_search_count($title,$search);
        }

        $data = [];
        if(!empty($logs)) {
            foreach ($logs as $log) {
                $nestedData['details'] = $log->details;
                $nestedData['name'] = $log->name;
	            $createdDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $log->created_date);
	            $nestedData['created_date'] = $createdDateTime->format('d/m/Y H:i:s');
                $data[] = $nestedData;
            }
        }
        $json_data = [
            "draw" => intval($this->input->post('draw')),  
            "recordsTotal" => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data
		];
        echo json_encode($json_data); 
    }


	/**
	 * Returns data for email template tags from a file cache if it exists, if not then from the database
	 * @return array
	 */
	private function getTemplateTags()
	{
		// Check cache
		$view_data = $this->cache->get($this->cacheIdTemplateTags);

		// If no cache file found, run the query and save to cache
		if (empty($view_data)){
			$tag_params = [
				'echo_query' => 0,
				'sort_list' => [
					[
						'order_by' => 'ett.`tag_name`',
						'sort' => 'ASC'
					]
				],
				'active' => 1
			];
			$view_data = $this->email_model->get_email_template_tag($tag_params)->result_array();
			$this->cache->save($this->cacheIdTemplateTags, $view_data, $this->cacheTTL);
		}

		return $view_data;
	}

	/**
	 * Checks to see if email_templates_type data exists in cache, or gets it from the db
	 * Then it generates an array specifically for a select dropdown
	 * @param int $template_type_id If editing a email template, pass through the current type id for it to be selected
	 * @return array
	 */
	private function getTemplateTypes($template_type_id = 0)
	{
		$options = [];

		// Check cache
		$view_data = $this->cache->get($this->cacheIdTemplateTypes);

		// If cache empty then get from DB
		if (empty($view_data)){
			$type_params = [
				'echo_query' => 0,
				'sort_list' => [
					[
						'order_by' => 'et_type.`name`',
						'sort' => 'ASC'
					]
				]
			];
			$view_data = $this->email_model->get_email_template_type($type_params)->result_array();
			$this->cache->save($this->cacheIdTemplateTypes, $view_data, $this->cacheTTL);
		}


		// Next we prepare our data for being used within a select dropdown
		if (!empty($view_data)) {
			foreach ($view_data as $row) {
				$options[] = [
					'value'	=> $row['email_templates_type_id'],
					'option' => $row['name'],
					'selected' => ($row['email_templates_type_id'] == $template_type_id)
				];

			}
		}

		return $options;
	}

	/** Add / Edit an email template
	 * I combined the two routes into one view
	 * @param $id
	 * @return void
	 */
	public function view_email_template($id = 0) {

		
		$data['exclude_gmap'] = true;

		$title_prefix = 'Add';
		if($id > 0){
			$title_prefix = 'Edit';
		}
		$data['title'] = $title_prefix;

		$data['canEdit'] = $this->canEdit;
		$this->breadcrumbs[] = [
			'title' => $data['title'],
			'status' => 'active',
		];

		$data['bc_items'] = $this->breadcrumbs;

		$data['data'] = (array) $this->email_model->get($id);

		$data['selectTemplateTypes'] = [
			'id' => 'temp_type',
			'name' => 'temp_type',
			'class' => 'form-control addinput temp_type',
			'disabled' => !$this->canEdit,
			'required' => true,
			'options' => $this->getTemplateTypes($data['data']['temp_type'])
		];

		$data['templateTags'] = $this->getTemplateTags();



		$this->load->view('templates/inner_header', $data);
		$this->load->view('emails/views/template', $data);
		$this->load->view('templates/inner_footer', $data);
	}


	public function save_email_template($id = 0)
	{
		if($id){
			$update_where = [
				'email_templates_id' => $id
			];
			$id = $this->email_model->from_form()->update(NULL, $update_where);
		} else {
			$id = $this->email_model->from_form()->insert();
		}

		if($id === FALSE) {
			$this->session->set_flashdata([
				'error_msg' => 'Unsuccessful',
				'status' => 'error'
			]);
		} else {
			$this->system_model->insert_log([
				'title' => 87,
				'details' => "Email Template <b>" . $this->input->get_post('template_name') . "</b> was saved.",
				'created_by_staff' => $this->session->staff_id,
			]);
			$this->session->set_flashdata([
				'success_msg' => 'Template has been updated',
				'status' => 'success'
			]);
		}

		redirect("/email/view_email_templates");
	}

    public function view_send_email_template() {
		$data['exclude_gmap'] = true;
        
        $data['title'] = "Send Email Template";

        $job_id = $this->input->get_post('job_id');
        $to_email = $this->input->get_post('to_email');
        $logged_user = $this->session->staff_id;
        $logged_user_class_id = $this->system_model->getStaffClassID();
        $logged_user_email = $this->gherxlib->getStaffInfo([
                    "sel_query" => "sa.Email",
                    "staff_id" => $logged_user
                ])->row()->Email;

        // get job data
        $job_data_params = [
            'job_id' => $job_id,
            'remove_deleted_filter' => 1,
            'a_status' => 'active',
            'display_echo' => 0
		];
        $jobs = $this->system_model->getJobsData($job_data_params);
        $row = $jobs[0];
        $data['row'] = $row;
        $property_id = $row['property_id'];
        $data['property_id'] = $property_id;
        // put account emails into an array
        $account_emails_exp = explode("\n", trim($row['account_emails']));
        $data['account_emails_exp'] = $account_emails_exp;
        // put agency emails into an array
        $agency_emails_exp = explode("\n", trim($row['agency_emails']));
        $data['agency_emails_exp'] = $agency_emails_exp;

        $pt_params = [
            'property_id' => $row['property_id'],
            'active' => 1,
            'echo_query' => 0
		];
        $data['pt'] = $this->gherxlib->getNewTenantsData($pt_params);

        // get email templates
        $temp_type = 2; //  Email Template Type - Jobs
        if ($logged_user_class_id == 8) {
            // get email templates that is call centre = yes
            $et_params = [
                'echo_query' => 0,
                'sort_list' => [
                    [
                        'order_by' => 'et.`template_name`',
                        'sort' => 'ASC'
					]
				],
                'active' => 1,
                'custom_filter' => ' AND et.`show_to_call_centre` = 1 '
			];
        } else {
            $et_params = [
                'echo_query' => 0,
                'sort_list' => [
                    [
                        'order_by' => 'et.`template_name`',
                        'sort' => 'ASC'
					]
				],
                'active' => 1,
                'temp_type' => $temp_type
			];
        }
        $email_temp = $this->email_model->get_email_templates($et_params)->result_array();
        $data['email_temp_list'] = $email_temp;

        $this->load->view('templates/inner_header', $data);
        $this->load->view('emails/views/view_send_email_template', $data);
        $this->load->view('templates/inner_footer', $data);
    }

    public function get_email_template_by_id_action_ajax() {
        $id = $this->input->get_post('et_id');
        if ((int) $id <= 0) {
            echo json_encode([]);
            return;
        }
        $total_params = [
            'email_templates_id' => $id
		];
        $email_temp = $this->email_model->get_email_templates($total_params)->row();
        $emp_temp_arr = [
            'email_templates_id' => $email_temp['email_templates_id'],
            'subject' => $email_temp['subject'],
            'body' => $email_temp['body']
		];
        echo json_encode($emp_temp_arr);
        return;
    }

    public function preview_email_template_action_ajax() {
        $job_id = $this->input->get_post('job_id');
        $agency_id = $this->input->get_post('agency_id');
        $subject = $this->input->get_post('subject');
        $body = $this->input->get_post('body');

        // parse tags
        if ($agency_id != '') {
            $jparams = ['agency_id' => $agency_id];
        } else if ($job_id != '') {
            $jparams = ['job_id' => $job_id];
        }


        $subject_parsed = $this->email_model->parseEmailTemplateTags($jparams, $subject);
        $body_parsed = $this->email_model->parseEmailTemplateTags($jparams, $body);

        // PHP (server side)
        $arr = [
            "subject" => $subject_parsed,
            "body" => $body_parsed
		];
        echo json_encode($arr);
        return;
    }

    public function send_email_template_action_form_submit() {
        $job_id = $this->input->get_post('job_id');
        $et_id = $this->input->get_post('et_id');
        $from_email = $this->input->get_post('from_email');
        $to_email = $this->input->get_post('to_email');
        $cc_email = $this->input->get_post('cc_email');
        $subject = $this->input->get_post('subject');
        $body = nl2br($this->input->get_post('body'));
        $loggedin_staff_id = $this->session->staff_id;
        $logged_user_name = $this->gherxlib->getStaffInfo([
                    "sel_query" => "sa.FirstName,sa.LastName",
                    "staff_id" => $loggedin_staff_id
                ])->row();
        $loggedin_staff_name = $this->system_model->formatStaffName($logged_user_name['FirstName'], $logged_user_name['LastName']);

        $marked_as_copy = $this->input->get_post('marked_as_copy');

        $job_pdf = $this->input->get_post('job_pdf');
        $file_upload = $_FILES['et_file_upload'];
        $custom_upload = 0;
        $stopSendEmail = 0;
        $is_copy = false;
        $invoice_copy_template_id = 0;
        if ((int) $job_id === 0) {
            return false;
        }
        $params = ['job_id' => $job_id];
        $subject_fin = $this->email_model->parseEmailTemplateTags($params, $subject);
        $message_fin = $this->email_model->parseEmailTemplateTags($params, $body);

        $to_email_arr = explode(";", $to_email);
        $to = [];
        foreach ($to_email_arr as $et_email) {
            if (filter_var(trim($et_email), FILTER_VALIDATE_EMAIL)) { // validate email
                $to[] = $et_email; // needs to be associative array
            }
        }
        $cc_email_arr = explode(";", $cc_email);
        $cc = [];
        foreach ($cc_email_arr as $et_email) {
            if (filter_var(trim($et_email), FILTER_VALIDATE_EMAIL)) { // validate email
                $cc[] = $et_email; // needs to be associative array
            }
        }
    }


    public function send() {

        $this->load->model('sms_model');
        $this->load->model('jobs_model');

        
        $data['title'] = "Send Email";
       

        $country_id = $this->config->item('country');
        $staff_id = $this->session->staff_id;
        $job_id = $this->input->get_post('job_id');
        $tenant_id = $this->input->get_post('tenant_id');
        $attachment_error = $this->input->get_post('attachment_error');
        $data['attachment_error'] = $attachment_error;

        $tags_arr = [];

        $uri = "/email/send?job_id={$job_id}";
        $data['uri'] = $uri;

        // get email templates
        $et_params = [
            'active' => 1,
            'sort_list' => [
                [
                    'order_by' => 'et.`template_name`',
                    'sort' => 'ASC'
                ]
			]
		];
        $data['email_temp_sql'] = $this->email_model->get_email_templates($et_params);
        $data['email_category'] = $this->email_model->get_emails_category();

        if ($job_id != '') {


            // job data
            $sel_query = "
                j.`id` AS jid,
                j.`booked_with`,
                j.`job_type`,

                p.`property_id`,
                p.`landlord_email`,
                p.`state` AS p_state,

                a.`agency_id`,
                a.`franchise_groups_id`,
                a.`account_emails`,
                a.`agency_emails`
            ";
            $job_params = [
                'sel_query' => $sel_query,
                'country_id' => $country_id,
                'job_id' => $job_id,
                'display_query' => 0
			];

            $job_sql = $this->jobs_model->get_jobs($job_params);
            $job_row = $job_sql->row();
            $data['job_row'] = $job_row;            

            // put account emails into an array
            $account_emails_exp = explode("\n",trim($job_row->account_emails));
            $data['account_emails_imp'] = implode(';',$account_emails_exp);
            // put agency emails into an array            
            $agency_emails_exp = explode("\n",trim($job_row->agency_emails));
            $data['agency_emails_imp'] = implode(';',$agency_emails_exp);

            //landlord email
            $data['landlord_emai'] = $job_row->landlord_email;

            //Property Managers Email
            $property_params = [
                'sel_query' => 'pm_id_new', 
                'property_id' => $job_row->property_id
            ];
            $pm_result = $this->properties_model->get_properties($property_params)->row();
            $email_result =  $this->email_model->getPropertyManagersEmail($pm_result->pm_id_new);
            $data['property_managers_email'] = $email_result->email;

            // tenants
            $sel_query = "
                j.`id` AS jid,

                pt.`property_tenant_id`,
                pt.`tenant_firstname`,
                pt.`tenant_lastname`,
                pt.`tenant_email`
            ";
            $tenant_params = [
                'sel_query' => $sel_query,
                'pt_active' => 1,
                'country_id' => $country_id,
                'job_id' => $job_id,
                'join_table' => ['property_tenants'],
                'sort_list' => [
                    [
                        'order_by' => 'pt.`tenant_firstname`',
                        'sort' => 'ASC'
					],
                    [
                        'order_by' => 'pt.`tenant_lastname`',
                        'sort' => 'ASC'
					]
				],
                'display_query' => 0
			];

            $data['tenants_sql'] = $this->jobs_model->get_jobs($tenant_params);

            // get template tags, reuse joseph's function
            $tag_params = [
                'echo_query' => 0,
                'sort_list' => [
                    [
                        'order_by' => 'ett.`tag_name`',
                        'sort' => 'ASC'
					]
				],
                'active' => 1
			];
            $data['template_tags_sql'] = $this->email_model->get_email_template_tag($tag_params);        

        }

        $this->load->view('templates/inner_header', $data);
        $this->load->view('/emails/send', $data);
        $this->load->view('templates/inner_footer', $data);

    }


    public function send_email_script(){
                        
        $this->load->model('/inc/email_functions_model');

        $job_id = $this->input->get_post('job_id');        
        $from = $this->input->get_post('from');   
        $to = $this->input->get_post('to');   
        $cc = $this->input->get_post('cc');   
        $subject = $this->input->get_post('subject');   
        $body = $this->input->get_post('body');   
        $email_type_id = $this->input->get_post('email_type');
        
        $attach_invoice = $this->input->get_post('attach_invoice');   
        $attach_cert = $this->input->get_post('attach_cert');   
        $attach_combined = $this->input->get_post('attach_combined'); 
        $brooks_quote = $this->input->get_post('brooks_quote');
        $economical_quote = $this->input->get_post('economical_quote');
        $cavius_quote = $this->input->get_post('cavius_quote');
        $combined_quote = $this->input->get_post('combined_quote');    
        $attach_mark_as_copy = $this->input->get_post('attach_mark_as_copy');  

        $file_custom_attach = $_FILES["custom_attach"];

        $country_id = $this->config->item('country');        
        $staff_id = $this->session->staff_id;       
        $today_full = date("Y-m-d H:i:s");  
        $custom_attach_file = null;   
        $attachment_error = null;   
                 
        if( $job_id > 0 ){

            // upload            
            if( $_FILES["custom_attach"]['name'] != '' ){

                // Upload vehicle image
                $upload_path = 'uploads/temp';
                $config['upload_path']          = $upload_path;
                //$config['allowed_types']        = 'gif|jpg|png|pdf';                
                //$config['allowed_types']        = '*';
                $config['allowed_types']        = 'pdf|msg|doc|docx|csv|xls|xlsx';

                // custom filename, plus random characters to avoid conflict of same file name
                $file = pathinfo($_FILES["custom_attach"]['name']);
                $custom_filename = 'custom_attach_'.date('YmdHis').rand().'.'. $file['extension'];
                
                $config['file_name'] = $custom_filename; // set custom file name
                //$config['max_size']             = 100;
                //$config['max_width']            = 1024;
                //$config['max_height']           = 768;      
                
                $this->load->library('upload', $config);

                if ( $this->upload->do_upload('custom_attach') ){

                    $upload_data = $this->upload->data();

                    if( $upload_data ){

                        $file_name = $upload_data['file_name'];  
                        $custom_attach_file =  "{$_SERVER['DOCUMENT_ROOT']}/{$upload_path}/{$file_name}";
                        
                    }                

                }else{

                    $attachment_error = $this->upload->display_errors();                                                     

                }

            }

            if( $attachment_error == null ){

                // send email
                $email_params = [
                    'job_id' => $job_id,

                    'from' => $from,
                    'to' => $to,
                    'cc' => $cc,
                    'subject' => $subject,
                    'body' => $body,
                    'email_type_id' => $email_type_id,

                    'attach_invoice' => $attach_invoice,
                    'attach_cert' => $attach_cert,
                    'attach_combined' => $attach_combined, 
                    'brooks_quote' => $brooks_quote,  
                    'economical_quote' => $economical_quote,
                    'cavius_quote' => $cavius_quote,
                    'combined_quote' => $combined_quote, 
                    'attach_mark_as_copy' => $attach_mark_as_copy,

                    'custom_attach_file' => $custom_attach_file
				];

                
                if( $this->email_functions_model->send_email_using_template($email_params) ){
                    $this->session->set_flashdata('send_email_success',1);         
                }else{
                    $this->session->set_flashdata('send_email_success',1);         
                }                            

            }   
            
            redirect("/email/send?job_id={$job_id}&attachment_error={$attachment_error}");
                             
        }       

    }


    public function get_email_template(){

        $template_id = $this->input->get_post('template_id');

        if( $template_id > 0 ){

            // get email templates
            $et_params = [
                'echo_query' => 0,
                'email_templates_id' => $template_id
			];
            $email_temp_sql = $this->email_model->get_email_templates($et_params);
            $email_temp_row = $email_temp_sql->row();

            $emp_temp_arr = [
            'email_templates_id' => $email_temp_row->email_templates_id,
            'subject' => $email_temp_row->subject,
            'body' => $email_temp_row->body,
            'template_name' => $email_temp_row->template_name,
            'temp_type' => $email_temp_row->temp_type
			];

            echo json_encode($emp_temp_arr);

        }        

    }

    public function preview_email_template() {
        
        $job_id = $this->input->get_post('job_id');        
        $agency_id = $this->input->get_post('agency_id');        
        $subject = $this->input->get_post('subject');
        $body = $this->input->get_post('body');

        if( $job_id > 0 ){

            $jparams = ['job_id' => $job_id];

            $subject_parsed = $this->email_model->parseEmailTemplateTags($jparams, $subject);
            $body_parsed = $this->email_model->parseEmailTemplateTags($jparams, $body);

            // PHP (server side)
            $arr = [
                "subject" => $subject_parsed,
                "body" => $body_parsed
			];
            echo json_encode($arr);

        }elseif($agency_id > 0){

            $jparams = ['agency_id' => $agency_id];

            $subject_parsed = $this->email_model->parseEmailTemplateTags($jparams, $subject);
            $body_parsed = $this->email_model->parseEmailTemplateTags($jparams, $body);

            // PHP (server side)
            $arr = [
                "subject" => $subject_parsed,
                "body" => $body_parsed
			];
            echo json_encode($arr);

        }    
        
    }

}
