<?php

class Properties extends MY_Controller
{
	private $cacheTTL = 3600;
    private $cacheActiveProperties = 'list_active_properties';
    private $cacheState = 'list_state';
    private $cacheCountProperties = 'properties_count';

    /**
     * Has the user submitted a post and is currently filtering so that we can disable caching of the default query
     * @var bool
     */
    private $post = false;

	public function __construct()
	{

		parent::__construct();
		$this->load->helper('url');
		$this->load->model('properties_model');
		$this->load->model('jobs_model');
		$this->load->library('pagination');
		$this->load->model('pme_model');
        $this->load->model('palace_model');
		$this->load->model('ourtradie_model');
		$this->load->model('property_tree_model');
		$this->load->model('alarm_job_type_model');

        $this->load->driver('cache', [
            'adapter' => 'file',
            'backup' => 'file'
        ]);

        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->post = true;
        }
		//$this->load->database();
        $this->load->library('HashEncryption');
	}

	public function active_job_properties()
	{

		
		$data['title'] = "Active Job Properties";
		$uri = "/properties/active_job_properties";
        $data['uri'] = $uri;

		$agency_filter = $this->input->get_post('agency_filter');
		$state_filter = $this->input->get_post('state_filter');
		$search = $this->input->get_post('search');

		$state_ms = $this->input->get_post('state_ms');
		$data['state_ms_json'] = json_encode($state_ms);
		$region_ms = $this->input->get_post('region_ms');
		$data['region_ms_json'] = json_encode($region_ms);
		$sub_region_ms = $this->input->get_post('sub_region_ms');
		$data['sub_region_ms_json'] = json_encode($sub_region_ms);

		if (!empty($sub_region_ms)) {
			$postcodes = $this->system_model->getPostCodeViaSubRegion($sub_region_ms);
		}

		$holiday_rental = $this->input->get_post('holiday_rental');
		$export = $this->input->get_post('export');
		$btn_search = $this->input->get_post('btn_search');

		// pagination
		$per_page = $this->config->item('pagi_per_page');
		$offset = $this->input->get_post('offset');


		$custom_where = null;


		$sel_query = "
		p.`property_id`,
		p.`address_1` AS p_address_1,
		p.`address_2` AS p_address_2,
		p.`address_3` AS p_address_3,
		p.`state` AS p_state,
		p.`postcode` AS p_postcode,
		p.`holiday_rental`,

		ps.`alarm_job_type_id` AS service_type,

		ajt.`type` AS service_type_name,

		a.`agency_id`,
		a.`agency_name`,
		aght.priority,
		apmd.abbreviation
		";

		// short term rental filter
		$fg = 14;
		if( $holiday_rental == 1 ){
			$custom_where = "p.`holiday_rental` = 1";
		}
		$exclude_dha = "a.franchise_groups_id!={$fg}";

		// paginated
		$params = array(
			'sel_query' => $sel_query,
			'custom_where' => $custom_where,
			'custom_where_arr' => array($exclude_dha),

			'p_deleted' => 0,
			'a_status' => 'active',
			'ps_service' => 1,

			'agency_filter' => $agency_filter,
			'state_filter' => $state_filter,
			'postcodes' => $postcodes,

			'search' => $search,

			'join_table' => array('property_services', 'agency_priority', 'agency_priority_marker_definition'),

			'custom_joins' => array(
				'join_table' => 'alarm_job_type AS ajt',
				'join_on' => 'ps.`alarm_job_type_id` = ajt.`id`',
				'join_type' => 'left'
			),

			'sort_list' => array(
				array(
					'order_by' => 'p.`address_2`',
					'sort' => 'ASC',
				),
				array(
					'order_by' => 'p.`address_1`',
					'sort' => 'ASC',
				),
			),

			'group_by' => 'p.`property_id`',

			'display_query' => 0
		);


		if( $export == 1 ){

			if( $btn_search ){

				$lists = $this->properties_model->get_properties($params);

				// file name
				$filename = 'active_job_properties'.date('YmdHis').rand().'.csv';

				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename={$filename}");
				header("Pragma: no-cache");
				header("Expires: 0");

				// file creation
				$file = fopen('php://output', 'w');

				// csv header
				$csv_header = []; // clear
				$csv_header = array( 'Address', 'State', 'Active service', 'Agency', 'Short Term Rental');
				fputcsv($file, $csv_header);

				// csv row
				foreach ( $lists->result() as $property_row ) {

					$csv_row = [];
					$csv_row = array(

						"{$property_row->p_address_1} {$property_row->p_address_2}, {$property_row->p_address_3}",
						$property_row->p_state,
						$property_row->service_type_name,
						$property_row->agency_name,
						( $property_row->holiday_rental == 1 )?'Yes':'No'
					);

					fputcsv($file, $csv_row);

				}

				fclose($file);

			}


		}else{

			if( $btn_search ){

				$params['limit'] = $per_page;
				$params['offset'] = $offset;

				$data['lists'] = $this->properties_model->get_properties($params);
				$data['sql_query'] = $this->db->last_query(); //Show query on About
				
				//Get all rows
				$sel_query = "p.`property_id`";
				$params = array(
					'sel_query' => $sel_query,
					'custom_where' => $custom_where,
					'custom_where_arr' => array($exclude_dha),
					'p_deleted' => 0,
					'a_status' => 'active',
					'ps_service' => 1,

					'join_table' => array('property_services'),

					'agency_filter' => $agency_filter,
					'state_filter' => $state_filter,
					'postcodes' => $postcodes,

					'search' => $search,
					'group_by' => 'p.`property_id`',

					'display_query' => 0
				);
				$query = $this->properties_model->get_properties($params);
				$total_rows = $query->num_rows();

				$pagi_links_params_arr = array(
					'agency_filter' => $agency_filter,
					'state_filter' => $state_filter,
					'sub_region_ms' => $sub_region_ms,
					'search' => $search,
					'holiday_rental' => $holiday_rental,
					'btn_search' => $btn_search
				);

				// pagination link
				$pagi_link_params = "{$uri}/?".http_build_query($pagi_links_params_arr);

				// explort link
				$data['export_link'] = "{$uri}/?export=1&".http_build_query($pagi_links_params_arr);

				// pagination settings
				$config['page_query_string'] = TRUE;
				$config['query_string_segment'] = 'offset';
				$config['total_rows'] = $total_rows;
				$config['per_page'] = $per_page;
				$config['base_url'] = $pagi_link_params;

				$this->pagination->initialize($config);

				$data['pagination'] = $this->pagination->create_links();

				// pagination count
				$pc_params = array(
					'total_rows' => $total_rows,
					'offset' => $offset,
					'per_page' => $per_page
				);

				$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);

			}

			// get Service Types
			$sel_query = "ajt.`id`, ajt.`type`, ajt.`short_name`";
			$params = array(
				'sel_query' => $sel_query,
				'active' => 1,
				'display_query' => 0
			);
			$data['service_types'] = $this->system_model->getServiceTypes($params);

			//Agency name filter
			$sel_query = "DISTINCT(a.`agency_id`), a.`agency_name`";
			$params = array(
				'sel_query' => $sel_query,
				'custom_where' => $custom_where,
				'custom_where_arr' => array($exclude_dha),
				'p_deleted' => 0,
				'a_status' => 'active',
				'ps_service' => 1,

				'join_table' => array('property_services'),

				'sort_list' => array(
					array(
						'order_by' => 'a.`agency_name`',
						'sort' => 'ASC',
					),
				),
				'display_query' => 0
			);
			$data['agency_filter_json'] = json_encode($params);

			// Region Filter ( get distinct state )
			$sel_query = "DISTINCT(p.`state`)";
			$region_filter_arr = array(
				'sel_query' => $sel_query,
				'custom_where' => $custom_where,
				'custom_where_arr' => array($exclude_dha),
				'p_deleted' => 0,
				'a_status' => 'active',
				'ps_service' => 1,

				'join_table' => array('property_services'),

				'sort_list' => array(
					array(
						'order_by' => 'p.`state`',
						'sort' => 'ASC',
					)
				),
				'display_query' => 0
			);
			$data['region_filter_json'] = json_encode($region_filter_arr);

			// state filter
			$sel_query_state_filter = "DISTINCT(p.`state`)";
			$params_state_filter = array(
				'sel_query' => $sel_query_state_filter,
				'custom_where' => $custom_where,
				'custom_where_arr' => array($exclude_dha),
				'p_deleted' => 0,
				'a_status' => 'active',
				'ps_service' => 1,

				'join_table' => array('property_services'),

				'sort_list' => array(
					array(
						'order_by' => 'p.`state`',
						'sort' => 'ASC',
					)
				),
				'display_query' => 0
			);
			$data['state_filter_sql'] = $this->properties_model->get_properties($params_state_filter);


			$this->load->view('templates/inner_header', $data);
			$this->load->view('properties/active_job_properties', $data);
			$this->load->view('templates/inner_footer', $data);

		}


	}

	public function active_properties()
	{
		$data['title'] = "Active Properties";

		$agency_filter = $this->input->get_post('agency_filter');
		$search = $this->input->get_post('search');
		$state_filter = $this->input->get_post('state_filter');

		$state_ms = $this->input->get_post('state_ms');
		$data['state_ms_json'] = json_encode($state_ms);
		$region_ms = $this->input->get_post('region_ms');
		$data['region_ms_json'] = json_encode($region_ms);
		$sub_region_ms = $this->input->get_post('sub_region_ms');
		$data['sub_region_ms_json'] = json_encode($sub_region_ms);

		if (!empty($sub_region_ms)) {
			$postcodes = $this->system_model->getPostCodeViaSubRegion($sub_region_ms);
		}

		$holiday_rental = $this->input->get_post('holiday_rental');
		$export = $this->input->get_post('export');

		// pagination
		$per_page = $this->config->item('pagi_per_page');
		$offset = $this->input->get_post('offset');


		$sel_query = "
		p.`property_id`,
		p.`address_1` AS p_address_1,
		p.`address_2` AS p_address_2,
		p.`address_3` AS p_address_3,
		p.`state` AS p_state,
		p.`postcode` AS p_postcode,
		p.`holiday_rental`,

		r.`region_name`,
		sr.`subregion_name`,

		ajt.`id` AS ajt_id,
        ajt.`type` AS ajt_type,

		a.`agency_id`,
		a.`agency_name`,
		aght.priority,
		apmd.abbreviation
		";

		// $custom_where = "( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )";
		$custom_where = "ps.`service` = 1";

		if( $holiday_rental == 1 ){
			$custom_where .= "AND p.`holiday_rental` = 1 AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )";
		}

		// paginated
		$params = array(
			'sel_query' => $sel_query,
			'custom_where' => $custom_where,
			'p_deleted' => 0,
			'a_status' => 'active',
			'join_table' => array('property_services', 'alarm_job_type' ,'agency_priority', 'agency_priority_marker_definition','join_regions'),
			'agency_filter' => $agency_filter,
			'postcodes' => $postcodes,
			'state_filter' => $state_filter,

			'search' => $search,

			'sort_list' => array(
				array(
					'order_by' => 'p.`address_2`',
					'sort' => 'ASC',
				),
				array(
					'order_by' => 'p.`address_1`',
					'sort' => 'ASC',
				),
			),

			'display_query' => 0
		);


		if( $export == 1 ){
			$property_sql = $this->properties_model->get_properties($params)->result();

			// file name
			$filename = 'property_export'.date('YmdHis').rand().'.csv';

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename={$filename}");
			header("Pragma: no-cache");
			header("Expires: 0");

			// file creation
			$file = fopen('php://output', 'w');

			// csv header
			$csv_header = []; // clear
			$csv_header = array( 'Property ID', 'Address', 'State', 'Postcode', 'Service Type', 'Amount', 'Last YM Date', 'Last YM $', ' Short Term Rental', ' Agency ID', 'Agency');
			fputcsv($file, $csv_header);

			// csv row
			foreach ( $property_sql as $property_row ) {

				$short_term_rental = ( $property_row->holiday_rental == 1 )?'Yes':'No';

				// get last YM
				$last_ym_sql = $this->db->query("
				SELECT 
					`date`,
					`invoice_balance`
                FROM `jobs` 
                WHERE `property_id` = {$property_row->property_id}
                AND `status` = 'Completed'
                AND `del_job` = 0
                AND `assigned_tech` NOT IN(1,2,3)
                ORDER BY `date` DESC
                LIMIT 1
				");
				$last_ym_row = $last_ym_sql->row();
				
				// Last YM Date
				$last_ym_date = ( $this->system_model->isDateNotEmpty($last_ym_row->date) )?date('d/m/Y',strtotime($last_ym_row->date)):null;

				// get price from variation
				$price_var_params = array(
					'service_type' => $property_row->ajt_id,
					'property_id' => $property_row->property_id
				);
				$price_var_arr = $this->system_model->get_property_price_variation($price_var_params);				

				$csv_row = []; // clear

				$csv_row[] = $property_row->property_id;
				$csv_row[] = "{$property_row->p_address_1} {$property_row->p_address_2}, {$property_row->p_address_3}"; 
				$csv_row[] = $property_row->p_state;
				$csv_row[] = $property_row->p_postcode;
				$csv_row[] = $property_row->ajt_type;
				$csv_row[] = number_format($price_var_arr['dynamic_price_total'],2); // Amount
				$csv_row[] = $last_ym_date; 
				$csv_row[] = number_format($last_ym_row->invoice_balance,2); // Last YM $
				$csv_row[] = $short_term_rental;
				$csv_row[] = $property_row->agency_id;
				$csv_row[] = $property_row->agency_name;

				fputcsv($file, $csv_row);

			}

			fclose($file);

			exit();

		} else {

			//Put before limit/offset params to get correct total
			$total_rows = count($this->properties_model->get_properties($params)->result());

            // page view
			$params['limit'] = $per_page;
			$params['offset'] = $offset;

            // Default view is to sort by most recently added
			// Disabled > sort by address instead as per DK's request
            // if(!$this->post) {
            //     $params['sort_list'] = [
            //         [
            //             'order_by' => 'p.address_2',
            //             'sort'     => 'ASC',
			// 		]
            //     ];
            // }

            // Dont use cache if user is filtering property list
            //if(!$this->post){
                //$data['lists'] = $this->cache->get($this->cacheActiveProperties);
            //}

            // if no data from cache, then get from db
            if(!$data['lists']){
                $data['lists'] = $this->properties_model->get_properties($params)->result();

                // if not filtering then lets cache the default property list
//                if(!$this->post){
//                    $this->cache->save($this->cacheActiveProperties, $data['lists'], $this->cacheTTL);
//                }

                //$total_rows = count($data['lists']);
            }
			
			$data['sql_query'] = $this->db->last_query(); //Show query on About

			//Get all rows
			$sel_query = "COUNT(p.`property_id`) AS pcount";
			$params = array(
				'sel_query' => $sel_query,
				'custom_where' => $custom_where,
				'p_deleted' => 0,
				'a_status' => 'active',

				'join_table' => array('property_services', 'alarm_job_type' ,'agency_priority', 'agency_priority_marker_definition','join_regions'),

				'agency_filter' => $agency_filter,
				'postcodes' => $postcodes,
				'state_filter' => $state_filter,

				'search' => $search,

				'display_query' => 0,
				'count' => true
			);

			// get Service Types
			$sel_query = "ajt.`id`, ajt.`type`, ajt.`short_name`";
			$params = array(
				'sel_query' => $sel_query,
				'active' => 1,
				'display_query' => 0
			);
			$data['service_types'] = $this->system_model->getServiceTypes($params);

			//Agency name filter
			$sel_query = "DISTINCT(a.`agency_id`), a.`agency_name`";
			$params = array(
				'sel_query' => $sel_query,
				'p_deleted' => 0,
				'a_status' => 'active',

				'sort_list' => array(
					array(
						'order_by' => 'a.`agency_name`',
						'sort' => 'ASC',
					),
				),
				'display_query' => 0
			);
			$data['agency_filter_json'] = json_encode($params);

			// Region Filter ( get distinct state )
			$sel_query = "DISTINCT(p.`state`)";
			$region_filter_arr = array(
				'sel_query' => $sel_query,
				'p_deleted' => 0,
				'a_status' => 'active',

				'sort_list' => array(
					array(
						'order_by' => 'p.`state`',
						'sort' => 'ASC',
					)
				),
				'display_query' => 0
			);
			$data['region_filter_json'] = json_encode($region_filter_arr);


			// state filter
			$sel_query_state_filter = "DISTINCT(p.`state`)";
			$params_state_filter = array(
				'sel_query' => $sel_query_state_filter,
				'custom_where' => $custom_where,
				'p_deleted' => 0,
				'a_status' => 'active',

				'join_table' => array('property_services', 'alarm_job_type' ,'agency_priority', 'agency_priority_marker_definition','join_regions'),

				'agency_filter' => $agency_filter,
				'postcodes' => $postcodes,

				'sort_list' => array(
					array(
						'order_by' => 'p.`state`',
						'sort' => 'ASC',
					)
				),
				'display_query' => 0
			);
			
			$cache_state = $this->cache->get($this->cacheState);
			$state_list = [];
			if(empty($cache_state)){
				$state_list = $this->properties_model->get_properties($params_state_filter)->result();
				$this->cache->save($this->cacheState, $state_list , $this->cacheTTL);
			}
			$data['state_filter_sql'] = !$cache_state ? $state_list : $cache_state;


			$pagi_links_params_arr = array(
				'agency_filter' 	=> $agency_filter,
				'state_filter'		=> $state_filter,
				'sub_region_ms'		=> $sub_region_ms,
				'holiday_rental'	=> $holiday_rental,
				'search' 			=> $search
			);
			$pagi_link_params = '/properties/active_properties/?' . http_build_query($pagi_links_params_arr);

			// pagination settings
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'offset';
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['base_url'] = $pagi_link_params;

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			// pagination count
			$pc_params = array(
				'total_rows' => $total_rows,
				'offset' => $offset,
				'per_page' => $per_page
			);

			$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);

			$this->load->view('templates/inner_header', $data);
			$this->load->view('properties/active_properties', $data);
			$this->load->view('templates/inner_footer', $data);

		}

	}

	public function deactivated_properties()
	{
		$this->load->model('agency_model');
		$data['title'] = "Deactivated Properties";

		$country_id = $this->config->item('country');

		$agency_filter = $this->input->get_post('agency_filter');
		$salesrep = $this->input->get_post('salesrep');
		$search = $this->input->get_post('search_filter');

		$date_filter = $this->input->get_post('date_from');
		$date_filter_2 = $this->input->get_post('date_to');


		$from_f = null;
		$to_f = null;

		if ((isset($date_filter) && $date_filter != '') && (isset($date_filter_2) && $date_filter_2 != '')) {

			// formated to be database ready Y-m-d
			$from_f = $this->system_model->formatDate($date_filter);
			$to_f = $this->system_model->formatDate($date_filter_2);

			$custom_where = "CAST(p.`deleted_date` AS Date)  BETWEEN '{$from_f}' AND '{$to_f}'";
		}

		// pagination
		$per_page = $this->config->item('pagi_per_page');
		$offset = $this->input->get_post('offset');

		$sel_query = "
		p.`property_id`,
		p.`address_1` AS p_address_1,
		p.`address_2` AS p_address_2,
		p.`address_3` AS p_address_3,
		p.`state` AS p_state,
		p.`postcode` AS p_postcode,
		p.`comments` AS p_comments,
		p.`deleted_date`,
		p.`agency_deleted` AS a_deleted,
		p.`reason` AS p_reason,
		p.`nlm_timestamp` AS nlm_timestamp,

		a.`agency_id` AS a_id,
		a.`agency_name` AS agency_name,
		a.`phone` AS a_phone,
		a.`salesrep`,

		sa.FirstName AS salesrep_fname, 
		sa.LastName AS salesrep_lname,
		
		aght.priority
		";

		$is_nlm = "p.is_nlm = 1";
		$params = array(
			'sel_query' => $sel_query,
			'is_nlm' => 1,
			// 'p_deleted' => 1,
			'a_status' => 'active',

			'agency_filter' => $agency_filter,
			'salesrep' => $salesrep,
			'search' => $search,
			'join_table' => array('agency_priority','staff_accounts'),
			'custom_where' => $custom_where, //date filter
			'custom_where_arr' => array($is_nlm),

			'limit' => $per_page,
			'offset' => $offset,

			'sort_list' => array(
				array(
					'order_by' => 'p.nlm_timestamp',
					'sort' => 'DESC',
				),
			),
		);
		if( $this->input->get_post('export')==1 ){ ##removed limit on export
			unset($params['limit']);
			unset($params['offset']);
		}
		$lists_q = $this->properties_model->get_properties($params);
		$data['page_query'] = $this->db->last_query();

		if( $this->input->get_post('export')==1 ){ ## EXPORT

			// file name
			$filename = 'Deleted_Properties_' . date('Y-m-d') . '.csv';

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename={$filename}");
			header("Pragma: no-cache");
			header("Expires: 0");

			$agency_filter = $this->input->get_post('agency_filter');
			$search = $this->input->get_post('search_filter');

			$date_filter = $this->input->get_post('date_from');
			$date_filter_2 = $this->input->get_post('date_to');

			$from_f = null;
			$to_f = null;

			if ((isset($date_filter) && $date_filter != '') && (isset($date_filter_2) && $date_filter_2 != '')) {

				// formated to be database ready Y-m-d
				$from_f = $this->system_model->formatDate($date_filter);
				$to_f = $this->system_model->formatDate($date_filter_2);

				$custom_where = "CAST(p.`deleted_date` AS Date)  BETWEEN '{$from_f}' AND '{$to_f}'";
			}


			// get data
			/*$sel_query = "
			p.`property_id`,
			p.`address_1` AS p_address_1,
			p.`address_2` AS p_address_2,
			p.`address_3` AS p_address_3,
			p.`state` AS p_state,
			p.`postcode` AS p_postcode,
			p.`comments` AS p_comments,
			p.`deleted_date`,
			p.`agency_deleted` AS a_deleted,

			a.`agency_id` AS a_id,
			a.`agency_name` AS agency_name,
			a.`phone` AS a_phone,
			a.`address_1` AS a_address_1,
			a.`address_2` AS a_address_2,
			a.`address_3` AS a_address_3,
			a.`state` AS a_state,
			a.`postcode` AS a_postcode,
			a.`trust_account_software`,
			a.`tas_connected`,
			";

			$params = array(
				'sel_query' => $sel_query,
				'p_deleted' => 1,
				'a_status' => 'active',

				'agency_filter' => $agency_filter,
				'search' => $search,

				'custom_where' => $custom_where, //date filter

				'sort_list' => array(
					array(
						'order_by' => 'p.deleted_date',
						'sort' => 'DESC',
					),
				),
			);
			$list = $this->properties_model->get_properties($params);
			*/

			$list = $lists_q;

			// file creation
			$file = fopen('php://output', 'w');

			//$header = array("Address", "State", "Agency", "Smoke Alarms", "Safety Switch", "Corded Windows", "Pool Barriers", "Deleted By", "Deleted Date");

			$header = array("Address", "State", "Agency", 'Sales Rep', "Services", "Deleted By", "Deleted Date");

			fputcsv($file, $header);

			foreach ($list->result() as $row) {

				$getAlarmJobType = $this->db->get_where('alarm_job_type', array('id' => $row->j_service))->row()->type;
				$address = "{$row->p_address_1} {$row->p_address_2}, {$row->p_address_3}";

				$export_data['address'] = $address;
				$export_data['state'] = $row->p_state;
				$export_data['agency'] = $row->agency_name;
				$export_data['salesrep'] = "{$row->salesrep_fname} {$row->salesrep_lname}";

				/*$export_data['sa'] = $this->properties_model->get_services($row->property_id, 2);
				$export_data['ss'] = $this->properties_model->get_services($row->property_id, 5);
				$export_data['cw'] = $this->properties_model->get_services($row->property_id, 6);
				$export_data['pool_barriers'] = $this->properties_model->get_services($row->property_id, 7);*/
				$ps_params = array(
					'sel_query' => "ajt.type as ajt_name",
					'join_table' => array('alarm_job_type'),
					'property_id' => $row->property_id,
					'ps_service' => 1
				);
				$prop_services_q = $this->properties_model->getPropertyServices($ps_params)->result_array();

				$prop_services_arr = array();
				foreach($prop_services_q as $prop_services_q_row){
					$prop_services_arr[] = $prop_services_q_row['ajt_name']."\n";
				}
				$export_data['prop_services'] = implode(" ", $prop_services_arr);

				$export_data['deleted_by'] = ($row->a_deleted == 1) ? "Agency" : $this->config->item('company_name_short');
				$export_data['deleted_date'] = $this->system_model->formatDate($row->deleted_date, 'd/m/Y');

				fputcsv($file, $export_data);
			}

			fclose($file);
			exit;

		}else{
			$data['lists'] = $lists_q;

			//Get all rows
			$params = array(
				'sel_query' => "COUNT(p.`property_id`) AS p_count",
				'is_nlm' => 1,
				// 'p_deleted' => 1,
				'a_status' => 'active',

				'agency_filter' => $agency_filter,
				'salesrep' => $salesrep,

				'search' => $search,
				'join_table' => array('agency_priority','staff_accounts'),
				'custom_where' => $custom_where, //date filter
				'custom_where_arr' => array($is_nlm)
			);
			$query = $this->properties_model->get_properties($params);
			$total_rows = $query->row()->p_count;

			//Agency name filter
			$sel_query = "DISTINCT(a.`agency_id`),
			a.`agency_name`";
			$params = array(
				'sel_query' => $sel_query,
				'is_nlm' => 1,
				// 'p_deleted' => 1,
				'a_status' => 'active',

				'salesrep' => $salesrep,

				'search' => $search,
				'join_table' => array('agency_priority','staff_accounts'),
				'custom_where' => $custom_where, //date filter
				'custom_where_arr' => array($is_nlm),

				'sort_list' => array(
					array(
						'order_by' => 'a.`agency_name`',
						'sort' => 'ASC',
					),
				)
			);
			$data['agency_filter'] = $this->properties_model->get_properties($params);

			// Sales Rep
			$sel_query = "
			DISTINCT(sa.`StaffID`), 
			sa.FirstName AS salesrep_fname, 
			sa.LastName AS salesrep_lname
			";
			$params = array(
				'sel_query' => $sel_query,
				'is_nlm' => 1,
				// 'p_deleted' => 1,
				'a_status' => 'active',

				'agency_filter' => $agency_filter,
				'salesrep' => $salesrep,
				
				'search' => $search,
				'join_table' => array('agency_priority','staff_accounts'),
				'custom_where' => $custom_where, //date filter
				'custom_where_arr' => array($is_nlm),

				'sort_list' => array(
					array(
						'order_by' => 'sa.FirstName',
						'sort' => 'ASC',
					),
					array(
						'order_by' => 'sa.LastName',
						'sort' => 'ASC',
					)
				)
			);
			$data['sales_rep_filter'] = $this->properties_model->get_properties($params);


			// pagination settings
			$pagi_links_params_arr = array(
				'date_from' => $from_f,
				'date_to' => $to_f,
				'agency_filter' => $agency_filter,
				'search_filter' => $search
			);
			$pagi_link_params = '/properties/deactivated_properties/?' . http_build_query($pagi_links_params_arr);
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'offset';
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['base_url'] = $pagi_link_params;

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			// pagination count
			$pc_params = array(
				'total_rows' => $total_rows,
				'offset' => $offset,
				'per_page' => $per_page
			);

			$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);

			$this->load->view('templates/inner_header', $data);
			$this->load->view('properties/deactivated_properties', $data);
			$this->load->view('templates/inner_footer', $data);
		}

	}

	/**
	 * Load Tenants (active/inactive) tab
	 * Load via ajax
	 */
	public function get_tenants_ajax()
	{

		$data['title'] = "Tenants";

		$data['prop_id'] = $this->security->xss_clean($this->input->post('prop_id'));

		if ($data['prop_id']) {

			// get active property tenants (new)
			$params_active = array('property_id' => $data['prop_id'], 'active' => 1);
			$data['active_tenants'] = $this->gherxlib->getNewTenantsData($params_active);

			// get inactive property tenants (new)
			$params_inactive = array('property_id' => $data['prop_id'], 'active' => "!=1");
			$data['in_active_tenants'] = $this->gherxlib->getNewTenantsData($params_inactive);
		} else {
			redirect(base_url('properties'), 'refresh');
		}

		//$this->load->view('templates/inner_header', $data);
		$this->load->view('properties/tenant_ajax', $data);
		//$this->load->view('templates/inner_footer', $data);

	}

	/**
	 * ACTIVATE/DEACTIVATE Tenants (ajax)
	 */
	public function update_tenant()
	{

		$data['status'] = false;
		$prop_id = $this->security->xss_clean($this->input->post('prop_id'));
		$tenant_id = $this->security->xss_clean($this->input->post('tenant_id'));
		$action = $this->security->xss_clean($this->input->post('action'));

		if ($action && $action == 'deactivate') {
			$deactivate_data = array(
				'active' => 0
			);
			$deactivate_data = $this->security->xss_clean($deactivate_data);
			$this->properties_model->update_tenant_details($tenant_id, $deactivate_data);

			//insert agency activity - tenant removed
			/*$details = "Tenant Removed for {p_address}";
			$params = array(
				'title' => 7,
				'details' => $details,
				'display_in_vpd' => 1,
				'display_in_portal' => 1,
				'agency_id' => $this->session->agency_id,
				'created_by_staff' => $this->session->aua_id,
				'property_id' => $prop_id,
			);
			$this->system_model->insert_log($params);*/

			//add log for all active job under property
			/*$fetch_active_job = $this->pm->get_active_job_by_propId($prop_id);

			if(!empty($fetch_active_job) && $fetch_active_job){

				foreach($fetch_active_job as $new_row){
					$details = "Tenant Removed for {p_address}";
					$params = array(
						'title' => 7,
						'details' => $details,
						'display_in_vjd' => 1,
						'agency_id' => $this->session->agency_id,
						'created_by_staff' => $this->session->aua_id,
						'property_id' => $prop_id,
						'job_id' => $new_row->id
					);
					$this->system_model->insert_log($params);
				}

			}*/

			$data['status'] = true;
			$data['action'] = 'deactivate';
		} else if ($action && $action == 'reactivate') {
			$reactivate_data = array(
				'active' => 1
			);
			$reactivate_data = $this->security->xss_clean($reactivate_data);
			$this->properties_model->update_tenant_details($tenant_id, $reactivate_data);

			//insert agency activity - tenant reactivate
			/*$details = "Tenant Reactivated for {p_address}";
			$params = array(
				'title' => 8,
				'details' => $details,
				'display_in_vpd' => 1,
				'display_in_portal' => 1,
				'agency_id' => $this->session->agency_id,
				'created_by_staff' => $this->session->aua_id,
				'property_id' => $prop_id,
			);
			$this->system_model->insert_log($params);*/

			//add log for all active job under property
			/*$fetch_active_job = $this->pm->get_active_job_by_propId($prop_id);

			if(!empty($fetch_active_job) && $fetch_active_job){

				foreach($fetch_active_job as $new_row){
					$details = "Tenant Reactivated for {p_address}";
					$params = array(
						'title' => 8,
						'details' => $details,
						'display_in_vjd' => 1,
						'agency_id' => $this->session->agency_id,
						'created_by_staff' => $this->session->aua_id,
						'property_id' => $prop_id,
						'job_id' => $new_row->id
					);
					$this->system_model->insert_log($params);
				}

			}*/

			$data['status'] = true;
			$data['action'] = 'reactivate';
		}

		echo json_encode($data);
	}

	/**
	 * Update Tenant Details/info (via ajax)
	 * Update tenants details/info
	 */
	public function update_tenant_details()
	{

		$data['status'] = false;
		$prop_id = $this->security->xss_clean($this->input->post('prop_id'));
		$tenant_id = $this->security->xss_clean($this->input->post('tenant_id'));

		$data_post = array(
			'tenant_firstname' => $this->input->post('tenant_fname'),
			'tenant_lastname' => $this->input->post('tenant_lname'),
			'tenant_mobile' => $this->input->post('tenant_mobile'),
			'tenant_landline' => $this->input->post('tenant_landline'),
			'tenant_email' => $this->input->post('tenant_email')
		);
		$data_post = $this->security->xss_clean($data_post);
		$update_tenant_details = $this->properties_model->update_tenant_details($tenant_id, $data_post);

		if ($update_tenant_details) {

			// insert agency activity
			/*$details = "Tenant Updated for {p_address}";
			$params = array(
				'title' => 9,
				'details' => $details,
				'display_in_vpd' => 1,
				'display_in_portal' => 1,
				'agency_id' => $this->session->agency_id,
				'created_by_staff' => $this->session->aua_id,
				'property_id' => $prop_id,
			);
			$this->system_model->insert_log($params);*/

			//add log for all active job under property
			/*$fetch_active_job = $this->pm->get_active_job_by_propId($prop_id);

			if(!empty($fetch_active_job) && $fetch_active_job){

				foreach($fetch_active_job as $new_row){
					$details = "Tenant Updated for {p_address}";
					$params = array(
						'title' => 9,
						'details' => $details,
						'display_in_vjd' => 1,
						'agency_id' => $this->session->agency_id,
						'created_by_staff' => $this->session->aua_id,
						'property_id' => $prop_id,
						'job_id' => $new_row->id
					);
					$this->system_model->insert_log($params);
				}

			}*/


		}
		$data['status'] = true;

		echo json_encode($data);
	}


	/**
	 * Add New Tenant via ajax
	 */
	public function add_tenant()
	{
		$this->load->library('form_validation');
		$data['status'] = false;
		$prop_id = $this->security->xss_clean($this->input->post('prop_id'));

		//validate email
		if (filter_var($this->input->post('tenant_email'), FILTER_VALIDATE_EMAIL)) {
			$tenant_email = $this->input->post('tenant_email');
		} else {
			$tenant_email = "";
		}

		//validate
		$this->form_validation->set_rules('tenant_fname', 'First Name', 'required');

		if ($this->form_validation->run() != FALSE) {

			$post_data = array(
				'property_id' => $prop_id,
				'tenant_firstname' => $this->input->post('tenant_fname'),
				'tenant_lastname' => $this->input->post('tenant_lname'),
				'tenant_mobile' => $this->input->post('tenant_mobile'),
				'tenant_landline' => $this->input->post('tenant_landline'),
				'tenant_email' => $tenant_email,
				'active' => 1

			);
			$post_data = $this->security->xss_clean($post_data);
			$add_tenant = $this->properties_model->add_tenants($post_data);

			if ($add_tenant) {

				// Insert Log
				/*$details = "Tenant Added for {p_address}";
				$params = array(
					'title' => 10,
					'details' => $details,
					'display_in_vpd' => 1,
					'display_in_portal' => 1,
					'agency_id' => $this->session->agency_id,
					'created_by_staff' => $this->session->aua_id,
					'property_id' => $prop_id,
				);
				$this->system_model->insert_log($params); */

				//add log for all active job under property
				/*$fetch_active_job = $this->pm->get_active_job_by_propId($prop_id);

				if(!empty($fetch_active_job) && $fetch_active_job){

					foreach($fetch_active_job as $new_row){
						$details = "Tenant Added for {p_address}";
						$params = array(
							'title' => 10,
							'details' => $details,
							'display_in_vjd' => 1,
							'agency_id' => $this->session->agency_id,
							'created_by_staff' => $this->session->aua_id,
							'property_id' => $prop_id,
							'job_id' => $new_row->id
						);
						$this->system_model->insert_log($params);
					}

				}*/


				$data['status'] = true;
			}
		}


		echo json_encode($data);
	}


	/**
	 * Export Inactive/Deleted Properties
	 */
	public function export_deleted_properties()
	{
		// file name
		$filename = 'Deleted_Properties_' . date('Y-m-d') . '.csv';

		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Pragma: no-cache");
		header("Expires: 0");

		$agency_filter = $this->input->get_post('agency_filter');
		$search = $this->input->get_post('search_filter');

		$date_filter = $this->input->get_post('date_from');
		$date_filter_2 = $this->input->get_post('date_to');

		$from_f = null;
		$to_f = null;

		if ((isset($date_filter) && $date_filter != '') && (isset($date_filter_2) && $date_filter_2 != '')) {

			// formated to be database ready Y-m-d
			$from_f = $this->system_model->formatDate($date_filter);
			$to_f = $this->system_model->formatDate($date_filter_2);

			$custom_where = "CAST(p.`deleted_date` AS Date)  BETWEEN '{$from_f}' AND '{$to_f}'";
		}


		// get data
		$sel_query = "
		p.`property_id`,
		p.`address_1` AS p_address_1,
		p.`address_2` AS p_address_2,
		p.`address_3` AS p_address_3,
		p.`state` AS p_state,
		p.`postcode` AS p_postcode,
		p.`comments` AS p_comments,
		p.`deleted_date`,
		p.`agency_deleted` AS a_deleted,

		a.`agency_id` AS a_id,
		a.`agency_name` AS agency_name,
		a.`phone` AS a_phone,
		a.`address_1` AS a_address_1,
		a.`address_2` AS a_address_2,
		a.`address_3` AS a_address_3,
		a.`state` AS a_state,
		a.`postcode` AS a_postcode,
		a.`trust_account_software`,
		a.`tas_connected`,
		";

		$params = array(
			'sel_query' => $sel_query,
			'p_deleted' => 1,
			'a_status' => 'active',

			'agency_filter' => $agency_filter,
			'search' => $search,

			'custom_where' => $custom_where, //date filter

			'sort_list' => array(
				array(
					'order_by' => 'p.deleted_date',
					'sort' => 'DESC',
				),
			),
		);
		$list = $this->properties_model->get_properties($params);

		// file creation
		$file = fopen('php://output', 'w');

		//$header = array("Address", "State", "Agency", "Smoke Alarms", "Safety Switch", "Corded Windows", "Pool Barriers", "Deleted By", "Deleted Date");
		$header = array("Address", "State", "Agency", "Smoke Alarms", "Safety Switch", "Corded Windows", "Pool Barriers", "Deleted By", "Deleted Date");
		fputcsv($file, $header);

		foreach ($list->result() as $row) {

			$getAlarmJobType = $this->db->get_where('alarm_job_type', array('id' => $row->j_service))->row()->type;
			$address = "{$row->p_address_1} {$row->p_address_2}, {$row->p_address_3}";


			$data['address'] = $address;
			$data['state'] = $row->p_state;
			$data['agency'] = $row->agency_name;
			$data['sa'] = $this->properties_model->get_services($row->property_id, 2);
			$data['ss'] = $this->properties_model->get_services($row->property_id, 5);
			$data['cw'] = $this->properties_model->get_services($row->property_id, 6);
			$data['pool_barriers'] = $this->properties_model->get_services($row->property_id, 7);
			$data['deleted_by'] = ($row->a_deleted == 1) ? "Agency" : $this->config->item('company_name_short');
			$data['deleted_date'] = $this->system_model->formatDate($row->deleted_date, 'd/m/Y');


			fputcsv($file, $data);
		}

		fclose($file);
		exit;
	}


	/**
	 * Restore Deleted/Inactive Properties
	 */
	public function restore_property()
	{

		$json_data['status'] = false;
		$del_tenant = $this->input->post('del_tenant');
		$prop_id = $this->input->post('prop_id');

		if ($prop_id && $prop_id != "") {

			$where = "property_id = $prop_id";
			$this->db->select('ps.service, ajt.type, ps.is_payable, ps.property_services_id');
			$this->db->from('property_services AS ps');
			$this->db->join('`alarm_job_type` AS ajt', 'ps.`alarm_job_type_id` = ajt.`id`', 'left');
			$this->db->where($where);
			$this->db->order_by('ps.property_services_id','desc');
			$this->db->limit(1);
			$query = $this->db->get()->row();
			$log_detail = "| {$query->type} Service updated from <strong>".$this->config->item('company_name_short')."</strong> to <strong>No Response</strong>";

			$update_data = array(
				'service' => '2'
			);                    
			$this->db->where('property_id', $prop_id);
			$this->db->where('property_services_id', $query->property_services_id);
			$this->db->update('property_services', $update_data);

			$is_payable_log = '';
			$this_month_start = date("Y-m-01");
			$this_month_end = date("Y-m-t");
			$sixty_days_ago = date("Y-m-d",strtotime("-61 days"));

			// get NLM date
			$this->db->select('nlm_timestamp');
			$this->db->from('property');
			$this->db->where('property_id', $prop_id);
			$data = $this->db->get()->result();
	
			$tmp_date = $data[0]->nlm_timestamp;
	
			$nlm_date = date('Y-m-d',strtotime($tmp_date));

			if(  $nlm_date > $sixty_days_ago && !( $nlm_date >= $this_month_start && $nlm_date <= $this_month_end ) ){
				$is_payable_log = "| Property unmarked <strong>payable</strong>";
				$update_data = array(
					'is_payable' => '0'
				);                    
				$this->db->where('property_id', $prop_id);
				$this->db->update('property_services', $update_data);
			} else {

				// update active service to is_payable to 1 and updated status changed to today
	
				$this->db->select('ajt.`type` AS ajt_type_name');
				$this->db->from('`property_services` as ps');
				$this->db->join('`alarm_job_type` AS ajt','ps.`alarm_job_type_id` = ajt.`id`','left');
				$this->db->where('ps.`property_id`', $prop_id);
				$ps_tt_sql = $this->db->get()->result();
				
				// set is_payable
				$updateService = array(
					'is_payable' => 1,
					'status_changed' => date('Y-m-d H:i:s')
				);
	
				$this->db->where('property_id', $prop_id);
				$this->db->where('service', 1);
				$this->db->update('property_services', $updateService);
	
				## Al > add is_payable log
				$mark_unmark = "marked";
				foreach ($ps_tt_sql as $val) {
					$details =  "Property Service <b>$val->ajt_type_name</b> $mark_unmark <b>payable</b>";
					$params = array(
						'title' => 65, // Property Update
						'details' => $details,
						'display_in_vpd' => 1,									
						'created_by_staff' => $this->session->staff_id,
						'property_id' => $prop_id
					);
					$this->system_model->insert_log($params);
				}
			}

			$update_data = array(
				'deleted' => 0,
				'agency_deleted' => 0,
				'is_nlm' => 0,
				'nlm_display' => NULL,
				'nlm_timestamp' => NULL,
				'nlm_by_sats_staff' => NULL,
				'nlm_by_agency' => NULL
			);
			$update_properties_query = $this->properties_model->restore_property($prop_id, $update_data);

			if ($update_properties_query) { // true
				//INSERT VPD LOG
				$staff_name_query = $this->db->select("StaffID,FirstName,LastName")->where('StaffID', $this->session->staff_id)->get('staff_accounts')->row();
				$staff_name  = "{$staff_name_query->FirstName} {$staff_name_query->LastName}";

				$log_details = "Property Restored by {$staff_name} {$log_detail} {$is_payable_log}";
				$log_params = array(
					'title' => 38,  //Property restored
					'details' => $log_details,
					'display_in_vpd' => 1,
					'created_by_staff' => $this->session->staff_id,
					'property_id' => $prop_id
				);
				$this->system_model->insert_log($log_params);

				

				// UPDATE STATUS CHANGED
				/*	disable as per Joes instuctions
			$status_data = array(
					'status_changed' => date("Y-m-d H:i:s")
				);
				$this->db->where('property_id', $prop_id);
				$this->db->update('property_services', $status_data);
				*/

				//SET JSON STATUS AND MESSAGE
				$json_data['status'] = true;
				$json_data['msg'] = "Property Successfully Restored";
			} else {
				$json_data['status'] = false;
				$json_data['msg'] = "An error has occurred, it looks like the property may have already been restored!";
			}
		} else {
			$json_data['status'] = false;
			$json_data['msg'] = "Error: Please contact admin!";
		}

		echo json_encode($json_data);
	}


	public function serviced_to_sats()
	{
		
		$data['title'] = "Property Services Updated to ".$this->config->item('company_name_short');
		$country_id = $this->config->item('country');
		$uri = '/properties/serviced_to_sats';

		$agency_filter = $this->input->get_post('agency_filter');
		$search = $this->input->get_post('search');
		$from_filter = ($this->input->get_post('from_filter') != '') ? $this->system_model->formatDate($this->input->get_post('from_filter')) : date('Y-m-01');
		$to_filter = ($this->input->get_post('to_filter') != '') ? $this->system_model->formatDate($this->input->get_post('to_filter')) : date('Y-m-t');
		$salerep_filter = $this->input->get_post('salerep_filter');
		$ver = $this->input->get_post('ver');

		// sales commission version on page parameter overrides global settings
		$sales_commission_ver = ( $this->input->get_post('ver') != '' )?$this->input->get_post('ver'):$this->config->item('sales_commission_ver');

		// pagination
		$per_page = $this->config->item('pagi_per_page');;
		$offset = $this->input->get_post('offset');

		$export = $this->input->get_post('export');

		$sel_query = "
			ps.`status_changed`,
			ajt.`type`,
			p.`property_id`,
			p.`address_1`,
			p.`address_2`,
			p.`address_3`,
			p.`state`,
			p.`postcode`,
			p.`deleted`,
			p.`nlm_timestamp`,

			a.`agency_id`,
			a.`agency_name`,
			aght.priority,
			apmd.abbreviation,

			sa.`FirstName`,
			sa.`LastName`
		";

		$custom_where = "
			CAST(ps.`status_changed` AS DATE) BETWEEN '{$from_filter}' AND '{$to_filter}'
			AND (
				p.`is_nlm` IS NULL
				OR p.`is_nlm` = 0
			)
			AND p.deleted = 0
		";

		// paginated
		$params = array(
			'sel_query' => $sel_query,
			'custom_where' => $custom_where,
			'country_id' => $country_id,
			'salesrep' => $salerep_filter,
			'join_table' => array('alarm_job_type', 'staff_accounts', 'agency_priority', 'agency_priority_marker_definition'),
			'sort_list' => array(
				array(
					'order_by' => 'ps.status_changed',
					'sort' => 'DESC',
				)
			),
			'limit' => $per_page,
			'offset' => $offset,
			'display_query' => 0
		);

		// sales commission version switch
		if( $sales_commission_ver == 'new' ){
			$params['is_payable'] = 1;
		}else{
			$params['ps_service'] = 1;
		}

		$lists = $this->properties_model->getPropertyServices($params);
		$data['page_query'] = $this->db->last_query();

		if ($export == 1) { //EXPORT

			$params = array(
				'sel_query' => $sel_query,
				'custom_where' => $custom_where,
				'country_id' => $country_id,
				'salesrep' => $salerep_filter,
				'join_table' => array('alarm_job_type', 'staff_accounts', 'agency_priority', 'agency_priority_marker_definition'),
				'sort_list' => array(
					array(
						'order_by' => 'ps.status_changed',
						'sort' => 'DESC',
					)
				),
				'display_query' => 0
			);

			// sales commission version switch
			if( $sales_commission_ver == 'new' ){
				$params['is_payable'] = 1;
			}else{
				$params['ps_service'] = 1;
			}

			$lists = $this->properties_model->getPropertyServices($params);

			// file name
			$date_export = date('YmdHis');
			$filename = "properties_services_to_sats_{$date_export}.csv";

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename={$filename}");
			header("Pragma: no-cache");
			header("Expires: 0");

			//file creation 
			$csv_file = fopen('php://output', 'w');            

			$header = array('Property Address','Agency','Service Type','Status Changed','Property Status', 'SalesRep');

			fputcsv($csv_file, $header);

			$result = $lists->result();

			foreach($result as $v) {
				$csv_row = [];

				$csv_row[] = $v->address_1 . " " . $v->address_2 . ", " . $v->address_3 . " " . $v->state . " " . $v->postcode;
				$csv_row[] = $v->agency_name;
				$csv_row[] = $v->type;
				$csv_row[] = $v->status_changed;
				$csv_row[] = $v->deleted == 1 ? "Inative" : "Active";
				$csv_row[] = $v->FirstName . " " . $v->LastName;

				fputcsv($csv_file,$csv_row);
			}

			fclose($csv_file); 
      exit;

		} else {
			
			$data['lists'] = $lists;

			//Get all rows
			$sel_query = "COUNT(ps.`property_services_id`) AS ps_count";
			$params = array(
				'sel_query' => $sel_query,
				'custom_where' => $custom_where,
				'country_id' => $country_id,
				'salesrep' => $salerep_filter,

				'join_table' => array('alarm_job_type', 'staff_accounts'),

				'display_query' => 0
			);

			// sales commission version switch
			if( $sales_commission_ver == 'new' ){
				$params['is_payable'] = 1;
			}else{
				$params['ps_service'] = 1;
			}

			$query = $this->properties_model->getPropertyServices($params);
			$total_rows = $query->row()->ps_count;

			// Salesrep filter
			$sel_query = "DISTINCT(sa.`StaffID`), sa.`FirstName`, sa.`LastName`";
			$params = array(
				'sel_query' => $sel_query,
				'custom_where' => $custom_where,
				'country_id' => $country_id,

				'join_table' => array('alarm_job_type', 'staff_accounts'),

				'display_query' => 0
			);

			// sales commission version switch
			if( $sales_commission_ver == 'new' ){
				$params['is_payable'] = 1;
			}else{
				$params['ps_service'] = 1;
			}

			$data['salesrep_filter'] = $this->properties_model->getPropertyServices($params);


			$pagi_links_params_arr = array(
				'from_filter' => $this->input->get_post('from_filter'),
				'to_filter' => $this->input->get_post('to_filter'),
				'salerep_filter' => $this->input->get_post('salerep_filter'),
			);
			$pagi_link_params = "{$uri}?" . http_build_query($pagi_links_params_arr);

			$data['export_link'] = "{$uri}/?export=1&".http_build_query($pagi_links_params_arr);

			// pagination settings
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'offset';
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['base_url'] = $pagi_link_params;

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			// pagination count
			$pc_params = array(
				'total_rows' => $total_rows,
				'offset' => $offset,
				'per_page' => $per_page
			);

			$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);


			$data['from_filter'] = $from_filter;
			$data['to_filter'] = $to_filter;

			$this->load->view('templates/inner_header', $data);
			$this->load->view('properties/serviced_to_sats', $data);
			$this->load->view('templates/inner_footer', $data);

		}
		
	}


	/**
	 * @name: nlm
	 * @description: No Longer Managed Properties
	 * @ JoyV.
	 */

	function nlm_properties()
	{
		
		$data['title'] = "No Longer Managed Properties";
		$country_id = $this->config->item('country');

		/**Input post value */
		$phrase_filter = $this->input->get_post('phrase_filter');
		$sel_agency = $this->input->get_post('nlm_sel_agency');
		$sel_show = $this->input->get_post('nlm_sel_show');

		$propertyArr = array();
		$mainParams = array();
		$countParams = array();

		/** Pagination limit and offset */
		$per_page = 100;
		$offset = $this->input->get_post('offset');

		$agencyParams = array(
			'sel_query' => 'DISTINCT(a.agency_id), a.agency_name',
			'is_nlm' => 1,
			'country_id' => $country_id,
			'sort_list' => array(
				array(
					'order_by' => 'a.`agency_name`',
					'sort' => 'ASC'
				)
			)
		);

		$agencyList = $this->properties_model->get_properties($agencyParams);
		$agencyNA = [];

		foreach ($agencyList->result() as $row) {
			$agencyID = $row->agency_id;
			$agencyName = $row->agency_name;

			$agencyNA[$agencyID] = $agencyName;
		}

		/**Select data */
		$nlm_select = array(
			'nlm_sel_show' => array(
				'money_owing' => '$ Owing',
				'verified_paid' => 'Verified Paid',
				'write_off' => 'Write Off'
			),
			'nlm_sel_agency' => $agencyNA
		);

		$data['select_data'] = $nlm_select;

		/**Table title */
		$data['reports_tbl_title'] = array(
			'Recent Invoice',
			'Date',
			'Amount',
			'Job Type',
			'Agency',
			'Address',
			'Date NLM',
			'NLM By',
			'Verify PAID',
			'$ Owning',
			'Write Off'
		);

		$sel_query = "
			DISTINCT(p.`property_id`),
			p.`address_1`,
			p.`address_2`,
			p.`address_3`,
			p.`state`,
			p.`postcode`,
			p.`nlm_by_sats_staff`,
			p.`nlm_by_agency`,
			p.`nlm_display`,
			p.`nlm_owing`,
			p.`write_off`,
			p.`nlm_timestamp`,

			a.`agency_id`,
			a.`agency_name`,
			aght.priority
		";

		$custom_where_arr = array(
			'nlm_display=1'
		);

		if (strlen($sel_show) !== 0) {
			if ($sel_show === "verified_paid") {
				array_push($custom_where_arr, 'COALESCE(write_off,0) = 0');
			}
			if ($sel_show === "money_owing") {
				$nlm_owing = 'nlm_owing=1';
				$write_off = 'write_off!=1';
				array_push($custom_where_arr, $nlm_owing, 'COALESCE(write_off,0) = 0');
			} else if ($sel_show === "write_off") {
				$write_off = 'write_off=1';
				array_push($custom_where_arr, $write_off);
			}
		} else {
			array_push($custom_where_arr, 'COALESCE(write_off,0) = 0');
		}

		$mainParams = array(
			'sel_query' => $sel_query,
			'country_id' => $country_id,
			'custom_where_arr' => $custom_where_arr,
			'is_nlm' => 1,
			'search' => $phrase_filter,
			'join_table' => array('agency_priority'),
			'sort_list' => array(
				array(
					'order_by' => 'p.nlm_timestamp',
					'sort' => 'DESC',
				)
			),

			'limit' => $per_page,
			'offset' => $offset,

			'display_query' => 0
		);

		/** Count affected rows */
		$sel_query = "COUNT(p.`property_id`) AS ps_count";
		$countParams = array(
			'sel_query' => $sel_query,
			'custom_where_arr' => $custom_where_arr,
			'search' => $phrase_filter,
			'country_id' => $country_id,
			'display_query' => 0
		);

		/** Check if the select_agency has a value */
		if (strlen($sel_agency) !== 0) {
			$mainParams['custom_where'] = 'a.agency_id=' . $sel_agency;
			$countParams['custom_where'] = 'a.agency_id=' . $sel_agency;
		}

		/** Returned properties */
		$data['propertiesReturn'] = $this->properties_model->get_properties($mainParams);
	
		$query = $this->properties_model->get_properties($countParams);
		$total_rows = $query->row()->ps_count;

		/** Call Recent Jobs data for each row */
		foreach($data['propertiesReturn']->result() as $key => $row) {
			$propertyArr[] = $this->fetchRecentJobsData($row->property_id)->result()[0];
		}
		$data['jobPropArr'] = $propertyArr;

		/** Start of Pagination Config here */
		$pagi_links_params_arr = array(
			'phrase_filter' => $this->input->get_post('phrase_filter'),
			'sel_agency' => $this->input->get_post('sel_agency'),
			'sel_show' => $this->input->get_post('sel_show'),
		);

		$pagi_link_params = '/properties/nlm_properties/?' . http_build_query($pagi_links_params_arr);

		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'offset';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['base_url'] = $pagi_link_params;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		$pc_params = array(
			'total_rows' => $total_rows,
			'offset' => $offset,
			'per_page' => $per_page
		);

		$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);

		/** Input post values */
		$data['phrase_filter'] = $phrase_filter;
		$data['sel_agency'] = $sel_agency;
		$data['sel_show'] = $sel_show;

		/** Views */
		$this->load->view('templates/inner_header', $data);
		$this->load->view('properties/nlm_properties', $data);
		$this->load->view('templates/inner_footer', $data);
	}

	/**
	 * Fetch Recent Jobs data using property_id
	 */
	public function fetchRecentJobsData($propertyID)
	{

		$params = array(
			'sel_query' => 'j.id, j.date, j.job_type, j.job_price',
			'custom_where_arr' => array(
				'j.property_id=' . $propertyID,
				"j.`status`='Completed'"
			),
			'sort_list' => array(
				array(
					'order_by' => 'j.date',
					'sort' => 'DESC'
				)
			),
			'limit' => 1
		);

		return $this->jobs_model->get_jobs($params);
	}

	/**
	 * Update no longer managed properties
	 */
	public function updateNLMProperty()
	{
		if ($this->input->is_ajax_request()) {
			$propID = (int)$this->input->post('propID');
			$propVal = (int)$this->input->post('propVal');
			$propType = $this->input->post('propType');
			$dataArr = array();
			$propKey = '';

			switch ($propType) {
				case 'verified':
					$propKey = "nlm_display";
					break;
				case 'owing':
					$propKey = "nlm_owing";
					break;
				case 'write_off':
					$propKey = "write_off";
					break;
			}
			$dataArr = array(
				$propKey => $propVal
			);
			echo json_encode($this->properties_model->update_property($propID, $dataArr));
		} else {
			$error = array("status" => false);
			echo json_encode($error);
		}
	}


	/**
	 * ADD PROPERTY
	 */
	public function add(){

		
		$this->load->model('agency_model');
		$data['title'] = "Add Property";
		$data['enable_PMe'] = true; // enable Pme
		//$data['enable_PMe'] = false; // disable Pme

		//post/get
		$country_id = $this->config->item('country');
		$btnAddProperty = $this->input->post('btnAddProperty');

		$data['pm_passed_agency_id'] = $this->input->get_post('agency_id');
		$data['pm_prop_id'] = $this->input->get_post('pid');
		$agency_id = $this->input->get_post('agency_id');

		//get agency list (dropdown)
		$agency_params = array(
			'sel_query' => '
				a.agency_id,
				a.agency_name,
				a.address_3,
				a.franchise_groups_id,
				a.allow_indiv_pm,
				a.load_api
			',
			'a_status' => 'active',
			'country_id' => $this->config->item('country'),
			'sort_list' => array(
				array(
					'order_by' =>  'a.agency_name',
					'sort' => 'ASC'
				)
			)
		);
		$data['agency_list'] = $this->agency_model->get_agency($agency_params);

		if($country_id==1){ //AU
			if( ENVIRONMENT == 'production' ){ //live
				$data['compass_fg'] = 39;
			}else{
				$data['compass_fg'] = 34;
			}
		}else{
			$data['compass_fg'] = 'compass_fg';
		}


		$this->load->view('templates/inner_header', $data);
		$this->load->view('properties/add', $data);
		$this->load->view('templates/inner_footer', $data);

	}

	public function ajax_toggle_load_api(){

		$agency_id = $this->input->get_post('agency_id');
		$load_api = $this->input->get_post('load_api');

		if( $agency_id > 0 ){

			$data = array(
				'load_api' => $load_api
			);

			$this->db->where('agency_id', $agency_id);
			$this->db->update('agency', $data);

		}

	}

	public function ajax_get_palace_properties(){

		$agency_id = $this->input->get_post('agency_id');

		if( $agency_id > 0 ){

        	$palaceList = $this->palace_model->get_all_palace_property($agency_id);

            $isConnectedCheck_str2 = "
                SELECT
					p.`property_id`,
					p.`address_1`,
					p.`address_2`,
					p.`address_3`,
					p.`state`,
					p.`postcode`,
					p.`deleted`,
					apd.api_prop_id,
					apd.api
				FROM `property` AS  p
				INNER JOIN `api_property_data` AS apd ON p.property_id = apd.crm_prop_id
				WHERE apd.`api_prop_id` IS NOT NULL AND apd.`api_prop_id` != '' AND apd.api = 4
				ORDER BY `address_2` ASC, `address_3` ASC, `address_1` ASC
            ";
            $isConnectedCheck_sql2 = $this->db->query($isConnectedCheck_str2);
            $arrConnectedPalace = $isConnectedCheck_sql2->result();

			if( count($palaceList) > 0 ){ ?>
				<header class="steps-numeric-title">
					<img src="/images/third_party/Palace.png" class="company_logo pme_logo">
				</header>

				<!-- PME START -->
				<div class="row">

					<div class="col-sm">

					<table class="table" id="pme_prop_tbl">
						<thead>
							<th class="pme_address_col">Address</th>
							<th class="text-center">Connected in CRM</th>
							<th class="pme_action_col">Action</th>
						</thead>
						<tbody>
							<?php
							if( count($palaceList) > 0 ){
								foreach($palaceList as $row){
									?>
										<tr class="pme_prop_row">
											<td class="pme_address_col">
					                            <?php
					                                if (trim($row->PropertyUnit) != "") {
					                                    $addUnit = $row->PropertyUnit . "/";
					                                }else {
					                                    $addUnit = "";
					                                }
					                            ?>
												<?=$addUnit.$row->PropertyAddress1 . " " . $row->PropertyAddress2 . ", " . $row->PropertyAddress3 . " " . $row->PropertyAddress4 . " " . $row->PropertyPostCode ?> <span class="badge badge-primary">Palace</span>
												<span class="font-icon font-icon-ok step-icon-finish green_tick pme_prop_found_tick"></span>
											</td>


											<!-- Connected in CRM -->
											<td class="text-center">
												<?php
												$searchPalaceId = $row->PropertyCode;
												$filtered = array_filter($arrConnectedPalace, function($elementPal)  use ($searchPalaceId) {
												    return $elementPal->api_prop_id === $searchPalaceId;
												});

												if( count($filtered) > 0 ){
													echo '<ul>';
													// foreach(  $isConnectedCheck_sql->result() as $connected_prop_row ){
														$connected_prop_full_add  = "{$arrConnectedPalace[array_keys($filtered)[0]]->address_1} {$arrConnectedPalace[array_keys($filtered)[0]]->address_2}, {$arrConnectedPalace[array_keys($filtered)[0]]->address_3} {$arrConnectedPalace[array_keys($filtered)[0]]->state}, {$arrConnectedPalace[array_keys($filtered)[0]]->postcode}";
														echo '<li><a target="_blank" href="/properties/details/?id='.$arrConnectedPalace[array_keys($filtered)[0]]->property_id.'" class="'. ( ( $exist_in_crm_row->deleted == 1 )?'txt_red':null ) .'">'.$connected_prop_full_add.'</a> <span class="font-icon font-icon-ok step-icon-finish green_tick"></span></li>';
													// }
													echo '</ul>';

												}else{
													echo '<img src="/images/escalate_jobs/verify_nlm.png" class="icon_red_x" />';
												}
												?>
											</td>
											<td class="pme_action_col">

												<?php
												if( count($filtered) > 0 ){ ?>
													<a href="javascript:void(0);" class="txt_red"><span class="fa fa-exclamation-triangle dup_icon_action"></span></a>
												<?php
												}
												?>

												<?php
												if( count($filtered) == 0 ){ ?>
													<button type="button" class="btn pme_prop_select_btn" sel-id="4">Select</button>
												<?php
												}
												?>

												<input type="hidden" class="pme_addr_unit" value="<?php echo $row->PropertyUnit; ?>" />
												<input type="hidden" class="pme_addr_number" value="<?php echo $row->PropertyAddress1; ?>" />
												<input type="hidden" class="pme_addr_street" value="<?php echo $row->PropertyAddress2; ?>" />
												<input type="hidden" class="pme_addr_suburb" value="<?php echo $row->PropertyAddress3; ?>" />
												<input type="hidden" class="pme_addr_postalcode" value="<?php echo $row->PropertyPostCode; ?>" />
												<input type="hidden" class="pme_addr_state" value="<?php echo $row->PropertyAddress4; ?>" />
												<input type="hidden" class="pme_addr_lat" value="" />
												<input type="hidden" class="pme_addr_lng" value="" />

												<input type="hidden" class="pme_addr_key" value="<?php echo $row->PropertyKeyNo; ?>" />

												<input type="hidden" class="pme_prop_id" value="<?php echo $row->PropertyCode; ?>" />
												<input type="hidden" class="pme_prop_owner_contact_id" value="<?php echo $row->PropertyOwnerCode; ?>" />
												<input type="hidden" class="pme_prop_tenants_contact_id" value="" />
												<input type="hidden" class="api_platform" value="4" />
												<input type="hidden" class="api_owner_code" value="<?=$row->PropertyOwnerCode?>" />

											</td>
										</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
					<script>
					// run datatable
					var table = jQuery('#pme_prop_tbl').DataTable({

						"columnDefs": [
							{
								"searchable": true,
								"targets": 'pme_address_col'
							},
							{
								"searchable": false,
								"targets": '_all'
							}
						]

					});
					</script>

					</div>

				</div>
			<?php
			}

		}
	}

	public function ajax_get_pme_properties(){

		$agency_id = $this->input->get_post('agency_id');
		$api_id = 1; // PMe

		if( $agency_id > 0 ){

			$end_points = "https://app.propertyme.com/api/v1/lots";

			// get access token
			$pme_params = array(
				'agency_id' => $agency_id,
				'api_id' => $api_id
			);
			$access_token = $this->pme_model->getAccessToken($pme_params);

			$pme_params = array(
				'access_token' => $access_token,
				'end_points' => $end_points
			);

			$response =  $this->pme_model->call_end_points_v2($pme_params);
			$pme_prop_list_json = json_decode($response);

			$isConnectedCheck_str = "
				SELECT
					p.`property_id`,
					p.`address_1`,
					p.`address_2`,
					p.`address_3`,
					p.`state`,
					p.`postcode`,
					p.`deleted`,
					apd.api_prop_id,
					apd.api
				FROM `property` AS  p
				INNER JOIN `api_property_data` AS apd ON p.property_id = apd.crm_prop_id
				WHERE apd.`api_prop_id` IS NOT NULL AND apd.`api_prop_id` != '' AND apd.api = 1
				ORDER BY `address_2` ASC, `address_3` ASC, `address_1` ASC
			";
			$isConnectedCheck_sql = $this->db->query($isConnectedCheck_str);
			$arrConnected = $isConnectedCheck_sql->result();

			if( count($pme_prop_list_json) > 0 ){ ?>

				<header class="steps-numeric-title">
					<img src="/images/third_party/pme_logo_transparent.png" class="company_logo pme_logo">
				</header>

				<!-- PME START -->
				<div class="row">

					<div class="col-sm">

					<table class="table" id="pme_prop_tbl">
						<thead>
							<th class="pme_address_col">Address</th>
							<th class="text-center">Connected in CRM</th>
							<th class="pme_action_col">Action</th>
						</thead>
						<tbody>
							<?php
							if( count($pme_prop_list_json) > 0 ){
								foreach($pme_prop_list_json as $pme_prop){
									?>
										<tr class="pme_prop_row">
											<td class="pme_address_col">
												<?php echo $pme_prop->AddressText; ?> <span class="badge badge-primary">PropertyMe</span>
												<span class="font-icon font-icon-ok step-icon-finish green_tick pme_prop_found_tick"></span>
											</td>


											<!-- Connected in CRM -->
											<td class="text-center">
												<?php
												$searchPmeId = $pme_prop->Id;
												$filtered = array_filter($arrConnected, function($element)  use ($searchPmeId) {
												    return $element->api_prop_id === $searchPmeId;
												});

												if( count($filtered) > 0 ){

													echo '<ul>';
													// foreach(  $isConnectedCheck_sql->result() as $connected_prop_row ){
														$connected_prop_full_add  = "{$arrConnected[array_keys($filtered)[0]]->address_1} {$arrConnected[array_keys($filtered)[0]]->address_2}, {$arrConnected[array_keys($filtered)[0]]->address_3} {$arrConnected[array_keys($filtered)[0]]->state}, {$arrConnected[array_keys($filtered)[0]]->postcode}";
														echo '<li><a target="_blank" href="/properties/details/?id='.$arrConnected[array_keys($filtered)[0]]->property_id.'" class="'. ( ( $exist_in_crm_row->deleted == 1 )?'txt_red':null ) .'">'.$connected_prop_full_add.'</a> <span class="font-icon font-icon-ok step-icon-finish green_tick"></span></li>';
													// }
													echo '</ul>';

												}else{
													echo '<img src="/images/escalate_jobs/verify_nlm.png" class="icon_red_x" />';
												}
												?>
											</td>
											<td class="pme_action_col">

												<?php
												if( count($filtered) > 0 ){ ?>
													<a href="javascript:void(0);" class="txt_red"><span class="fa fa-exclamation-triangle dup_icon_action"></span></a>
												<?php
												}
												?>

												<?php
												if( count($filtered) == 0 ){ ?>
													<button type="button" class="btn pme_prop_select_btn" sel-id="1">Select</button>
												<?php
												}
												?>

												<input type="hidden" class="pme_addr_unit" value="<?php echo $pme_prop->Address->Unit; ?>" />
												<input type="hidden" class="pme_addr_number" value="<?php echo $pme_prop->Address->Number; ?>" />
												<input type="hidden" class="pme_addr_street" value="<?php echo $pme_prop->Address->Street; ?>" />
												<input type="hidden" class="pme_addr_suburb" value="<?php echo $pme_prop->Address->Suburb; ?>" />
												<input type="hidden" class="pme_addr_postalcode" value="<?php echo $pme_prop->Address->PostalCode; ?>" />
												<input type="hidden" class="pme_addr_state" value="<?php echo $pme_prop->Address->State; ?>" />
												<input type="hidden" class="pme_addr_lat" value="<?php echo $pme_prop->Address->Latitude; ?>" />
												<input type="hidden" class="pme_addr_lng" value="<?php echo $pme_prop->Address->Longitude; ?>" />

												<input type="hidden" class="pme_addr_key" value="<?php echo $pme_prop->KeyNumber; ?>" />

												<input type="hidden" class="pme_prop_id" value="<?php echo $pme_prop->Id; ?>" />
												<input type="hidden" class="pme_prop_owner_contact_id" value="<?php echo $pme_prop->OwnerContactId; ?>" />
												<input type="hidden" class="pme_prop_tenants_contact_id" value="<?php echo $pme_prop->TenantContactId; ?>" />
												<input type="hidden" class="api_platform" value="1" />

											</td>
										</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
					<script>
					// run datatable
					var table = jQuery('#pme_prop_tbl').DataTable({

						"columnDefs": [
							{
								"searchable": true,
								"targets": 'pme_address_col'
							},
							{
								"searchable": false,
								"targets": '_all'
							}
						]

					});
					</script>

					</div>

				</div>
				<!-- PME END  -->

			<?php
			}

		}

	}

	// get Property Tree list
    public function ajax_get_pt_properties(){ 

		$agency_id = $this->input->get_post('agency_id');
		$api_id = 3; // Property Tree

        $prop_tree_list = $this->property_tree_model->get_all_properties($agency_id);
        
        ?>
		<header class="steps-numeric-title">
			<img src="/images/third_party/propertytree.png" class="company_logo pme_logo">
		</header>

		<!-- PME START -->
		<div class="row">
			<div class="col-sm">
				<table id="pmeProp" class="display table table-borderless" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th class="pt_address_col">Address</th>
							<th class="pme_action_col">Action</th>
						</tr>
					</thead>                        
					<tbody>
					<?php 
					// get all connected properties
					$crm_connected_prop_sql_str = "
					SELECT `api_prop_id`
					FROM `api_property_data`
					WHERE `crm_prop_id` != ''
					AND `api` = {$api_id}
					";
					$crm_connected_prop_sql = $this->db->query($crm_connected_prop_sql_str);

					// get connection properties ID
					$api_prop_id_arr = [];
					foreach( $crm_connected_prop_sql->result() as $crm_conn_prop_row ){
						$api_prop_id_arr[] = $crm_conn_prop_row->api_prop_id; 
					}

					foreach ( $prop_tree_list as $address_obj_row ) {  
																	
						$hide_row = false;
						$address_obj_row_hl_class = null;              
						
						$api_prop_id = $address_obj_row->id;
						$address_obj = $address_obj_row->address;
						
						// street
						if( $address_obj->unit != '' && $address_obj->street_number != '' ){
							$street_unit_num = "{$address_obj->unit}/{$address_obj->street_number}";
						}else if( $address_obj->unit != '' ){
							$street_unit_num = "{$address_obj->unit}";
						}else if( $address_obj->street_number != '' ){
							$street_unit_num = "{$address_obj->street_number}";
						}
							
						$pt_prop_add = "{$street_unit_num} {$address_obj->address_line_1}, {$address_obj->suburb} {$address_obj->state} {$address_obj->post_code}";    

						//hide archive
						if( $address_obj_row->archived == true ){
							$hide_row = true;
						}

						// if API property already connected
						if( in_array($api_prop_id, $api_prop_id_arr) ){
							$hide_row = true; // hide row
						}
						

						if( $hide_row == false ){
					?>
					<tr class="<?php echo $address_obj_row_hl_class; ?>">
						<td class="pmeAdd"><?php echo $pt_prop_add; ?></td>
						<td>
							<button type="button" class="btn pme_prop_select_btn" sel-id="1">Select</button>

							<input type="hidden" class="pme_full_address" value="<?php echo $pt_prop_add; ?>" />

							<input type="hidden" class="pme_addr_unit" value="<?php echo $address_obj->unit; ?>" />
							<input type="hidden" class="pme_addr_number" value="<?php echo $address_obj->street_number; ?>" />
							<input type="hidden" class="pme_addr_street" value="<?php echo $address_obj->address_line_1; ?>" />
							<input type="hidden" class="pme_addr_suburb" value="<?php echo $address_obj->suburb; ?>" />                            
							<input type="hidden" class="pme_addr_postalcode" value="<?php echo $address_obj->post_code; ?>" />
							<input type="hidden" class="pme_addr_state" value="<?php echo $address_obj->state; ?>" />

							<input type="hidden" class="pme_addr_text" value="<?php echo $pt_prop_add; ?>" />   
							<input type="hidden" class="pme_addr_key" value="<?php echo $address_obj_row->key_number; ?>" />                                                                       
							
							<input type="hidden" class="pme_prop_id" value="<?php echo $api_prop_id; ?>" />
							<input type="hidden" class="api_prop_id" value="<?php echo $api_prop_id; ?>" />    
							<input type="hidden" class="pt_tenancy_id" value="<?php echo $address_obj_row->tenancy; ?>" />       
							<input type="hidden" class="pt_ownership_id" value="<?php echo $address_obj_row->ownership; ?>" />                              
							<input type="hidden" class="api_platform" value="<?php echo $api_id; ?>" />															
						
						</td>
					</tr>
					<?php    
						}         
					}
					?>
					</tbody>
				</table>
			</div>
		</div>        
        <script>
        // propertytree datatable initialize
        var table = jQuery('#pmeProp').DataTable({

			"columnDefs": [
				{
					"searchable": true,
					"targets": 'pt_address_col'
				},
				{
					"searchable": false,
					"targets": '_all'
				}
			]

        });            
        </script>
        <?php

    }

	public function ajax_get_pme_properties_old(){

		$agency_id = $this->input->get_post('agency_id');
		$api_id = 1; // PMe

		if( $agency_id > 0 ){

			$end_points = "https://app.propertyme.com/api/v1/lots";

			// get access token
			$pme_params = array(
				'agency_id' => $agency_id,
				'api_id' => $api_id
			);
			$access_token = $this->pme_model->getAccessToken($pme_params);

			$pme_params = array(
				'access_token' => $access_token,
				'end_points' => $end_points
			);

			$response =  $this->pme_model->call_end_points_v2($pme_params);
			$pme_prop_list_json = json_decode($response);


			if( count($pme_prop_list_json) > 0 ){ ?>

				<header class="steps-numeric-title">
					<img src="/images/third_party/pme_logo_transparent.png" class="company_logo pme_logo">
				</header>

				<!-- PME START -->
				<div class="row">

					<div class="col-sm">

					<table class="table" id="pme_prop_tbl">
						<thead>
							<th class="pme_address_col">Address</th>
							<th class="text-center">Connected in CRM</th>
							<th class="pme_action_col">Action</th>
						</thead>
						<tbody>
							<?php
							if( count($pme_prop_list_json) > 0 ){
								foreach($pme_prop_list_json as $pme_prop){
									//print_r($pme_prop_list_json);

									?>
										<tr class="pme_prop_row">
											<td class="pme_address_col">
												<?php echo $pme_prop->AddressText; ?>
												<span class="font-icon font-icon-ok step-icon-finish green_tick pme_prop_found_tick"></span>
											</td>


											<!-- Connected in CRM -->
											<td class="text-center">
												<?php
												$isConnectedCheck_str = "
													SELECT
														`property_id`,
														`address_1`,
														`address_2`,
														`address_3`,
														`state`,
														`postcode`,
														`deleted`
													FROM `property`
													WHERE `propertyme_prop_id` = '{$pme_prop->Id}'
													ORDER BY `address_2` ASC, `address_3` ASC, `address_1` ASC
												";
												$isConnectedCheck_sql = $this->db->query($isConnectedCheck_str);
												$connected_crm_prop_count = $isConnectedCheck_sql->num_rows();

												if( $connected_crm_prop_count > 0 ){

													echo '<ul>';
													foreach(  $isConnectedCheck_sql->result() as $connected_prop_row ){
														$connected_prop_full_add  = "{$connected_prop_row->address_1} {$connected_prop_row->address_2}, {$connected_prop_row->address_3} {$connected_prop_row->state}, {$connected_prop_row->postcode}";
														echo '<li><a target="_blank" href="/properties/details/?id='.$connected_prop_row->property_id.'" class="'. ( ( $exist_in_crm_row->deleted == 1 )?'txt_red':null ) .'">'.$connected_prop_full_add.'</a> <span class="font-icon font-icon-ok step-icon-finish green_tick"></span></li>';
													}
													echo '</ul>';

												}else{
													echo '<img src="/images/escalate_jobs/verify_nlm.png" class="icon_red_x" />';
												}
												?>
											</td>
											<td class="pme_action_col">

												<?php
												if( $connected_crm_prop_count > 0 ){ ?>
													<a href="javascript:void(0);" class="txt_red"><span class="fa fa-exclamation-triangle dup_icon_action"></span></a>
												<?php
												}
												?>

												<?php
												if( $connected_crm_prop_count == 0 ){ ?>
													<button type="button" class="btn pme_prop_select_btn">Select</button>
												<?php
												}
												?>

												<input type="hidden" class="pme_addr_unit" value="<?php echo $pme_prop->Address->Unit; ?>" />
												<input type="hidden" class="pme_addr_number" value="<?php echo $pme_prop->Address->Number; ?>" />
												<input type="hidden" class="pme_addr_street" value="<?php echo $pme_prop->Address->Street; ?>" />
												<input type="hidden" class="pme_addr_suburb" value="<?php echo $pme_prop->Address->Suburb; ?>" />
												<input type="hidden" class="pme_addr_postalcode" value="<?php echo $pme_prop->Address->PostalCode; ?>" />
												<input type="hidden" class="pme_addr_state" value="<?php echo $pme_prop->Address->State; ?>" />
												<input type="hidden" class="pme_addr_lat" value="<?php echo $pme_prop->Address->Latitude; ?>" />
												<input type="hidden" class="pme_addr_lng" value="<?php echo $pme_prop->Address->Longitude; ?>" />

												<input type="hidden" class="pme_addr_key" value="<?php echo $pme_prop->KeyNumber; ?>" />

												<input type="hidden" class="pme_prop_id" value="<?php echo $pme_prop->Id; ?>" />
												<input type="hidden" class="pme_prop_owner_contact_id" value="<?php echo $pme_prop->OwnerContactId; ?>" />
												<input type="hidden" class="pme_prop_tenants_contact_id" value="<?php echo $pme_prop->TenantContactId; ?>" />

											</td>
										</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
					<script>
					// run datatable
					var table = jQuery('#pme_prop_tbl').DataTable({

						"columnDefs": [
							{
								"searchable": true,
								"targets": 'pme_address_col'
							},
							{
								"searchable": false,
								"targets": '_all'
							}
						]

					});
					</script>

					</div>

				</div>
				<!-- PME END  -->

			<?php
			}

		}

	}

	public function ajax_get_ourtradie_properties(){

		$agency_id = $this->input->get_post('agency_id');
		$api_id = 6; // Ourtradie

		$unixtime 	= time();
        $now 		= date("Y-m-d H:i:s",$unixtime);

		$api = new OurtradieApi();

		if( $agency_id > 0 ){

			$this->checkToken($agency_id);

			// get access token
			$pme_params = array(
				'agency_id' => $agency_id,
				'api_id' => $api_id
			);
			$token['token'] = $this->ourtradie_model->getToken($agency_id, $api_id);

			$data['agency_name']  = $this->ourtradie_model->getAgencyEmail($agency_id);
			$agency_name = $data['agency_name'][0]->agency_name;

			$access_token   = $token['token'][0]->access_token;
			$tmp_ref_token   = $token['token'][0]->refresh_token;
			$tmp_arr_ref_token = explode("+/-]",$tmp_ref_token);

			$ot_agency_id = $tmp_arr_ref_token[1];
			$_SESSION['ot_agency_id'] = $agency_id;

			$token = array('access_token' => $access_token);

			//GetAllResidentialProperties
			$params = array(
				'Skip' 	 		=> 'No',
				'Count'     => 'No',
				'AgencyID'  => $ot_agency_id
			);
			$property = $api->query('GetAllResidentialProperties', $params, '', $token, true);

			$ot_prop_list_json = json_decode($property);

			$isConnectedCheck_str = "
				SELECT
					p.`property_id`,
					p.`address_1`,
					p.`address_2`,
					p.`address_3`,
					p.`state`,
					p.`postcode`,
					p.`deleted`,
					apd.api_prop_id,
					apd.api
				FROM `property` AS  p
				INNER JOIN `api_property_data` AS apd ON p.property_id = apd.crm_prop_id
				WHERE apd.`api_prop_id` IS NOT NULL AND apd.`api_prop_id` != '' AND apd.api = 6
				ORDER BY `address_2` ASC, `address_3` ASC, `address_1` ASC
			";
			$isConnectedCheck_sql = $this->db->query($isConnectedCheck_str);
	
			$arrConnected = $isConnectedCheck_sql->result();

			if( count($ot_prop_list_json) > 0 ){ ?>

				<header class="steps-numeric-title">
					<img src="/images/ourtradie.png" class="company_logo pme_logo">
				</header>

				<!-- OURTRADIE START -->
				<div class="row">

					<div class="col-sm">

					<table class="table" id="pme_prop_tbl">
						<thead>
							<th class="pme_address_col">Address</th>
							<th class="text-center">Connected in CRM</th>
							<th class="pme_action_col">Action</th>
						</thead>
						<tbody>
							<?php
							//$cntr = 0;
							if( count($ot_prop_list_json) > 0 ){
								foreach($ot_prop_list_json as $ot_prop){
									foreach($ot_prop as $row){

									?>
										<tr class="pme_prop_row">
											<td class="pme_address_col">
												<?php echo $row->Address1; ?> <span class="badge badge-primary">OurTradie</span>
												<span class="font-icon font-icon-ok step-icon-finish green_tick pme_prop_found_tick"></span>
											</td>


											<!-- Connected in CRM -->
											<td class="text-center">
												<?php
												$searchOTId = $row->ID;
												$filtered = array_filter($arrConnected, function($element)  use ($searchOTId) {
												    return $element->api_prop_id === $searchOTId;
												});

												if(!empty($filtered)){
													foreach(  $filtered as $connected_prop_row ){
														//print_r($connected_prop_row);
														$connected_prop_full_add  = "{$connected_prop_row->address_2}, {$connected_prop_row->state}, {$connected_prop_row->postcode}";
														echo '<a target="_blank" href="/properties/details/?id='.$connected_prop_row->property_id.'" class="'. ( ( $exist_in_crm_row->deleted == 1 )?'txt_red':null ) .'">'.$connected_prop_full_add.'</a> <span class="font-icon font-icon-ok step-icon-finish green_tick pme_prop_found_tick"></span>';
													}
												}

												else{
													echo '<img src="/images/escalate_jobs/verify_nlm.png" class="icon_red_x" />';
												}
												?>
											</td>
											<td class="pme_action_col">

												<?php
												if( count($filtered) > 0 ){ ?>
													<a href="javascript:void(0);" class="txt_red"><span class="fa fa-exclamation-triangle dup_icon_action"></span></a>
												<?php
												}
												?>

												<?php
												if( count($filtered) == 0 ){ ?>
													<button type="button" class="btn ot_prop_select_btn" sel-id="1">Select</button>
												<?php
												}
												?>

												<input type="hidden" class="ot_addr_unit" value="<?php echo $row->Address1; ?>" />
												<input type="hidden" class="ot_addr_suburb" value="<?php echo $row->Suburb; ?>" />
												<input type="hidden" class="ot_addr_postalcode" value="<?php echo $row->Postcode; ?>" />
												<input type="hidden" class="ot_addr_state" value="<?php echo $row->State; ?>" />
												<input type="hidden" class="ot_addr_key" value="<?php echo $row->KeyNumber; ?>" />
												<input type="hidden" class="ot_prop_id" value="<?php echo $row->ID; ?>" />

												<input type="hidden" class="ot_landlords_lists" value='<?php echo json_encode($row->Agency_Contacts); ?>' />

												<input type="hidden" class="ot_agency_contact_id" value="<?php echo $row->Agency_Contacts[0]->ID; ?>" />
												<input type="hidden" class="ot_agency_contact_name" value="<?php echo $row->Agency_Contacts[0]->Name; ?>" />
												<input type="hidden" class="ot_agency_contact_email" value="<?php echo $row->Agency_Contacts[0]->Email; ?>" />
												<input type="hidden" class="ot_agency_contact_mobile" value="<?php echo $row->Agency_Contacts[0]->Mobile; ?>" />

												<input type="hidden" class="ot_tenants_lists" value='<?php echo json_encode($row->Tenant_Contacts); ?>' />

												<input type="hidden" class="ot_tenant_contact_id" value="<?php echo $row->Tenant_Contacts[0]->ID; ?>" />
												<input type="hidden" class="ot_tenant_contact_name" value="<?php echo $row->Tenant_Contacts[0]->Name; ?>" />
												<input type="hidden" class="ot_tenant_contact_email" value="<?php echo $row->Tenant_Contacts[0]->Email; ?>" />
												<input type="hidden" class="ot_tenant_contact_mobile" value="<?php echo $row->Tenant_Contacts[0]->Mobile; ?>" />

												<input type="hidden" class="api_platform" value="6" />

											</td>
										</tr>
									<?php
									}
								}
							}
							?>
						</tbody>
					</table>
					<script>
					// run datatable
					var table = jQuery('#pme_prop_tbl').DataTable({

						"columnDefs": [
							{
								"searchable": true,
								"targets": 'pme_address_col'
							},
							{
								"searchable": false,
								"targets": '_all'
							}
						]

					});
					</script>

					</div>

				</div>
				<!-- PME END  -->

			<?php
			}

		}

	}

	public function getAgencyIntegratedAPI(){

		$this->load->model('agency_model');
		$this->load->model('api_model');

		$agency_id = $this->input->get_post('agency_id');
		$api_id = $this->input->get_post('api_id');
		$html_markup = null;

		//$api_sql = $this->agency_model->getIntegratedAPI($agency_id);

		// check if connected to API
		$sel_query = "
			agen_api_tok.`agency_api_token_id`,
			agen_api_tok.`agency_id`,
			agen_api_tok.`api_id`,

			agen_api.`api_name`
		";
		$api_token_params = array(
			'sel_query' => $sel_query,
			'active' => 1,
			'agency_id' => $agency_id,
			'display_query' => 0
		);
		$api_token_sql = $this->api_model->get_agency_api_tokens($api_token_params);

		if( $api_token_sql->num_rows() > 0 ){
			foreach( $api_token_sql->result() as $api_row ){

				$html_markup .= '
					<button type="button" id="api_buttons_'.$api_row->api_id.'" class="btn api_buttons " data-api_id="'.$api_row->api_id.'">
						'.$api_row->api_name.'
					</button>
				';
			}
		}else{
			$html_markup = 'NONE';
		}

		echo $html_markup;

	}


	public function select_api(){

		$agency_id = $this->input->get_post('agency_id');
		$api_id = $this->input->get_post('api_id');
		//$this->session->set_userdata('add_prop_sel_agency', $agency_id);
		$url = "/properties/add/?agency_id={$agency_id}&api_id={$api_id}";
		redirect($url);

	}


	public function get_pme_data(){

		$agency_id = $this->input->get_post('agency_id');
		$tenants_contact_id = $this->input->get_post('tenants_contact_id');
		$owner_contact_id = $this->input->get_post('owner_contact_id');

		$api_id = 1; // PMe

		$json_arr = [];

		// get tenants
		if( $tenants_contact_id ){

			// get Pme contacts
			$end_points = "https://app.propertyme.com/api/v1/contacts/{$tenants_contact_id}";

			// get access token
			$pme_params = array(
				'agency_id' => $agency_id,
				'api_id' => $api_id
			);
			$access_token = $this->pme_model->getAccessToken($pme_params);

			$pme_params = array(
				'access_token' => $access_token,
				'end_points' => $end_points
			);

			$json_arr['tenants'] = $this->pme_model->call_end_points_v2($pme_params);

		}

		// get landlord
		if( $owner_contact_id ){

			// get Pme contacts
			$end_points = "https://app.propertyme.com/api/v1/contacts/{$owner_contact_id}";

			// get access token
			$pme_params = array(
				'agency_id' => $agency_id,
				'api_id' => $api_id
			);
			$access_token = $this->pme_model->getAccessToken($pme_params);

			$pme_params = array(
				'access_token' => $access_token,
				'end_points' => $end_points
			);

			$json_arr['landlord'] =  $this->pme_model->call_end_points_v2($pme_params);

		}

		echo json_encode($json_arr);

	}



	/**
	 * ADD PROPERTY FUNCTION
	 * insert property
	 * insert tenants
	 * insert file
	 * insert property services
	 * inser jobs
	 */
	public function add_property(){

		if($this->input->post('btnAddProperty')){			
				$this->load->library('form_validation');
				$this->load->library('upload');
				$this->load->library('email');

				$agency_id = $this->input->post('agency');
				$remember = $this->input->post('remember_agency');

				$address_1 = $this->input->post('address_1');
				$address_2 = $this->input->post('address_2');
				$address_3 = $this->input->post('address_3');
				$state = $this->input->post('state');
				$postcode = $this->input->post('postcode');

				$holiday_rental = $this->input->post('holiday_rental');
				$other_supplier_job = $this->input->post('other_supplier_job');
				$other_supplier_job_date = ($this->input->post('other_supplier_job_date')!="")?date("Y-m-d",strtotime(str_replace("/","-",$this->input->post('other_supplier_job_date')))):NULL;

				// somehow the db setting is strict, it cannot be submitted if empty, needs to default to 0 if empty
				$service_garage = ( is_numeric($this->input->post('service_garage')) )?$this->input->post('service_garage'):0;

				$prop_vacant = $this->input->post('prop_vacant');
				$vacant_from = (!empty($this->input->post('vacant_from')))?date("Y-m-d H:i:s",strtotime(str_replace("/","-",$this->input->post('vacant_from')))): NULL;
				$vacant_to = (!empty($this->input->post('vacant_to')))?date("Y-m-d",strtotime(str_replace("/","-",$this->input->post('vacant_to')))): NULL;

				$landlord_firstname = $this->input->post('landlord_firstname');
				$landlord_lastname = $this->input->post('landlord_lastname');
				$landlord_moble = $this->input->post('landlord_mobile');
				$landlord_landline = $this->input->post('landlord_landline');
				$landlord_email = $this->input->post('landlord_email');
				$prop_upgraded_to_ic_sa = $this->input->post('prop_upgraded_to_ic_sa');

				$alarm_code = $this->input->post('alarm_code');
				$workorder_num = $this->input->post('workorder_num');
				$lockbox_code = $this->input->post('lockbox_code');
				$key_number = $this->input->post('key_number');

				$hid_allow_pm = $this->input->post('hid_allow_pm');
				$property_manager = ( $hid_allow_pm == 1 )?$this->input->post('property_manager'):'';

				$compass_index_num = $this->input->post('compass_index_num');
				$workorder_notes = $this->input->post('workorder_notes');

				$selected_ot_prop_id = $this->input->get_post('selected_ot_prop_id');

				// PME property ID
				$pme_prop_id = ( $this->input->get_post('selected_pme_prop_id') != '' )?$this->input->get_post('selected_pme_prop_id'):null;

				// Palace property ID
				$palace_prop_id = ( $this->input->get_post('selected_palace_prop_id') != '' )?$this->input->get_post('selected_palace_prop_id'):null;

				// Property Tree property ID
				$pt_prop_id = ( $this->input->get_post('selected_pt_prop_id') != '' )?$this->input->get_post('selected_pt_prop_id'):null;

				// Ourtradie property ID
				$ourtradie_prop_id = ( $this->input->get_post('selected_ot_prop_id') != '' )?$this->input->get_post('selected_ot_prop_id'):null;

				$duplicate_query = $this->properties_model->check_duplicate_property($address_1,$address_2,$address_3,$state,$postcode);

				$added_from_property_list = $this->input->post('added_from_property_list') == 1;

				$source_of_company_toggle_val = $this->input->post('source_of_company_toggle_val');


				if($duplicate_query->num_rows()>0){ //DUPLICATE PROPERTY

					//SHOW DUPLICATE ERROR
					$duplicate_row = $duplicate_query->row_array();
					$data['prop_id'] = $duplicate_row['property_id'];
					$data['status'] = ($duplicate_row['deleted']==1)?'Deactivated':'Active';
					$data['address'] = "{$duplicate_row['p_address_1']} {$duplicate_row['p_address_2']}, {$duplicate_row['p_address_3']} {$duplicate_row['state']} {$duplicate_row['postcode']}";
					$data['agency_id'] = $duplicate_row['agency_id'];
					$data['agency_name'] = $duplicate_row['agency_name'];

					$data['title'] = "Duplicate Property";
					$data['result_type'] = "duplicate";
					$this->load->view('templates/inner_header', $data);
					$this->load->view('properties/ajax_property/property_result', $data);
					$this->load->view('templates/inner_footer', $data);

				}else{ //No Duplicate property - continue adding-----

					// CI FORM VALIDATION
					$this->form_validation->set_rules('address_1', 'Street No.', 'required');
					$this->form_validation->set_rules('address_2', 'Sreet Name', 'required');
					$this->form_validation->set_rules('address_3', 'Suburb', 'required');
					$this->form_validation->set_rules('state', 'State', 'required');
					$this->form_validation->set_rules('postcode', 'Postcode', 'required');

					if ( $this->form_validation->run() == true ){ // Validation Ok

							// Hume Community Housing Association
							$prop_comments = '';
							if( $agency_id==1598 ){

								$prop_comments = 'Please install 9vLi or 240v only. DO NOT INSTALL 240vLi';

							}

							// replace some state, only on NZ
							if( $this->config->item('country') == 2 ){ // NZ
								$state = $this->system_model->replace_state($state);
							}

							// get lat and lng for mapping
							$address_str = "{$address_1} {$address_2} {$address_3} {$state} {$postcode}";
							$coordinate = $this->system_model->getGoogleMapCoordinates($address_str);


							// INSERT PROPERTY
							$property_data = [
								'agency_id' => $agency_id,
								'address_1' => $address_1,
								'address_2' => $address_2,
								'address_3' => $address_3,
								'state' => $state,
								'postcode' => $postcode,
								'added_by' => $this->session->staff_id,
								'key_number' => $key_number,
								'alarm_code' => $alarm_code,
								'holiday_rental' => $holiday_rental,
								'service_garage' => $service_garage,
								'landlord_firstname' => $landlord_firstname,
								'landlord_lastname' => $landlord_lastname,
								'landlord_email' => $landlord_email,
								'landlord_mob' => $landlord_moble,
								'landlord_ph' => $landlord_landline,
								'prop_upgraded_to_ic_sa' => $prop_upgraded_to_ic_sa,
								'pm_id_new' => $property_manager,
								'comments' => $prop_comments,
								'lat' => $coordinate['lat'],
								'lng' => $coordinate['lng'],
								'compass_index_num' => $compass_index_num,
								//'propertyme_prop_id' => $pme_prop_id,
								//'palace_prop_id' => $palace_prop_id,
								//'ourtradie_prop_id' => $ourtradie_prop_id,

                                // Default Emerald
								'preferred_alarm_id' => 22,
                                'is_nlm' => 0,
                            ];

							//print_r($property_data);
							//exit();

							$add_property = $this->properties_model->add_property($property_data);


							$prop_insert_id = $this->db->insert_id();

							if($pme_prop_id != ''){
								$separated_data = array(
									'crm_prop_id' => $prop_insert_id,
									'api'         => 1,
									'api_prop_id' => $pme_prop_id
								);
								
								$add_separated_data = $this->properties_model->add_data_property($separated_data);
							}

							if($palace_prop_id != ''){
								$separated_data = array(
									'crm_prop_id' => $prop_insert_id,
									'api'         => 4,
									'api_prop_id' => $palace_prop_id
								);
								
								$add_separated_data = $this->properties_model->add_data_property($separated_data);
							}							
					
							// property tree
							if($pt_prop_id != ''){
								$separated_data = array(
									'crm_prop_id' => $prop_insert_id,
									'api'         => 3,
									'api_prop_id' => $pt_prop_id
								);
								
								$add_separated_data = $this->properties_model->add_data_property($separated_data);
							}

							if( $prop_insert_id > 0 ){

								// clear "cant connect to API" marker
								$this->db->where('property_id', $prop_insert_id);
								$this->db->delete('property_cant_connect_to_api');

							}

							if($ourtradie_prop_id != ''){
								$separated_data = array(
									'crm_prop_id' => $prop_insert_id,
									'api'         => 6,
									'api_prop_id' => $ourtradie_prop_id
								);
								
								$add_separated_data = $this->properties_model->add_data_property($separated_data);
							}

							if($add_property && !empty($prop_insert_id)){ // success adding property

								//INsert VPD LOG FOR NEW PROPERTY
								if ($pme_prop_id != '') {
									$prop_log_details = 'Added to match Active property on PropertyMe';
								}else if ($palace_prop_id != '') {
									$prop_log_details = 'Added to match Active property on Palace';
								}else if ($ourtradie_prop_id != '') {
									$prop_log_details = 'Added to match Active property on OurTradie';
								}else {
									$prop_log_details = 'New Property';
								}

								if ($added_from_property_list){
									$prop_log_details = 'This property was added from a property list';
								}

								$params = array(
									'title' => 2, //New Property Added
									'details' => $prop_log_details,
									'display_in_vpd' => 1,
									'agency_id' => $agency_id,
									'created_by_staff' => $this->session->staff_id,
									'property_id' => $prop_insert_id
								);
								$this->system_model->insert_log($params);

								// check if lockbox exist
								$lb_sql = $this->db->query("
								SELECT COUNT(`id`) AS pl_count
								FROM `property_lockbox`
								WHERE `property_id` = {$prop_insert_id}
								");
								$lb_row = $lb_sql->row();
					
								if( $lb_row->pl_count > 0 ){ // it exist, update
					
									$this->db->query("
									UPDATE `property_lockbox`
									SET `code` = '{$lockbox_code}'
									WHERE `property_id` = {$prop_insert_id}
									");
					
								}else{ // doesnt exist, insert
					
									if( $lockbox_code != '' ){
					
										$this->db->query("
										INSERT INTO 
										`property_lockbox`(
											`code`,
											`property_id`
										)
										VALUE(
											'{$lockbox_code}',
											{$prop_insert_id}
										)	
										");
					
									}		
					
								}

								// Insert if source_of_company toggle is ON 
								if ($source_of_company_toggle_val == 1) {
									//START -- insert properties_from_other_company
									$added_date = $added_date = date('Y-m-d');
									$agency_from_other_company_property_val = (int)$this->input->post('agency_from_other_company_property_val');
									$data = array(
										'property_id'       => $prop_insert_id,
										'company_id'        => $agency_from_other_company_property_val,
										'active'            => 1,
										'added_date'        => $added_date
									);    
									$this->properties_model->save_properties_from_other_company($data);
									//END 
								}

								//INSERT TENNANTS
								$tenant_priority_arr = $this->input->post('tenant_priority');
								$tenant_firstname_arr = $this->input->post('tenant_firstname');
								$tenant_lastname_arr = $this->input->post('tenant_lastname');
								$tenant_ph_arr = $this->input->post('tenant_ph');
								$tenant_mob_arr = $this->input->post('tenant_mob');
								$tenant_email_arr = $this->input->post('tenant_email');

								if(!empty($this->input->post('tenant_firstname')) || !empty($this->input->post('tenant_lastname'))){
										foreach($tenant_firstname_arr as $index => $tenant_fname_val){
											if($tenant_fname_val!="" || $this->input->post('tenant_lastname')[$index]!=""){
												$post_array[] = array(
													'property_id' =>  $prop_insert_id,
													'tenant_firstname' => $tenant_fname_val,
													'tenant_lastname' => $tenant_lastname_arr[$index],
													'tenant_mobile' => $tenant_mob_arr[$index],
													'tenant_landline' => $tenant_ph_arr[$index],
													'tenant_email' => $tenant_email_arr[$index],
													'active' => 1,
													'tenant_priority' => $tenant_priority_arr[$index]
												);
											}
										}
										if(!empty($post_array)){
											$this->properties_model->add_tenants($post_array, 'batch'); //  param insert batch otherwise 0 for normal
										}
								}
								//INSERT TENNANTS END

								//UPLOAD FILE
								$filename = preg_replace('/#+/', 'num', $_FILES['fileupload']['name']);
								$filename2 = preg_replace('/\s+/', '_', $filename);
								$filename3 = rand().date('YmdHis')."_".$filename2;
								$upload_path = "./uploads/property_files/";
								$upload_folder = "/uploads/property_files/"; //note without dot

								//make directory if not exist and set permission to 777
								if(!is_dir($upload_folder)){
									mkdir($upload_path,0777,true);
								}

								if(!empty($_FILES['fileupload']['name'])){
									$_FILES['file']['name'] = $_FILES['fileupload']['name'];
									$_FILES['file']['type'] = $_FILES['fileupload']['type'];
									$_FILES['file']['tmp_name'] = $_FILES['fileupload']['tmp_name'];
									$_FILES['file']['error'] = $_FILES['fileupload']['error'];
									$_FILES['file']['size'] = $_FILES['fileupload']['size'];

									//set upload config
									$upload_params = array(
										'file_name' => $filename3,
										'upload_path' => $upload_path,
										'max_size' => '1024', //1mb
										'allowed_types' => 'gif|jpg|jpeg|png|pdf|csv|docx|xlsx|xls'
									);
									$upload = $this->gherxlib->do_upload('file',$upload_params);
									if($upload){

										//upload data
										$uploadData = $this->upload->data();

										//insert files upload data to DB
										$file_data = array(
											'property_id' => $prop_insert_id,
											'path' => $upload_folder,
											'filename' => $uploadData['file_name'],
											'date_created' => date("Y-m-d H:i:s")
										);
										$this->properties_model->isnert_property_files($file_data);

										//insert file upload log
										$details = "{$uploadData['file_name']} Uploaded";
										$params = array(
											'title' => 41, //file upload
											'details' => $details,
											'display_in_vpd' => 1,
											'agency_id' => $agency_id,
											'created_by_staff' => $this->session->staff_id,
											'property_id' => $prop_insert_id,
										);
										$this->system_model->insert_log($params);

								}else{
									$error = array('error' => $this->upload->display_errors());
									$data['upload_error'] = $error['error'];
								}
								}
								//UPLOAD FILE END


								//INSERT PROPERTY SERVICES
								$alarm_job_type_id  = $this->input->post('alarm_job_type_id');
								$price = $this->input->post('price');
								$price_changed = $this->input->post('price_changed');
								$price_reason = $this->input->post('price_reason');
								$price_details = $this->input->post('price_details');

								$is_payable = 0;

								foreach($alarm_job_type_id as $index=>$val){ //$val = alarm_job_type_id

										$services = $this->input->post('service'.$index);

										// dont insert property services if NR(2), requested by Ben
										if( $services != 2 || ($price[$index]!="" && $services==2 && $price_changed[$index]==1) ){

											// insert property services -----
											$prop_services_array = array(
												'property_id' => $prop_insert_id,
												'alarm_job_type_id' => $val,
												'service' => $services,
												'price' => $price[$index],
												'status_changed' => date("Y-m-d H:i:s"),
											);

											// if service = SATS, mark as payable
											if( $services == 1 ){
												$is_payable = $prop_services_array['is_payable'] = 1;
											}

											$this->properties_model->add_property_services($prop_services_array);

										}

										//iff picked jobs = sats > add jobs -----
										if($services==1){

												//Insert Property TYpe
												$prop_type_post = array(
													'property_id' => $prop_insert_id,
													'alarm_job_type_id' => $val
												);
												$this->properties_model->add_property_type($prop_type_post);

												// IF DHA agencies, franchise group = 14(Defence Housing)
												$franchise_groups_id = $this->db->select('franchise_groups_id')->from('agency')->where('agency_id', $agency_id)->get()->row()->franchise_groups_id; //get franchise group id by agency id

												if($this->system_model->isDHAagenciesV2($franchise_groups_id)){

														$tech_notes = $this->input->post('tech_notes');
														//$start_date = ($this->input->post('start_date')!="")?date('Y-m-d', strtotime($this->input->post('start_date'))):NULL;
														//$due_date = ($this->input->post('due_date')!="")?date('Y-m-d', strtotime($this->input->post('due_date'))):NULL;

														$start_date = ( $this->input->get_post('start_date') != '' )?$this->system_model->formatDate($this->input->get_post('start_date')):NULL;
														$due_date = ( $this->input->get_post('due_date') != '' )?$this->system_model->formatDate($this->input->get_post('due_date')):NULL;

														$jt_txt = 'Once-off';
														$s_txt = 'DHA';

														//set additional fields to insert jobs
														$add_field_array = array(
															'tech_notes' => $tech_notes,
															'start_date' => $start_date,
															'due_date' => $due_date
														);

												}else{	///not DHA

														$jt_txt = 'Yearly Maintenance';


														if($other_supplier_job ==1){ // if other supplier

															$s_txt = 'Completed';

															//set additional fields to insert jobs
															$add_field_array = array(
																'tech_notes' => NULL,
																'start_date' => $vacant_from,
																'due_date' => $vacant_to,
																'date' => $other_supplier_job_date,
																'assigned_tech' => 1
															);

														}else{

															$s_txt = 'Send Letters';
															//set additional fields to insert jobs
															$add_field_array = array(
																'tech_notes' => NULL,
																'start_date' => $vacant_from,
																'due_date' => $vacant_to
															);

														}



												}

												// if agency is DHA agencies with franchise group = 14(Defence Housing) OR if agency has maintenance program
												if( $this->system_model->isDHAagenciesV2($franchise_groups_id)==true || $this->system_model->agencyHasMaintenanceProgram($agency_id)==true   ){
													$dha_need_processing = 1;
												}

												$price_var_params = array(
													'service_type' => $alarm_job_type_id[$index],
													'property_id' => $prop_insert_id
												);
												$price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
												$price_text = substr($price_var_arr['price_text'],1);

												/*
												//Last Query
												echo $this->db->last_query();
												echo "<br /><br />";

												// Testing Purposes
												echo "Property ID:";
												echo "<br />";
												echo $prop_insert_id;
												echo "<br /><br />";

												echo "Service Type:";
												echo "<br />";
												echo $alarm_job_type_id[$index];
												echo "<br /><br />";
												
												echo "Results:";
												echo "<br />";
												print_r($price_var_arr);
												echo "<br /><br />";

												echo "Price <br />";
												$price_text = substr($price_var_arr['price_text'],1);
												echo $price_text;
												echo "<br /><br />";
												exit();
												*/

												//Insert Jobs
												$job_post_array = array(
													'job_type' => $jt_txt,
													'property_id' => $prop_insert_id,
													'status' => $s_txt,
													'work_order' => $workorder_num,
													'service' => $val,
													'job_price' => $price_text,
													'property_vacant' => $prop_vacant,
													'dha_need_processing' => $dha_need_processing,
													'comments' => $workorder_notes
												) + $add_field_array;
												// insert jobs - return last id
												$job_id = $this->properties_model->add_jobs($job_post_array);


												// AUTO - UPDATE INVOICE DETAILS
												$this->system_model->updateInvoiceDetails($job_id);

												// If BUNDLE > INSERT BUNDLE SERVICES
												$ajt = $this->db->select('*')->from('alarm_job_type')->where('id',$val)->get()->row_array(); //get ajt

												if($ajt['bundle']==1){ //if bundle
													$b_ids = explode(",",trim($ajt['bundle_ids']));
													// insert bundles
													foreach($b_ids as $val){
														$ajt_post_arr = array('job_id'=>$job_id, 'alarm_job_type_id'=> $val);
														$this->db->insert('bundle_services',$ajt_post_arr);
													}
												}

										}

										// if job is alarms popupate alarms
										if($val==2){

											$aa_sql = $this->db->select('*')->from('agency_alarms')->where('agency_id', $agency_id)->get(); //get current agency alarm power and price

											if( $aa_sql->num_rows()>0 ){

												foreach($aa_sql->result_array() as $aa){
													$pa_post_arr = array('property_id'=>$prop_insert_id,'alarm_pwr_id'=>$aa['alarm_pwr_id'],'price'=>$aa['price']);
													$this->db->insert('property_alarms',$pa_post_arr);
												}

											}

										}

										// Changed Price Log
										if($price_changed[$index]==1){

											$serv = "";
											switch($val){
												case 2:
													$serv = "Smoke Alarms";
												break;
												case 5:
													$serv = "Safety Switch";
												break;
												case 6:
													$serv = "Corded Windows";
												break;
												case 7:
													$serv = "Pool Barriers";
												break;
											}

											$details = "New Price for {$serv}- $".$price[$index].", Reason- ".$price_reason[$index].", Details- ".$price_details[$index];
											$params = array(
												'title' => 42, //Price Changed
												'details' => $details,
												'display_in_vpd' => 1,
												'agency_id' => $agency_id,
												'created_by_staff' => $this->session->staff_id,
												'property_id' => $prop_insert_id
											);
											$this->system_model->insert_log($params);

										}
										// Changed Price Log End

								}

								if($is_payable == 1){
									$prop_log_details = 'This property is payable';
									$params = array(
										'title' => 88, //Is Payable
										'details' => $prop_log_details,
										'display_in_vpd' => 1,
										'agency_id' => $agency_id,
										'created_by_staff' => $this->session->staff_id,
										'property_id' => $prop_insert_id
									);
									$this->system_model->insert_log($params);
								}

							}

							//CREATE SESSION FOR REMEMBER AGENCY CHECKBOX
							if($remember==1){
								$remember_session = array('remember_agency'=>1,'remember_agency_id'=> $agency_id, 'rem_fg_id'=> $franchise_groups_id);
								$this->session->set_userdata($remember_session);
						}else{
								$aw_unset_arr = array('remember_agency','remember_agency_id','rem_fg_id');
								$this->session->unset_userdata($aw_unset_arr);
						}


							//get property data pass to result page
							$data['title'] = "Success";
							$res_prop_query = $this->db->select('property_id,address_1,address_2,address_3,state,postcode')->from('property')->where('property_id',$prop_insert_id)->get()->row_array();
							$data['res_prop_id'] = $res_prop_query['property_id'];
							$data['res_prop_address'] = "{$res_prop_query['address_1']} {$res_prop_query['address_2']}, {$res_prop_query['address_3']} {$res_prop_query['state']} {$res_prop_query['postcode']} ";

							/*
							$data['result_type'] = "success";
							$this->load->view('templates/inner_header', $data);
							$this->load->view('properties/ajax_property/property_result', $data);
							$this->load->view('templates/inner_footer', $data);
							*/

							$new_prop_added_crm_link = $this->gherxlib->crmLink('vpd',$res_prop_query['property_id'], $data['res_prop_address']);
							$success_message = "Property Below Successfully Added <br/> {$new_prop_added_crm_link}";
							//$this->session->set_flashdata(array('success_msg'=>$success_message,'status'=>'success'));
							$this->session->set_userdata('gherx_msg', $success_message);
							redirect(base_url('/properties/add'),'refresh');


					}else{ //validation end
							$error_msg = "Error: Required field must not be empty";
							//$this->session->set_flashdata(array('error_msg'=>$error_msg,'status'=>'error'));
							$this->session->set_userdata('gherx_error_msg', $error_msg);
							redirect(base_url('/properties/add'),'refresh');
					}

				}


	}else{
		redirect('/properties/add','refresh');
	}
}



// check propety dupctcate-------
public function check_property_duclicate(){

	$complete_address = $this->input->post('complete_address');
	$address_1 = $this->input->post('address_1');
	$address_2 = $this->input->post('address_2');
	$address_3 = $this->input->post('address_3');
	$state = $this->input->post('state');
	$postcode = $this->input->post('postcode');
	$res = $this->properties_model->check_duplicate_property($address_1,$address_2,$address_3,$state,$postcode);
	$res_row = $res->row_array();
	if($res->num_rows()>0){
		$jData['match'] =  1;
		$jData['agency_id'] = $res_row['agency_id'];
		$jData['property_id'] =  $res_row['property_id'];
		$jData['agency_name'] =  $res_row['agency_name'];
	}else{
		$jData['match'] =  0;
	}
	echo json_encode($jData);

}



public function check_property_duplicate(){

	$complete_address = $this->input->post('complete_address');
	$address_1 = $this->input->post('address_1');
	$address_2 = $this->input->post('address_2');
	$address_3 = $this->input->post('address_3');
	$state = $this->input->post('state');
	$postcode = $this->input->post('postcode');

	$params = array(
		'street_num_fin' => $address_1,
		'street_name_fin' => $address_2,
		'suburb' => $address_3,
		'state' => $state,
		'postcode' => $postcode
	);
	$res = $this->properties_model->check_duplicate_full_address($params);
	$res_row = $res->row_array();
	if($res->num_rows()>0){
		$jData['match'] =  1;
		$jData['agency_id'] = $res_row['agency_id'];
		$jData['property_id'] =  $res_row['property_id'];
		$jData['agency_name'] =  $res_row['agency_name'];
	}else{
		$jData['match'] =  0;
	}
	echo json_encode($jData);

}



public function active_job_properties_v2()
{

	$title = "Active Job Properties V2";
	$uri = '/properties/active_job_properties_v2';

	$agency_filter = $this->input->get_post('agency_filter');
	$search = $this->input->get_post('search');

	$state_ms = $this->input->get_post('state_ms');
	$data['state_ms_json'] = json_encode($state_ms);
	$region_ms = $this->input->get_post('region_ms');
	$data['region_ms_json'] = json_encode($region_ms);
	$sub_region_ms = $this->input->get_post('sub_region_ms');
	$data['sub_region_ms_json'] = json_encode($sub_region_ms);

	if (!empty($sub_region_ms)) {
		$postcodes = $this->system_model->getPostCodeViaSubRegion($sub_region_ms);
	}

	$export = $this->input->get_post('export');

	// pagination
	//$per_page = $this->config->item('pagi_per_page');
	$per_page = 100;
	$offset = $this->input->get_post('offset');


	$sel_query = "
	p.`property_id`,
	p.`address_1` AS p_address_1,
	p.`address_2` AS p_address_2,
	p.`address_3` AS p_address_3,
	p.`state` AS p_state,
	p.`postcode` AS p_postcode,
	p.`created`,

	ps.`alarm_job_type_id` AS ajt_id,
	ps.`price` AS ps_price,

	ajt.`type` AS ajt_type,

	a.`agency_id`,
	a.`agency_name`
	";

	// paginated
	$params = array(
		'sel_query' => $sel_query,

		'p_deleted' => 0,
		'a_status' => 'active',
		'ps_service' => 1,

		'agency_filter' => $agency_filter,
		'postcodes' => $postcodes,

		'search' => $search,

		'join_table' => array('property_services'),

		'custom_joins' => array(
			'join_table' => 'alarm_job_type AS ajt',
			'join_on' => 'ps.`alarm_job_type_id` = ajt.`id`',
			'join_type' => 'left'
		),

		'sort_list' => array(
			array(
				'order_by' => 'a.`agency_id`',
				'sort' => 'ASC',
			)
		),

		'display_query' => 0
	);


	if( $export == 1 ){

		$params['limit'] = $this->input->get_post('limit');
		$params['offset'] = $offset;

		$sql = $this->properties_model->get_properties($params);

		// file name
        $filename = preg_replace('/\s+/', '_', $title)."_".date("M/Y",strtotime("+1 month")).".csv";

        header("Content-Type: text/csv");
        header("Content-Disposition: Attachment; filename={$filename}");
        header("Pragma: no-cache");

        // headers
		$str = "Property ID,Date Added,Address,Agency ID,Agency,Service,Amount,Last Billed,Next Service Due,Last Visit\n";
		foreach( $sql->result() as $row ){

			$date_added = ($this->system_model->isDateNotEmpty($row->created))?date('d/m/Y', strtotime($row->created)):null;
			$address = "{$row->p_address_1} {$row->p_address_2}, {$row->p_address_3} {$row->p_state} {$row->p_postcode}";
			$amount = '$'.$row->ps_price;

			// last YM completed
			$job_sql = $this->db->query("
			SELECT `id`, `date`
				FROM `jobs`
				WHERE `property_id` = {$row->property_id}
				AND `job_type` = 'Yearly Maintenance'
				AND `status` = 'Completed'
				AND `del_job` = 0
				ORDER BY `date` DESC
				LIMIT 1
			");
			$job_row = $job_sql->row();
			$last_billed = ( $this->system_model->isDateNotEmpty($job_row->date) )?$this->system_model->formatDate($job_row->date,'d/m/Y'):null;
			$next_service_due = ( $this->system_model->isDateNotEmpty($job_row->date) )?date("F Y",strtotime($job_row->date.' +1 year')):null;
			$last_visit = $this->jobs_model->get_last_visit_per_property($row->property_id);

			$str .= "{$row->property_id},{$date_added},\"{$address}\",{$row->agency_id},\"{$row->agency_name}\",\"{$row->ajt_type}\",{$amount},{$last_billed},{$next_service_due},{$last_visit}\n";

		}

		echo $str;

	}else{

		$params['limit'] = $per_page;
		$params['offset'] = $offset;

		$data['lists'] = $this->properties_model->get_properties($params);

		//Get all rows
		$sel_query = "p.`property_id`";
		$params = array(
			'sel_query' => $sel_query,
			'p_deleted' => 0,
			'a_status' => 'active',
			'ps_service' => 1,

			'join_table' => array('property_services'),

			'agency_filter' => $agency_filter,
			'postcodes' => $postcodes,

			'search' => $search,

			'display_query' => 0
		);
		$query = $this->properties_model->get_properties($params);
		$total_rows = $query->num_rows();

		// get Service Types
		$sel_query = "ajt.`id`, ajt.`type`, ajt.`short_name`";
		$params = array(
			'sel_query' => $sel_query,
			'active' => 1,
			'display_query' => 0
		);
		$data['service_types'] = $this->system_model->getServiceTypes($params);

		//Agency name filter
		$sel_query = "DISTINCT(a.`agency_id`), a.`agency_name`";
		$params = array(
			'sel_query' => $sel_query,
			'p_deleted' => 0,
			'a_status' => 'active',
			'ps_service' => 1,

			'join_table' => array('property_services'),

			'sort_list' => array(
				array(
					'order_by' => 'a.`agency_name`',
					'sort' => 'ASC',
				),
			),
			'display_query' => 0
		);
		$data['agency_filter_json'] = json_encode($params);

		// Region Filter ( get distinct state )
		$sel_query = "DISTINCT(p.`state`)";
		$region_filter_arr = array(
			'sel_query' => $sel_query,
			'p_deleted' => 0,
			'a_status' => 'active',
			'ps_service' => 1,

			'join_table' => array('property_services'),

			'sort_list' => array(
				array(
					'order_by' => 'p.`state`',
					'sort' => 'ASC',
				)
			),
			'display_query' => 0
		);
		$data['region_filter_json'] = json_encode($region_filter_arr);

		$pagi_links_params_arr = array(
			'agency_filter' => $agency_filter,
			'search' => $search,
			'sub_region_ms' => $sub_region_ms
		);
		$pagi_link_params = $uri.'/?' . http_build_query($pagi_links_params_arr);


		// pagination settings
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'offset';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['base_url'] = $pagi_link_params;

		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();

		// pagination count
		$pc_params = array(
			'total_rows' => $total_rows,
			'offset' => $offset,
			'per_page' => $per_page
		);

		// data
		$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
		$data['export_link'] = $uri.'/?export=1' . http_build_query($pagi_links_params_arr);
		$data['title'] = $title;
		$data['uri'] = $uri;
		$data['total_rows'] = $total_rows;

		$this->load->view('templates/inner_header', $data);
		$this->load->view($uri, $data);
		$this->load->view('templates/inner_footer', $data);

	}


}

public function duplicate_properties(){

	// pagination
	$per_page = $this->config->item('pagi_per_page');
	$offset = $this->input->get_post('offset');
	$page_url = '/properties/duplicate_properties';
	$uri = $page_url;
    $data['uri'] = $uri;
	$agency_filter = $this->input->post('agency_filter');

	$sel_query = " p.property_id, p.`address_1`, p.`address_2`, p.`address_3`, p.`state`, p.`postcode`, p.`deleted`, COUNT( * ) AS jcount, a.`agency_id`,a.`agency_name`";

	/*if($agency_filter!=""){
		$custom_where = "a.agency_id = {$agency_filter}";
	}else{
		$custom_where = null;
	}*/

	if($agency_filter>0){
		$agency_filter2 = $agency_filter;
	}else{
		$agency_filter2 = null;
	}

	$export = $this->input->get_post('export');

	$params = array(
		'sel_query' => $sel_query,
		'agency_filter' => $agency_filter2		
	);	

	if ($export == 1) { //EXPORT         
            
            
		// file name
		$date_export = date('YmdHis');
		$filename = "duplicate_properties_{$date_export}.csv";

		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Pragma: no-cache");
		header("Expires: 0");

		// file creation 
		$csv_file = fopen('php://output', 'w');            

		$header = array('Property ID','Address','Suburb','Postcode','State','Agency Name','Status');
		fputcsv($csv_file, $header);

		
		$main_list = $this->properties_model->jFindDupProp($params);		
		foreach ( $main_list->result() as $row ){ 

			$csv_row = [];                              

			$csv_row[] = $row->property_id;
			$csv_row[] = "{$row->address_1} {$row->address_2}";
			$csv_row[] = $row->address_3;                
			$csv_row[] = $row->postcode;
			$csv_row[] = $row->state;
			$csv_row[] = $row->agency_name;
			$csv_row[] = ( $row->deleted == 1 )?'Inactive':'Active';
			
			fputcsv($csv_file,$csv_row); 
			
			$dup_sql2 = $this->properties_model->jGetOtherDupProp($row->property_id,$row->address_1,$row->address_2,$row->address_3,$row->state,$row->postcode);
								
			if(!empty($dup_sql2)){

				foreach($dup_sql2->result() as $row2){

					$csv_row = [];                              

					$csv_row[] = $row2->property_id;
					$csv_row[] = "{$row2->address_1} {$row2->address_2}";
					$csv_row[] = $row2->address_3;                
					$csv_row[] = $row2->postcode;
					$csv_row[] = $row2->state;
					$csv_row[] = $row2->agency_name;
					$csv_row[] = ( $row2->deleted == 1 )?'Inactive':'Active';
					
					fputcsv($csv_file,$csv_row); 

				}
				
			}

		}
		
	
		fclose($csv_file); 
		exit; 
		
		
	}else{

		$params['limit'] = $per_page;
		$params['offset'] = $offset;

		$data['lists'] = $this->properties_model->jFindDupProp($params);
		$data['last_query'] = $this->db->last_query();

		$params_total = array(
			'sel_query' => "COUNT( * ) AS jcount",
			'agency_filter' => $agency_filter2
			//'limit' => $per_page,
			//'offset' => $offset,
		);
		$query = $this->properties_model->jFindDupProp($params_total);
		$total_rows = $query->num_rows();
	
		//agency filter
		$param_agency_filter = array(
			'sel_query' => "DISTINCT(a.agency_id), a.agency_id, a.agency_name, COUNT( * ) AS jcount",
			'sort_list' => array(
				array(
					'order_by' => 'a.agency_name',
					'sort' => 'ASC'
				)
			)
		);
		$data['agency_filter'] = $this->properties_model->jFindDupProp($param_agency_filter);
	
		// update page total
		$page_tot_params = array(
			'page' => $page_url,
			'total' => $total_rows
		);
		$this->system_model->update_page_total($page_tot_params);
	
		$pagi_links_params_arr = array(
			'agency_filter' => $agency_filter
		);
		$pagi_link_params = '/properties/duplicate_properties/?' . http_build_query($pagi_links_params_arr);

		// export link
		$data['export_link'] = "{$uri}/?export=1&".http_build_query($pagi_links_params_arr);
	
		// pagination settings
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'offset';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['base_url'] = $pagi_link_params;
	
		$this->pagination->initialize($config);
	
		$data['pagination'] = $this->pagination->create_links();
	
		// pagination count
		$pc_params = array(
			'total_rows' => $total_rows,
			'offset' => $offset,
			'per_page' => $per_page
		);
	
		$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
	
		$data['title'] = "Duplicate Properties";
		$this->load->view('templates/inner_header', $data);
		$this->load->view('/properties/duplicate_properties', $data);
		$this->load->view('templates/inner_footer', $data);

	}	

}

public function duplicate_postcode(){

	//$data['duplicate'] = $this->properties_model->getPostcodeDuplicates(); disabled > use v2 fetched from new table
	$data['duplicate'] = $this->properties_model->getPostcodeDuplicatesV2();
	$page_url = '/properties/duplicate_postcode';
	$data['page_url'] = $page_url;

	$data['title'] = "Duplicate Postcode";
	$this->load->view('templates/inner_header', $data);
	$this->load->view('/properties/duplicate_postcode', $data);
	$this->load->view('templates/inner_footer', $data);

}

public function view_regions($postcode=NULL){

	if( $postcode && $postcode!="" && is_numeric(($postcode)) ){

		//get regions
		$params = array(
			'sel_query' => '*, r.region_state, sr.sub_region_id as postcode_region_id',
			'postcode' => $postcode,
			'delete' => 0,
			'sort_list' => array(
				array(
					'order_by' => 'r.region_name',
					'sort' => 'ASC'
				)
			)
		);
		$data['regions'] = $this->system_model->get_postcodes($params);

		$data['title'] = "View Regions";
		$this->load->view('templates/inner_header', $data);
		$this->load->view('/properties/view_regions', $data);
		$this->load->view('templates/inner_footer', $data);

	}else{
		redirect('/properties/duplicate_postcode','refresh');
	}


}

public function deleteRegion(){

	$id = $this->input->post('id');

	if($id!=""){
		$update_data = array(
			'deleted' => 1
		);
		$this->db->where('sub_region_id', $id);
		$this->db->update('postcode',$update_data);
		$this->db->limit(1);

	}

}

public function add_main_region(){

	$submit = $this->input->post('submit');

	if($submit){ // ADD NEW
		$region_name = $this->input->post('region_name');
		$state = $this->input->post('state');

		if( !empty($region_name) && !empty($state) ){

			$data = array(
				'region_name' => $region_name,
				'region_state' => $state,
				'country_id' => $this->config->item('country'),
				'status' => 1
			);
			$this->db->insert('regions', $data);
			$this->db->limit(1);

			//success session
			$success_message = "New Region has been created";
			$this->session->set_flashdata(array('success_msg'=>$success_message,'status'=>'success'));
			redirect(base_url('/properties/add_main_region'),'refresh');

		}else{

			$error_message = "Required field must not be empty.";
			$this->session->set_flashdata(array('error_msg'=>$error_message,'status'=>'error'));
			redirect(base_url('/properties/add_main_region'),'refresh');

		}

	}else{ // LIST VIEW
		$data['title'] = "Add Region";
		$this->load->view('templates/inner_header', $data);
		$this->load->view('/properties/add_main_region', $data);
		$this->load->view('templates/inner_footer', $data);
	}

}

public function next_service() {

	
	$data['title'] = "Next Service";
	$uri = '/properties/next_service';
	$data['uri'] = $uri;

	$date_filter = ( $this->input->get_post('date_filter') !='' )?$this->system_model->formatDate($this->input->get_post('date_filter')):null;
	$agency_filter = $this->input->get_post('agency_filter');
	$state_filter = $this->input->get_post('state_filter');
	$country_id = $this->config->item('country');
	$export = $this->input->get_post('export');

	//Additiona date filter - Chops
	/*
	$date_filter_to   = $this->input->get_post('date_filter_to');
	$date_filter_from = $this->input->get_post('date_filter_from');
	*/

	$input_date_filter_to   = $this->input->get_post('date_filter_to');
	$input_date_filter_from = $this->input->get_post('date_filter_from');

	if(!empty($input_date_filter_to)){
		$tmp_date_filter_to = str_replace('/', '-', $input_date_filter_to);
		$date_filter_to = date("Y-m-d", strtotime($tmp_date_filter_to));
	}
	else{
		$date_filter_to = "";
	}
	
	if(!empty($input_date_filter_from)){
		$tmp_date_filter_from = str_replace('/', '-', $input_date_filter_from);
		$date_filter_from = date("Y-m-d", strtotime($tmp_date_filter_from));
	}
	else{
		$date_filter_from = "";
	}

	// pagination
	$per_page = $this->config->item('pagi_per_page');
	$offset = ( $this->input->get_post('offset') != '' )?$this->input->get_post('offset'):0;

	$ex_agency_arr = [];
	$ex_agency_filter =  null;

	$nsea_sql = $this->db->query("
	SELECT nsea.`nsea_id`, a.`agency_id`, a.`agency_name`
	FROM `next_service_exclude_agency` AS nsea
	LEFT JOIN `agency` AS a ON nsea.`agency_id` = a.`agency_id`
	WHERE nsea.`active` = 1
	");
	foreach( $nsea_sql->result() as $nsea_row ){
		$ex_agency_arr[] = $nsea_row->agency_id;
	}

	if( count($ex_agency_arr) > 0 ){
		$ex_agency_imp = implode(",",$ex_agency_arr);
		$ex_agency_filter = "AND a.`agency_id` NOT IN({$ex_agency_imp})";
	}

	$data['nsea_sql'] = $nsea_sql;



	$sel_query = "
	p.`property_id`,
	p.`address_1` AS p_address_1,
	p.`address_2` AS p_address_2,
	p.`address_3` AS p_address_3,
	p.`state` AS p_state,
	p.`postcode` AS p_postcode,
	p.`retest_date`,

	a.`agency_id`,
	a.`agency_name`
	";

	//$custom_where = "p.`retest_date` != ''";
	$once_off_date = '1521-03-16';
	$no_job_date = '1521-03-17';

	$next_30_days = date('Y-m-d',strtotime("+30 days"));
	$custom_where = "( p.`retest_date` != '' AND p.`retest_date` <= '{$next_30_days}' )
	AND a.`franchise_groups_id` != 14
	AND p.retest_date != '{$once_off_date}'
	AND (
		( a.`allow_upfront_billing` = 1 AND p.`retest_date` = '{$no_job_date}' ) OR
		( a.`allow_upfront_billing` = 0 AND p.`retest_date` != '{$no_job_date}' )
	)
	{$ex_agency_filter}
	";

	$custom_joins = array(
		'join_table' => 'property_services AS ps',
		'join_on' => '( p.`property_id` = ps.`property_id` AND ps.`service` = 1 AND ps.`alarm_job_type_id` != 6 )', // must be serviced to SATS and excluding CW
		'join_type' => 'inner'
	);

	// paginated
	$params = array(
		'sel_query' => $sel_query,
		'custom_where' => $custom_where,
		'p_deleted' => 0,
		'a_status' => 'active',
		'agency_filter' => $agency_filter,
		'state_filter' => $state_filter,
		'date_filter_from' => $date_filter_from,
		'next_services'    => 1,
		'date_filter_to' => $date_filter_to,

		'custom_joins' => $custom_joins,

		'sort_list' => array(
			array(
				'order_by' => 'p.`retest_date`',
				'sort' => 'ASC',
			),
			array(
				'order_by' => 'p.`address_2`',
				'sort' => 'ASC',
			),
		),

		'limit' => $per_page,
		'offset' => $offset,

		'display_query' => 0
	);
	$data['property_sql'] = $this->properties_model->get_properties($params);
	$data['sql_query'] = $this->db->last_query();

	// params export
	$params_export = array(
		'sel_query' => $sel_query,
		'custom_where' => $custom_where,
		'p_deleted' => 0,
		'a_status' => 'active',
		'agency_filter' => $agency_filter,
		'state_filter' => $state_filter,
		'next_services'    => 1,
		'date_filter_from' => $date_filter_from,
		'date_filter_to' => $date_filter_to,

		'custom_joins' => $custom_joins,

		'sort_list' => array(
			array(
				'order_by' => 'p.`retest_date`',
				'sort' => 'ASC',
			),
			array(
				'order_by' => 'p.`address_2`',
				'sort' => 'ASC',
			),
		),

		'display_query' => 0
	);

	if( $export == 1 ){
		$property_sql = $this->properties_model->get_properties($params_export);

		// file name
		$filename = 'next_service'.date('YmdHis').rand().'.csv';

		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Pragma: no-cache");
		header("Expires: 0");

		// file creation
		$file = fopen('php://output', 'w');

		// csv header
		$csv_header = []; // clear
		$csv_header = array( 'Deadline', 'Retest Date', 'Property Addess', 'Agency', 'Active Job Status', 'Active Job Age');
		fputcsv($file, $csv_header);

		// csv row
		foreach ( $property_sql->result() as $property_row ) {
			//print_r($property_row);
			$p_address = "{$property_row->p_address_1} {$property_row->p_address_2}, {$property_row->p_address_3}  {$property_row->p_state}   {$property_row->p_postcode}";

			$retest_date_ts = $property_row->retest_date;
			$date_now = date("Y-m-d", strtotime(now)); 
			
			$date1 = date_create($retest_date_ts);
			$date2 = date_create($date_now);
			$diff = date_diff($date2,$date1);
			$retest_deadline = $diff->format("%R%a");

			//$p_address = "Address";
			$deadline_date = $datediff;
			$retest_date = $property_row->retest_date;
			//$retest_date = ($this->system_model->isDateNotEmpty($property_row->retest_date) == true) ? $this->system_model->formatDate($property_row->retest_date, 'd/m/Y') : '';
			$agency_name = $property_row->agency_name;

			$csv_row = [];
			$csv_row = array(
				$retest_deadline,
				$retest_date,
				$p_address,
				$agency_name
			);

			fputcsv($file, $csv_row);

		}

		fclose($file);
	}
	
	else{
		//Get all rows
	$sel_query = "COUNT(p.`property_id`) AS pcount";
	$params = array(
		'sel_query' => $sel_query,
		'custom_where' => $custom_where,
		'custom_joins' => $custom_joins,
		'p_deleted' => 0,
		'a_status' => 'active',
		'agency_filter' => $agency_filter,
		'state_filter' => $state_filter,
		'date_filter_from' => $date_filter_from,
		'next_services'    => 1,
		'date_filter_to' => $date_filter_to,
		'display_query' => 0
	);
	$query = $this->properties_model->get_properties($params);
	$total_rows = $query->row()->pcount;

	// update page total
	$page_tot_params = array(
		'page' => $uri,
		'total' => $total_rows
	);
	$this->system_model->update_page_total($page_tot_params);

	//agency filter
	$sel_query_agency_filter = "DISTINCT(a.`agency_id`), a.`agency_name`";
	$params_agency_filter = array(
		'sel_query' => $sel_query_agency_filter,
		'custom_where' => $custom_where,
		'p_deleted' => 0,
		'a_status' => 'active',

		'custom_joins' => $custom_joins,

		'sort_list' => array(
			array(
				'order_by' => 'a.`agency_name`',
				'sort' => 'ASC'
			)
		)
	);
	$data['agency_filter'] = $this->properties_model->get_properties($params_agency_filter);

	//state filter
	$sel_query_state_filter = "DISTINCT(p.`state`)";
	$params_state_filter = array(
		'sel_query' => $sel_query_state_filter,
		'custom_where' => $custom_where,
		'p_deleted' => 0,
		'a_status' => 'active',

		'custom_joins' => $custom_joins,

		'sort_list' => array(
			array(
				'order_by' => 'p.`state`',
				'sort' => 'ASC',
			)
		),
	);
	$data['state_filter'] = $this->properties_model->get_properties($params_state_filter);

	$pagi_links_params_arr = array(
		//'date_filter' => $date_filter,
		'date_filter_from' => $date_filter_from,
		'date_filter_to' => $date_filter_to,
		'next_services'    => 1,
		'agency_filter' => $agency_filter,
		'state_filter' =>  $state_filter
	);
	$pagi_link_params = $uri.'/?'.http_build_query($pagi_links_params_arr);


	// pagination
	$config['page_query_string'] = TRUE;
	$config['query_string_segment'] = 'offset';
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $per_page;
	$config['base_url'] = $pagi_link_params;

	$this->pagination->initialize($config);

	$data['pagination'] = $this->pagination->create_links();

	$pc_params = array(
		'total_rows' => $total_rows,
		'offset' => $offset,
		'per_page' => $per_page
	);
	$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);


	//load views
	$this->load->view('templates/inner_header', $data);
	$this->load->view($uri, $data);
	$this->load->view('templates/inner_footer', $data);
	}

}

/**
 * get recent create job by property_id via ajax
 * return job status
 */
/* disable for now (gherx) > include job_url to ajax_get_recent_created_job_age() to git rid of multiple request
public function ajax_get_recent_created_job_type(){

	$property_id = $this->input->post('prop_id');

	$this->db->select('j.id,j.created,j.date,j.status,j.job_type,p.property_id as p_prop_id');
	$this->db->from('jobs as j');
	$this->db->join('property as p','p.property_id = j.property_id','left');
	$this->db->join('agency as a','a.agency_id = p.agency_id','left');
	$this->db->where('a.`country_id`', $this->config->item('country'));
	$this->db->where('j.`del_job`', 0);
	$this->db->where('j.status!=','Completed');
	$this->db->where('j.`property_id`', $property_id);
	$this->db->order_by('j.created','DESC');
	$this->db->limit(1);
	$query = $this->db->get();
	$row = $query->row_array();
	$job_url = $this->gherxlib->crmLink('vjd',$row['id'], $row['status']);
	echo $job_url;

}
*/

/**
 * get recent create job by property_id via ajax
 * return job age
 */
public function ajax_get_recent_created_job_age(){

	$property_id = $this->input->post('prop_id');

	$this->db->select('j.id,j.created,j.date,j.status,j.job_type,p.property_id as p_prop_id');
	$this->db->from('jobs as j');
	$this->db->join('property as p','p.property_id = j.property_id','left');
	$this->db->join('agency as a','a.agency_id = p.agency_id','left');
	$this->db->where('a.`country_id`', $this->config->item('country'));
	$this->db->where('j.`del_job`', 0);
	$this->db->where('j.status!=','Completed');
	$this->db->where('j.`property_id`', $property_id);
	$this->db->order_by('j.created','DESC');
	$this->db->limit(1);
	$query = $this->db->get();
	$row = $query->row_array();
	$created =  $row['created'];

	$date1 = date_create(date('Y-m-d', strtotime($created)));
	$date2 = date_create(date('Y-m-d'));
	$diff = date_diff($date1, $date2);
	$age = $diff->format("%r%a");

	if($query->num_rows()>0){
		$age_val = (((int) $age) != 0) ? $age : 0;
	}else{
		$age_val = NULL;
	}

	$job_status = $row['status'];
	$job_url = $this->gherxlib->crmLink('vjd',$row['id'], $job_status);

	//table start
	echo "<table data-jobstatus='{$job_status}' class='awo' style='margin:0;padding;0;width:100%;border:0;'><tr><td style='width:200px;'>";
	echo $job_url;
	echo "</td>";

	echo "<td style='width:150px;'>";
	echo $age_val;
	echo "</td></tr></table>";
	//table end

}


	public function get_tenants_ajax_no_add_tenant_section()
	{

		$data['title'] = "Tenants";

		$data['prop_id'] = $this->security->xss_clean($this->input->post('prop_id'));

		if ($data['prop_id']) {

			// get active property tenants (new)
			$params_active = array('property_id' => $data['prop_id'], 'active' => 1);
			$data['active_tenants'] = $this->gherxlib->getNewTenantsData($params_active);

			// get inactive property tenants (new)
			$params_inactive = array('property_id' => $data['prop_id'], 'active' => "!=1");
			$data['in_active_tenants'] = $this->gherxlib->getNewTenantsData($params_inactive);
		} else {
			redirect(base_url('properties'), 'refresh');
		}

		//$this->load->view('templates/inner_header', $data);
		$this->load->view('properties/tenant_ajax_no_add_tenant_section', $data);
		//$this->load->view('templates/inner_footer', $data);

	}



	public function duplicate_property_service() {


        
        $data['title'] = "Duplicate Property Service";
        $uri = '/properties/duplicate_property_service';
        $data['uri'] = $uri;

        $agency_filter = $this->db->escape_str($this->input->get_post('agency_filter'));
        $alarm_expiry = $this->db->escape_str($this->input->get_post('alarm_expiry'));
        $btn_search = $this->db->escape_str($this->input->get_post('btn_search'));
        $country_id = $this->config->item('country');

        $query_filter = null;

        // pagination
		$per_page = $this->config->item('pagi_per_page');
        $offset = ( $this->input->get_post('offset') != '' )?$this->input->get_post('offset'):0;

        // main listing
		$list_sql_str = "
		SELECT
			ps.`property_id`,
			ps.`alarm_job_type_id`,
			COUNT(ps.`alarm_job_type_id`) AS duplicate_count,

			p.`property_id`,
			p.`address_1` AS p_street_num,
			p.`address_2` AS p_street_name,
			p.`address_3` AS p_suburb,
			p.`state` AS p_state,
			p.`postcode` AS p_postcode,

			a.`agency_id`,
			a.`agency_name`,

			ajt.`type` AS service_type
		FROM `property_services` AS ps
		INNER JOIN `property` AS p ON ps.`property_id` = p.`property_id`
		LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
		LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
		GROUP BY ps.`property_id`, ps.`alarm_job_type_id`
		HAVING duplicate_count > 1
		LIMIT {$offset}, {$per_page}
		";
		$data['list_sql'] = $this->db->query($list_sql_str);


        // get total row
		$list_sql_str = "
		SELECT
			ps.`property_id`,
			ps.`alarm_job_type_id`,
			COUNT(ps.`alarm_job_type_id`) AS duplicate_count,

			p.`property_id`,
			p.`address_1` AS p_street_num,
			p.`address_2` AS p_street_name,
			p.`address_3` AS p_suburb,
			p.`state` AS p_state,
			p.`postcode` AS p_postcode,

			a.`agency_id`,
			a.`agency_name`,

			ajt.`type` AS service_type
		FROM `property_services` AS ps
		INNER JOIN `property` AS p ON ps.`property_id` = p.`property_id`
		LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
		LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
		GROUP BY ps.`property_id`, ps.`alarm_job_type_id`
		HAVING duplicate_count > 1
		";
		$job_sql = $this->db->query($list_sql_str);
        $total_rows = $job_sql->num_rows();


        $pagi_links_params_arr = array(
            'date_filter' => $date_filter
        );
		$pagi_link_params = $uri.'/?'.http_build_query($pagi_links_params_arr);

        // pagination
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['base_url'] = $pagi_link_params;

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();

        $pc_params = array(
            'total_rows' => $total_rows,
            'offset' => $offset,
            'per_page' => $per_page
        );
		$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);



        //load views
        $this->load->view('templates/inner_header', $data);
        $this->load->view($uri, $data);
        $this->load->view('templates/inner_footer', $data);

	}


	public function fix_dup_prop_serv(){

		// main listing
		$list_sql_str = "
		SELECT
			ps.`property_services_id`,
			ps.`property_id`,
			ps.`alarm_job_type_id`,
			ps.`service`,
			ps.`price`,
			ps.`status_changed`,
			ps.`last_inspection`,
			ps.`is_payable`,
			COUNT(ps.`alarm_job_type_id`) AS duplicate_count,

			p.`property_id`,
			p.`address_1` AS p_street_num,
			p.`address_2` AS p_street_name,
			p.`address_3` AS p_suburb,
			p.`state` AS p_state,
			p.`postcode` AS p_postcode,

			a.`agency_id`,
			a.`agency_name`,

			ajt.`type` AS service_type
		FROM `property_services` AS ps
		INNER JOIN `property` AS p ON ps.`property_id` = p.`property_id`
		LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
		LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
		GROUP BY ps.`property_id`, ps.`alarm_job_type_id`
		HAVING duplicate_count > 1
		";

		$list_sql = $this->db->query($list_sql_str);
		foreach( $list_sql->result() as $row ){

			$property_id = $row->property_id;
			$alarm_job_type_id = $row->alarm_job_type_id;
			$service = $row->service;
			$service_type = $row->service_type;
			
			$price = $row->price;
			$status_changed = ( $row->status_changed != '' )?"'{$row->status_changed}'":'NULL';
			$last_inspection = ( $row->last_inspection != '' )?"'{$row->last_inspection}'":'NULL';
			$is_payable = $row->is_payable;

			if( $property_id > 0 ){

				$duplicated_sql = "SELECT `service`,status_changed FROM `property_services` WHERE `property_id` = {$property_id} AND `alarm_job_type_id` = {$alarm_job_type_id} AND `service` != 0";
				$order_service = $this->db->query($duplicated_sql . "  ORDER BY `service` ASC LIMIT 1");
				$order_date = $this->db->query($duplicated_sql . "  ORDER BY `status_changed` DESC LIMIT 1");
				
				$status_changed = ($order_date->row()->status_changed ? "'{$order_date->row()->status_changed}'" : $status_changed);
				$service = ($order_service->row()->service ? $order_service->row()->service : $service);

				// delete duplicate
				$delete_sql_str = "
				DELETE
				FROM `property_services`
				WHERE `property_id` = {$property_id}
				AND `alarm_job_type_id` = {$alarm_job_type_id}
				";
				$delete_duplicate = $this->db->query($delete_sql_str);

				// only re-inserts if property service ID is > 0
				if( $alarm_job_type_id > 0 ){

					// re-insert deleted duplicate
					$insert_sql_str = "
					INSERT INTO
					`property_services`(
						`property_id`,
						`alarm_job_type_id`,
						`service`,
						`price`,
						`status_changed`,
						`last_inspection`,
						`is_payable`
					)
					VALUES(
						{$property_id},
						{$alarm_job_type_id},
						{$service},
						{$price},
						{$status_changed},
						{$last_inspection},
						{$is_payable}
					)
					";
					$this->db->query($insert_sql_str);

					switch ($service) {
						case 0:
							$service = 'DIY';
							break;
						case 1:
							$service = config_item('company_name_short');
							break;
						case 2:
							$service = 'No Response';
							break;
						case 3:
							$service = 'Other Provider';
							break;
					}

					//insert logs
					$details = "Duplicate <strong>Property Services</strong> cleared, service type: {$service_type}; service: {$service}";
					$params_property_log = array(
						'title' => 3, //Property Services Updated
						'details' => $details,
						'display_in_vpd' => 1,
						'display_in_vjd' => 1,
						'agency_id' => $agency_id,
						'created_by_staff' => $this->session->aua_id,
						'property_id' => $property_id,
						'job_id' => NULL
					);
					$this->system_model->insert_log($params_property_log);

				}

			}
		}

	}



	public function inactive_properties_on_api() {

		$this->load->model('api_model');
        
        $data['title'] = "Inactive Properties on API";
        $country_id = $this->config->item('country');
        $uri = '/properties/inactive_properties_on_api';
        $data['uri'] = $uri;

        $agency_id = $this->input->get_post('agency_filter');

        // get connected agency
       /* $agency_sql_str = "
            SELECT
                a.`agency_id`,
                a.`agency_name`,

                agen_tok.`agency_api_token_id`,
                agen_tok.`connection_date`,
                agen_tok.`access_token`
            FROM `agency` AS a
            LEFT JOIN `agency_api_integration` AS agen_api ON (a.`agency_id` = agen_api.`agency_id`)
            LEFT JOIN `agency_api_tokens` AS agen_tok ON ( a.`agency_id` = agen_tok.`agency_id` AND agen_tok.`api_id` = 1 )
            WHERE a.`status` = 'active'
            AND agen_api.`active` = 1
			GROUP BY a.agency_id
			ORDER BY a.`agency_name` ASC
        ";

        $data['agency_sql'] = $this->db->query($agency_sql_str);
		*/

		// agency list that has data start > by Gherx

		$api_token_params_pme = array(
            'sel_query' => 'agen_api_tok.`agency_api_token_id`, a.`agency_id`,a.`agency_name`,a.`no_bulk_match`,pme_upc.`count` AS upc_count, agen_api_tok.api_id',
            'active' => 1,
            'deactivated' => 1,
            'target' => 1,
			'custom_where' => "(agen_api_tok.api_id = 1 OR agen_api_tok.api_id = 3 OR agen_api_tok.api_id = 4)",
            'group_by' => 'agen_api_tok.`agency_id`',
            'join_table' => array('agency','pme_unmatched_property_count'),
            'sort_list' => array(
                array(
                    'order_by' => 'a.agency_name',
                    'sort' => 'ASC'
                )
            ),
            'display_query' => 0			
        );
        $data['agency_sql'] = $this->api_model->get_agency_api_tokens($api_token_params_pme); 

       /* $agency_sql_arr = $this->api_model->get_agency_api_tokens($api_token_params_pme);  

		$agency_api = array();
		$agency_id_arr = array();
		$apd_api_prop_id_arr = array();
		foreach( $agency_sql_arr->result() as $key=>$a_row ){

			$agency_id_arr[] = $a_row->agency_id;

			if( $a_row->api_id==1 ){ //PME > 1
				//$agency_api[] = array('agency_id'=>$a_row->agency_id,'agency_name'=>$a_row->agency_name,'api_type'=>1, 'api_res'=> $this->pme_model->get_all_archived_properties($a_row->agency_id));
				$pme_q = $this->pme_model->get_all_archived_properties($a_row->agency_id);
				foreach( $pme_q as $pme_q_row ){
					$apd_api_prop_id_arr[] = $pme_q_row->Id;
				}
				
			}elseif( $a_row->api_id==4 ){ //Palace > 4
				//$agency_api[] = array('agency_id'=>$a_row->agency_id,'agency_name'=>$a_row->agency_name,'api_type'=>4, 'api_res'=> $this->palace_model->get_all_properties($a_row->agency_id));
				$palace_q = $this->palace_model->get_all_properties($a_row->agency_id);
				foreach( $palace_q as $palace_q_row ){
					if( $palace_q_row->PropertyArchived == true){
						//$apd_api_prop_id_arr[] = $palace_q_row->PropertyCode;
						$apd_api_prop_id_arr[] = array('prop_id'=>$palace_q_row->PropertyCode, 'achieve'=>$palace_q_row->PropertyArchived, 'address'=>$palace_q_row->PropertyAddress4);
					}
				}
			}
			
		}

		$this->db->select('p.property_id, apd.api_prop_id, p.agency_id');
		$this->db->from('property as p');
		$this->db->join('api_property_data as apd', 'apd.crm_prop_id = p.property_id' ,'left');
		$this->db->where_in('p.agency_id',$agency_id_arr);
		$this->db->where('p.deleted', 0);
		$this->db->where('apd.api_prop_id!=', '');
		$ttq = $this->db->get();
		
		$ttq_arr = array();
		foreach( $ttq->result() as $ttq_row ){
			$ttq_arr[] = array('agency_id'=>$ttq_row->agency_id, 'crm_api_prop_id'=> $ttq_row->api_prop_id);
		}

		$final_api_agency_idarr = array();
		foreach($ttq_arr as $ttq_arr_row){

			foreach( $apd_api_prop_id_arr as $apd_api_prop_id_arr_row ){
				if( $ttq_arr_row['crm_api_prop_id'] == $apd_api_prop_id_arr_row ){
					$final_api_agency_idarr[] = $ttq_arr_row['agency_id'];
				}
			}

		}

		$agency_id_unique = array_unique($final_api_agency_idarr);

		$this->db->select('agency_id,agency_name');
		$this->db->from('agency');
		$this->db->where_in('agency_id', $agency_id_unique);
		$a_q = $this->db->get();
		$data['agency_sql'] = $a_q; */

		// agency list that has data end > by Gherx
		

        if( $agency_id > 0 ){

			// get agency API
			$apt_sql = $this->db->query("
			SELECT `api_id`
			FROM `agency_api_tokens`
			WHERE `agency_id` = {$agency_id}
			");
			$apt_row = $apt_sql->row();

            // get paginated CRM properties |  get propertyme_prop_id and palace_prop_id
            $property_sql_str = "
            SELECT
				p.`property_id`,
                p.`address_1` AS p_street_num,
                p.`address_2` AS p_street_name,
                p.`address_3` AS p_suburb,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
				p.`agency_id`,
				
				apd.api_prop_id,
				apd.api
            FROM `property` AS p
			LEFT JOIN `api_property_data` AS apd ON p.property_id = apd.crm_prop_id
            WHERE p.`agency_id` = {$agency_id}
            AND p.`deleted` = 0

            ";
            $property_sql = $this->db->query($property_sql_str);
			$data['sql_query'] = $this->db->last_query();

			// store crm property query result
			$crm_prop_result = $property_sql->result();

			$crm_prop_arr = [];

			// API ID
			$pme_api_id = 1; // PMe
			$pt_api_id = 3; // PropertyTree
			$palace_api_id = 4; // Palace

			if( $apt_row->api_id == $pme_api_id ){

				// get PMe archived properties
				$pme_archived_prop = $this->pme_model->get_all_archived_properties($agency_id);
				
				foreach( $crm_prop_result as $property_row ){

					foreach( $pme_archived_prop as $pme_prop_data ){

						if( $property_row->api == $pme_api_id ) {	

							// matched connected property
							if( $property_row->api_prop_id == $pme_prop_data->Id  ){

								$property_row->api_prop_address = $pme_prop_data->AddressText;								
								$property_row->api_type = $pme_api_id;
								$property_row->api_archived_date = $pme_prop_data->ArchivedOn;

								$crm_prop_arr[] = $property_row;

							}
						}
					}

				}
				
			}else if( $apt_row->api_id == $pt_api_id ){ // PROPERTY TREE

				// property tree
				$prop_tree_list = $this->property_tree_model->get_all_properties($agency_id);

				foreach( $crm_prop_result as $property_row ){
	
					foreach( $prop_tree_list as $prop_tree_data ){

						$address_obj = $prop_tree_data->address;

						if ( $property_row->api == $pt_api_id ) {	

							// matched connected property
							if( $prop_tree_data->archived == true || $prop_tree_data->deleted == true ){
								
								if( $property_row->api_prop_id == $prop_tree_data->id ){
	
									$pt_prop_address = "{$address_obj->street_number} {$address_obj->address_line_1} {$address_obj->address_line_2} {$address_obj->suburb} {$address_obj->state} {$address_obj->post_code}";
	
									$property_row->api_prop_address = $pt_prop_address;
									$property_row->api_type = $pt_api_id; 
	
									$crm_prop_arr[] = $property_row;
	
								}
							}
						}
					}
	
				}

			}else if( $apt_row->api_id == $palace_api_id ){ // PALACE				

				// get all palace properties
				$palace_prop = $this->palace_model->get_all_properties($agency_id);
			
				foreach( $crm_prop_result as $property_row ){
	
					foreach( $palace_prop as $palace_prop_data ){

						if ( $property_row->api == $palace_api_id ) {	
	
							if( $palace_prop_data->PropertyArchived == true ){
								
								if( $property_row->api_prop_id == $palace_prop_data->PropertyCode ){
	
									$palace_prop_address = "{$palace_prop_data->PropertyAddress1} {$palace_prop_data->PropertyAddress2} {$palace_prop_data->PropertyAddress3} {$palace_prop_data->PropertyAddress4} {$palace_prop_data->PropertyPostCode}";
	
									$property_row->api_prop_address = $palace_prop_address;
									$property_row->api_type = $palace_api_id;
	
									$crm_prop_arr[] = $property_row;
	
								}
							}
						}
					}
	
				}

			}        						

			// crm properties that is archived/deactived on API
            $data['crm_prop_arr'] = $crm_prop_arr;

        }

        $this->load->view('templates/inner_header', $data);
        $this->load->view($uri, $data);
        $this->load->view('templates/inner_footer', $data);
	}


	public function api_workorders() {

        
        $data['title'] = "API workorders";
        $country_id = $this->config->item('country');
        $uri = '/properties/api_workorders';
        $data['uri'] = $uri;

        $agency_id = $this->input->get_post('agency_filter');

        // get connected agency
        $agency_sql_str = "
            SELECT
                a.`agency_id`,
                a.`agency_name`,

                agen_tok.`agency_api_token_id`,
                agen_tok.`connection_date`,
                agen_tok.`access_token`
            FROM `agency` AS a
            LEFT JOIN `agency_api_integration` AS agen_api ON (a.`agency_id` = agen_api.`agency_id` AND agen_api.`connected_service` = 1 )
            LEFT JOIN `agency_api_tokens` AS agen_tok ON ( a.`agency_id` = agen_tok.`agency_id` AND agen_tok.`api_id` = 1 )
            WHERE a.`status` = 'active'
            AND agen_api.`connected_service` = 1
            AND agen_api.`active` = 1
			ORDER BY a.`agency_name` ASC
        ";

        $data['agency_sql'] = $this->db->query($agency_sql_str);

        if( $agency_id > 0 ){


            // get paginated CRM properties
            $property_sql_str = "
            SELECT
                p.`property_id`,
                p.`address_1` AS p_street_num,
                p.`address_2` AS p_street_name,
                p.`address_3` AS p_suburb,
                p.`state` AS p_state,
                p.`postcode` AS p_postcode,
				apd.api_prop_id,
				apd.api
			FROM `property` AS p
			LEFT JOIN `api_property_data` AS apd ON p.property_id = apd.crm_prop_id
            WHERE `agency_id` = {$agency_id}
            AND `deleted` = 0

            ";
            $property_sql = $this->db->query($property_sql_str);

			// store crm property query result
			$crm_prop_result = $property_sql->result();

            $crm_prop_arr = [];

			// get all palace properties
			$palace_workorders = $this->palace_model->get_workorders($agency_id);
			//print_r($palace_workorders);


            foreach( $crm_prop_result as $property_row ){

				// clear
				$is_archived = null;

                foreach( $palace_workorders as $palace_prop_data ){

					// apd.api = 4
					if ($property_row->api == 4) {	
						// matched connected property
						if( $property_row->api_prop_id == $palace_prop_data->PropertyCode ){

							$property_row->workorder_description = $palace_prop_data->WorksOrderDescription;

							$property_row->api_type = 4; // Palace

							$crm_prop_arr[] = $property_row;

						}
					}
				}


			}

			// crm properties that is archived/deactived on API
			$data['crm_prop_arr'] = $crm_prop_arr;


        }

        $this->load->view('templates/inner_header', $data);
        $this->load->view($uri, $data);
		$this->load->view('templates/inner_footer', $data);

	}


	// add agency to excluded filter
	public function add_next_service_exclude_agency(){

		$ex_agency = $this->input->get_post('ex_agency');
		$today = date('Y-m-d H:i:s');

		if( $ex_agency > 0 ){

			$sql_str = "
			SELECT COUNT(`nsea_id`) AS jcount
			FROM `next_service_exclude_agency`
			WHERE `agency_id` = {$ex_agency}
			";
			$sql = $this->db->query($sql_str);
			$num_rows = $sql->row()->jcount;

			if( $num_rows == 0 ){

				$data = array(
					'agency_id' => $ex_agency,
					'date_created' => $today
				);
				$this->db->insert('next_service_exclude_agency', $data);

			}

		}

	}

	// remove agency from excluded filter
	public function remove_next_service_exclude_agency(){

		$nsea_id = $this->input->get_post('nsea_id');

		if( $nsea_id > 0 ){

			$sql_str = "
			DELETE
			FROM `next_service_exclude_agency`
			WHERE `nsea_id` = {$nsea_id}
			";
			$this->db->query($sql_str);

		}

	}

	public function checkToken($agency_id){

        $unixtime 	= time();
        $now 		= date("Y-m-d H:i:s",$unixtime);

        $api_id    = 6;

        $token['token'] = $this->ourtradie_model->getToken($agency_id, $api_id);

		$created         = $token['token'][0]->created;
        $expiry          = $token['token'][0]->expiry;
        $expired         = strtotime($now) - strtotime($expiry);

        $tmp_refresh_token   = $token['token'][0]->refresh_token;
        $tmp_arr_refresh_token = explode("+/-]",$tmp_refresh_token);
        $refresh_token = $tmp_arr_refresh_token[0];

        if($expired > 0){

        $options = array(
            'grant_type'      => 'refresh_token',
            'refresh_token'   =>  $refresh_token,
            'client_id'		  => 'br6ucKvcPRqDNA1V2s7x',
            'client_secret'	  => 'd5YOJHb6EYRw5oypl73CJFWGLob5KB9A',
            'redirect_uri'	  => "https://{$this->config->item('domain')}/ourtradie/refreshToken"
            );

        $api = new OurtradieApi($options, $_REQUEST);
        $token = $refresh_token;

        $response = $api->refreshToken($token);

		if(!empty($response)){
			$access_token   = $response->access_token;
			$refresh_token  = $response->refresh_token;
			$expiry         = date('Y-m-d H:i:s',strtotime('+3600 seconds'));
			$created        = $now;

			$update_data = array(
				'access_token'    => $access_token,
				'refresh_token'   => $refresh_token."+/-]".$tmp_arr_refresh_token[1],
				'created'         => $created,
				'expiry'          => $expiry,
			);

			$this->ourtradie_model->updateToken($agency_id, $api_id, $update_data);

			/*
			if($uri == "ajax_bulk_connect_get_ourtradie_list"){
				$this->ajax_bulk_connect_get_ourtradie_list();
			}
			*/

			if($uri == "property"){
				$contactId = $_SESSION['contactId'];
				$agency_id = $_SESSION['agency_id'];
				$this->property($contactId, $agency_id);
			}

			if($uri == "properties_needs_verification"){
				redirect('/property_me/'.$uri);
			}
		}

        }

    }//endfct

	/**
	 * Add Sales Properties
	 */
	public function add_sales_properties(){

		
		$this->load->model('agency_model');
		$data['title'] = "Add Sales Properties";
		$data['enable_PMe'] = true; // enable Pme
		//$data['enable_PMe'] = false; // disable Pme

		//post/get
		$country_id = $this->config->item('country');
		$btnAddProperty = $this->input->post('btnAddProperty');

		$data['pm_passed_agency_id'] = $this->input->get_post('agency_id');
		$data['pm_prop_id'] = $this->input->get_post('pid');
		$agency_id = $this->input->get_post('agency_id');

		//get agency list (dropdown)
		$agency_params = array(
			'sel_query' => '
				a.agency_id,
				a.agency_name,
				a.address_3,
				a.franchise_groups_id,
				a.allow_indiv_pm,
				a.load_api
			',
			'a_status' => 'active',
			'country_id' => $this->config->item('country'),
			'custom_where' => 'a.agency_id!=1 AND state="QLD"',
			'sort_list' => array(
				array(
					'order_by' =>  'a.agency_name',
					'sort' => 'ASC'
				)
			)
		);
		$data['agency_list'] = $this->agency_model->get_agency($agency_params);


		if($country_id==1){ //AU
			if( ENVIRONMENT == 'production' ){ //live
				$data['compass_fg'] = 39;
			}else{
				$data['compass_fg'] = 34;
			}
		}else{
			$data['compass_fg'] = 'compass_fg';
		}


		$this->load->view('templates/inner_header', $data);
		$this->load->view('properties/add_sales_properties', $data);
		$this->load->view('templates/inner_footer', $data);

	}

	public function add_sales_properties_form(){

		if($this->input->post('btnAddProperty')){

				$this->load->library('form_validation');
				$this->load->library('upload');
				$this->load->library('email');

				$agency_id = $this->input->post('agency');
				$remember = $this->input->post('remember_agency');

				$address_1 = $this->input->post('address_1');
				$address_2 = $this->input->post('address_2');
				$address_3 = $this->input->post('address_3');
				$state = $this->input->post('state');
				$postcode = $this->input->post('postcode');

				// somehow the db setting is strict, it cannot be submitted if empty, needs to default to 0 if empty
				$service_garage = ( is_numeric($this->input->post('service_garage')) )?$this->input->post('service_garage'):0;

				$prop_vacant = $this->input->post('prop_vacant');
				if($prop_vacant==0){
					$vacant_from = (!empty($this->input->post('vacant_from')))?date("Y-m-d H:i:s",strtotime(str_replace("/","-",$this->input->post('vacant_from')))): date('Y-m-d H:i:s');
					$vacant_to = (!empty($this->input->post('vacant_to')))?date("Y-m-d",strtotime(str_replace("/","-",$this->input->post('vacant_to')))): date('Y-m-d', strtotime('+30 days'));
				}else{
					$vacant_from = (!empty($this->input->post('vacant_from')))?date("Y-m-d H:i:s",strtotime(str_replace("/","-",$this->input->post('vacant_from')))): NULL;
					$vacant_to = (!empty($this->input->post('vacant_to')))?date("Y-m-d",strtotime(str_replace("/","-",$this->input->post('vacant_to')))): NULL;
				}

				$landlord_firstname = $this->input->post('landlord_firstname');
				$landlord_lastname = $this->input->post('landlord_lastname');
				$landlord_moble = $this->input->post('landlord_mobile');
				$landlord_landline = $this->input->post('landlord_landline');
				$landlord_email = $this->input->post('landlord_email');

				$alarm_code = $this->input->post('alarm_code');
				$workorder_num = $this->input->post('workorder_num');
				$lockbox_code = $this->input->post('lockbox_code');
				$key_number = $this->input->post('key_number');

				$hid_allow_pm = $this->input->post('hid_allow_pm');
				$property_manager = ( $hid_allow_pm == 1 )?$this->input->post('property_manager'):'';

				$compass_index_num = $this->input->post('compass_index_num');
				$workorder_notes = $this->input->post('workorder_notes');

				// PME property ID
				$pme_prop_id = ( $this->input->get_post('selected_pme_prop_id') != '' )?$this->input->get_post('selected_pme_prop_id'):null;

				// Palace property ID
				$palace_prop_id = ( $this->input->get_post('selected_palace_prop_id') != '' )?$this->input->get_post('selected_palace_prop_id'):null;

				// Property Tree property ID
				$pt_prop_id = ( $this->input->get_post('selected_pt_prop_id') != '' )?$this->input->get_post('selected_pt_prop_id'):null;

				// Ourtradie property ID
				$ourtradie_prop_id = ( $this->input->get_post('selected_ot_prop_id') != '' )?$this->input->get_post('selected_ot_prop_id'):null;

				$duplicate_query = $this->properties_model->check_duplicate_property($address_1,$address_2,$address_3,$state,$postcode);

				$added_from_property_list = $this->input->post('added_from_property_list') == 1;

				$alt_email = $this->input->post('alt_email');


				if($duplicate_query->num_rows()>0){ //DUPLICATE PROPERTY

					//SHOW DUPLICATE ERROR
					$duplicate_row = $duplicate_query->row_array();
					$data['prop_id'] = $duplicate_row['property_id'];
					$data['status'] = ($duplicate_row['deleted']==1)?'Deactivated':'Active';
					$data['address'] = "{$duplicate_row['p_address_1']} {$duplicate_row['p_address_2']}, {$duplicate_row['p_address_3']} {$duplicate_row['state']} {$duplicate_row['postcode']}";
					$data['agency_id'] = $duplicate_row['agency_id'];
					$data['agency_name'] = $duplicate_row['agency_name'];

					$data['title'] = "Duplicate Property";
					$data['result_type'] = "duplicate";
					$this->load->view('templates/inner_header', $data);
					$this->load->view('properties/ajax_property/property_result', $data);
					$this->load->view('templates/inner_footer', $data);

				}else{ //No Duplicate property - continue adding-----

					// CI FORM VALIDATION
					$this->form_validation->set_rules('address_1', 'Street No.', 'required');
					$this->form_validation->set_rules('address_2', 'Sreet Name', 'required');
					$this->form_validation->set_rules('address_3', 'Suburb', 'required');
					$this->form_validation->set_rules('state', 'State', 'required');
					$this->form_validation->set_rules('postcode', 'Postcode', 'required');

					if ( $this->form_validation->run() == true ){ // Validation Ok

							// Hume Community Housing Association
							$prop_comments = '';
							if( $agency_id==1598 ){

								$prop_comments = 'Please install 9vLi or 240v only. DO NOT INSTALL 240vLi';

							}

							// replace some state, only on NZ
							if( $this->config->item('country') == 2 ){ // NZ
								$state = $this->system_model->replace_state($state);
							}

							// get lat and lng for mapping
							$address_str = "{$address_1} {$address_2} {$address_3} {$state} {$postcode}";
							$coordinate = $this->system_model->getGoogleMapCoordinates($address_str);


							// INSERT PROPERTY
							$property_data = array(
								'agency_id' => $agency_id,
								'address_1' => $address_1,
								'address_2' => $address_2,
								'address_3' => $address_3,
								'state' => $state,
								'postcode' => $postcode,
								'added_by' => $this->session->staff_id,
								'key_number' => $key_number,
								'alarm_code' => $alarm_code,
								'service_garage' => $service_garage,
								'landlord_firstname' => $landlord_firstname,
								'landlord_lastname' => $landlord_lastname,
								'landlord_email' => $landlord_email,
								'landlord_mob' => $landlord_moble,
								'landlord_ph' => $landlord_landline,
								'pm_id_new' => $property_manager,
								'comments' => $prop_comments,
								'lat' => $coordinate['lat'],
								'lng' => $coordinate['lng'],
								'compass_index_num' => $compass_index_num,
								'is_sales' => 1,
								'preferred_alarm_id' => 22 // Emerald Planet is the default preferred alarm for Sales property
							);

							//print_r($property_data);
							//exit();

							$add_property = $this->properties_model->add_property($property_data);


							$prop_insert_id = $this->db->insert_id();

							if($add_property && !empty($prop_insert_id)){ // success adding property

								//INsert VPD LOG FOR NEW PROPERTY
								if ($pme_prop_id != '') {
									$prop_log_details = 'Added to match Active property on PropertyMe';
								}else if ($palace_prop_id != '') {
									$prop_log_details = 'Added to match Active property on Palace';
								}else {
									$prop_log_details = '';
								}

								if ($added_from_property_list){
									$prop_log_details = 'This property was added from a property list';
								}

								$params = array(
									'title' => 2, //New Property Added
									'details' => $prop_log_details,
									'display_in_vpd' => 1,
									'agency_id' => $agency_id,
									'created_by_staff' => $this->session->staff_id,
									'property_id' => $prop_insert_id
								);
								$this->system_model->insert_log($params);

								// check if lockbox exist
								$lb_sql = $this->db->query("
								SELECT COUNT(`id`) AS pl_count
								FROM `property_lockbox`
								WHERE `property_id` = {$prop_insert_id}
								");
								$lb_row = $lb_sql->row();
					
								if( $lb_row->pl_count > 0 ){ // it exist, update
					
									$this->db->query("
									UPDATE `property_lockbox`
									SET `code` = '{$lockbox_code}'
									WHERE `property_id` = {$prop_insert_id}
									");
					
								}else{ // doesnt exist, insert
					
									if( $lockbox_code != '' ){
					
										$this->db->query("
										INSERT INTO 
										`property_lockbox`(
											`code`,
											`property_id`
										)
										VALUE(
											'{$lockbox_code}',
											{$prop_insert_id}
										)	
										");
					
									}		
					
								}

								//INSERT TENNANTS
								$tenant_priority_arr = $this->input->post('tenant_priority');
								$tenant_firstname_arr = $this->input->post('tenant_firstname');
								$tenant_lastname_arr = $this->input->post('tenant_lastname');
								$tenant_ph_arr = $this->input->post('tenant_ph');
								$tenant_mob_arr = $this->input->post('tenant_mob');
								$tenant_email_arr = $this->input->post('tenant_email');

								if(!empty($this->input->post('tenant_firstname')) || !empty($this->input->post('tenant_lastname'))){
										foreach($tenant_firstname_arr as $index => $tenant_fname_val){
											if($tenant_fname_val!="" || $this->input->post('tenant_lastname')[$index]!=""){
												$post_array[] = array(
													'property_id' =>  $prop_insert_id,
													'tenant_firstname' => $tenant_fname_val,
													'tenant_lastname' => $tenant_lastname_arr[$index],
													'tenant_mobile' => $tenant_mob_arr[$index],
													'tenant_landline' => $tenant_ph_arr[$index],
													'tenant_email' => $tenant_email_arr[$index],
													'active' => 1,
													'tenant_priority' => $tenant_priority_arr[$index]
												);
											}
										}
										if(!empty($post_array)){
											$this->properties_model->add_tenants($post_array, 'batch'); //  param insert batch otherwise 0 for normal
										}
								}
								//INSERT TENNANTS END

								//INSERT PROPERTY SERVICES
								$alarm_job_type_id  = $this->input->post('alarm_job_type_id');
								$price = $this->input->post('price');
								$price_changed = $this->input->post('price_changed');
								$price_reason = $this->input->post('price_reason');
								$price_details = $this->input->post('price_details');

								foreach($alarm_job_type_id as $index=>$val){ //$val = alarm_job_type_id

										$services = $this->input->post('service'.$index);

										// dont insert property services if NR(2), requested by Ben
										if( $services != 2 || ($price[$index]!="" && $services==2 && $price_changed[$index]==1) ){

											// insert property services -----
											$prop_services_array = array(
												'property_id' => $prop_insert_id,
												'alarm_job_type_id' => $val,
												'service' => $services,
												'price' => $price[$index],
												'status_changed' => date("Y-m-d H:i:s"),
											);

											// if service = SATS, mark as payable
											if( $services == 1 ){
												$prop_services_array['is_payable'] = 1;
											}

											$this->properties_model->add_property_services($prop_services_array);

										}

										//iff picked jobs = sats > add jobs -----
										if($services==1){

												//Insert Property TYpe
												$prop_type_post = array(
													'property_id' => $prop_insert_id,
													'alarm_job_type_id' => $val
												);
												$this->properties_model->add_property_type($prop_type_post);

												// IF DHA agencies, franchise group = 14(Defence Housing)
												$franchise_groups_id = $this->db->select('franchise_groups_id')->from('agency')->where('agency_id', $agency_id)->get()->row()->franchise_groups_id; //get franchise group id by agency id

												if($this->system_model->isDHAagenciesV2($franchise_groups_id)){

														$tech_notes = $this->input->post('tech_notes');
														//$start_date = ($this->input->post('start_date')!="")?date('Y-m-d', strtotime($this->input->post('start_date'))):NULL;
														//$due_date = ($this->input->post('due_date')!="")?date('Y-m-d', strtotime($this->input->post('due_date'))):NULL;

														$start_date = ( $this->input->get_post('start_date') != '' )?$this->system_model->formatDate($this->input->get_post('start_date')):NULL;
														$due_date = ( $this->input->get_post('due_date') != '' )?$this->system_model->formatDate($this->input->get_post('due_date')):NULL;

														$jt_txt = 'Once-off';
														$s_txt = 'DHA';

														//set additional fields to insert jobs
														$add_field_array = array(
															'tech_notes' => $tech_notes,
															'start_date' => $start_date,
															'due_date' => $due_date
														);

												}else{	///not DHA

													$jt_txt = 'IC Upgrade';
													$s_txt = 'To Be Booked';
													//set additional fields to insert jobs
													$add_field_array = array(
														'tech_notes' => NULL,
														'start_date' => $vacant_from,
														'due_date' => $vacant_to
													);

												}

												// if agency is DHA agencies with franchise group = 14(Defence Housing) OR if agency has maintenance program
												if( $this->system_model->isDHAagenciesV2($franchise_groups_id)==true || $this->system_model->agencyHasMaintenanceProgram($agency_id)==true   ){
													$dha_need_processing = 1;
												}

												//Insert Jobs
												$job_post_array = array(
													'job_type' => $jt_txt,
													'property_id' => $prop_insert_id,
													'status' => $s_txt,
													'work_order' => $workorder_num,
													'service' => $val,
													'job_price' => $price[$index],
													'property_vacant' => $prop_vacant,
													'dha_need_processing' => $dha_need_processing,
													'comments' => $workorder_notes
												) + $add_field_array;
												// insert jobs - return last id
												$job_id = $this->properties_model->add_jobs($job_post_array);


												// AUTO - UPDATE INVOICE DETAILS
												$this->system_model->updateInvoiceDetails($job_id);

												// If BUNDLE > INSERT BUNDLE SERVICES
												$ajt = $this->db->select('*')->from('alarm_job_type')->where('id',$val)->get()->row_array(); //get ajt

												if($ajt['bundle']==1){ //if bundle
													$b_ids = explode(",",trim($ajt['bundle_ids']));
													// insert bundles
													foreach($b_ids as $val){
														$ajt_post_arr = array('job_id'=>$job_id, 'alarm_job_type_id'=> $val);
														$this->db->insert('bundle_services',$ajt_post_arr);
													}
												}

												/*
												// insert alternate email if any
												$data = array(
													'foreign_id' => $job_id,
													'alt_email' => $alt_email,
													'email_for_cert' => 1
												);											
												$this->db->insert('alternate_email', $data);
												*/

										}

										// Changed Price Log
										if($price_changed[$index]==1){

											$serv = "";
											switch($val){
												case 2:
													$serv = "Smoke Alarms";
												break;
												case 5:
													$serv = "Safety Switch";
												break;
												case 6:
													$serv = "Corded Windows";
												break;
												case 7:
													$serv = "Pool Barriers";
												break;
											}

											$details = "New Price for {$serv}- $".$price[$index].", Reason- ".$price_reason[$index].", Details- ".$price_details[$index];
											$params = array(
												'title' => 42, //Price Changed
												'details' => $details,
												'display_in_vpd' => 1,
												'agency_id' => $agency_id,
												'created_by_staff' => $this->session->staff_id,
												'property_id' => $prop_insert_id
											);
											$this->system_model->insert_log($params);

										}
										// Changed Price Log End

								}

							}

							//CREATE SESSION FOR REMEMBER AGENCY CHECKBOX
							if($remember==1){
								$remember_session = array('remember_agency'=>1,'remember_agency_id'=> $agency_id, 'rem_fg_id'=> $franchise_groups_id);
								$this->session->set_userdata($remember_session);
						}else{
								$aw_unset_arr = array('remember_agency','remember_agency_id','rem_fg_id');
								$this->session->unset_userdata($aw_unset_arr);
						}


							//get property data pass to result page
							$data['title'] = "Success";
							$res_prop_query = $this->db->select('property_id,address_1,address_2,address_3,state,postcode')->from('property')->where('property_id',$prop_insert_id)->get()->row_array();
							$data['res_prop_id'] = $res_prop_query['property_id'];
							$data['res_prop_address'] = "{$res_prop_query['address_1']} {$res_prop_query['address_2']}, {$res_prop_query['address_3']} {$res_prop_query['state']} {$res_prop_query['postcode']} ";

							$new_prop_added_crm_link = $this->gherxlib->crmLink('vjd',$job_id, $data['res_prop_address']);
							$success_message = "Property Below Successfully Added <br/> {$new_prop_added_crm_link}";
							//$this->session->set_flashdata(array('success_msg'=>$success_message,'status'=>'success'));
							$this->session->set_userdata('gherx_msg', $success_message);
							redirect(base_url('/properties/add_sales_properties'),'refresh');


					}else{ //validation end
							$error_msg = "Error: Required field must not be empty";
							//$this->session->set_flashdata(array('error_msg'=>$error_msg,'status'=>'error'));
							$this->session->set_userdata('gherx_error_msg', $error_msg);
							redirect(base_url('/properties/add_sales_properties'),'refresh');
					}

				}


		}else{
			redirect('/properties/add_sales_properties','refresh');
		}
	}

	public function get_properties_with_multiple_services(){
		 
        $data['title'] = 'Properties With Multiple Services';

		$country_id = $this->config->item('country');

		// pagination
		$per_page = $this->config->item('pagi_per_page');
		$offset = $this->input->get_post('offset');

		//get agencies
		$params['get_agencies'] = true;
		$data['agencies'] = $this->properties_model->get_properties_with_multiple_services($params);
		$params['get_agencies'] = false;

		//get states
		$params['get_states'] = true;
		$data['states'] = $this->properties_model->get_properties_with_multiple_services($params);
		$params['get_states'] = false;

		$params['total_rows'] = true;
		$params['search_filter'] = $this->input->get_post('search_filter');
		$params['state_filter'] = $this->input->get_post('state_filter');
		$params['agency_filter'] = $this->input->get_post('agency_filter');

		$total_rows = $this->properties_model->get_properties_with_multiple_services($params);

		$params['total_rows'] = false;
		$params['limit'] = $per_page;
		$params['offset'] = $offset;

		$properties_with_multiple_services = $this->properties_model->get_properties_with_multiple_services($params);

		$data['properties_with_multiple_services'] = 	$properties_with_multiple_services;

		$pagi_link_params = '/properties/properties_with_multiple_services';
  
        // pagination settings
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['base_url'] = $pagi_link_params;
        
        $this->pagination->initialize($config);
        
        $data['pagination'] = $this->pagination->create_links();

		// pagination count
		$pc_params = array(
			'total_rows' => $total_rows,
			'offset' => $offset,
			'per_page' => $per_page
		);
		
		$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);

        $this->load->view('templates/inner_header', $data);
        $this->load->view('properties/properties_with_multiple_services',$data);
        $this->load->view('templates/inner_footer');
	}

	public function ajax_check_agency(){
		$agency_id = $this->input->get_post('agency_id');
		$country_id = $this->config->item('country');
		//echo $country_id;
		//exit();

		$private_check = $this->properties_model->getAgencyName($agency_id, $country_id);
		//echo $this->db->last_query();
		//print_r($private_check);
		//exit();

		if(!empty($private_check)){
			$private = 1;
		}
		else{
			$private = 0;
		}
		echo $private;

	}

	public function update_property_variation() 
	{
        
		$title = "Update Property Variation";
        $data['title'] = $title;
        $country_id = $this->config->item('country');
        $uri = '/properties/update_property_variation';
        $data['uri'] = $uri;

        $agency_id = $this->input->get_post('agency_filter');
        $date_filter_str = null;

		$export = $this->input->get_post('export');
		$search = $this->input->get_post('search');

        // pagination
        $per_page = $this->config->item('pagi_per_page');
        $offset = ($this->input->get_post('offset')!="")?$this->input->get_post('offset'):0;

        $agency_filter = null;
		$where_search = "";

		// select query
        $sel_query = "
        SELECT
		p.`property_id`,
		p.`address_1`,
		p.`address_2`,
		p.`address_3`,
		p.`state`,
		p.`postcode`,
		p.`qld_new_leg_alarm_num`,

		ps.`alarm_job_type_id` AS service_type,

		ajt.`type` AS service_type_name,

		a.`agency_id`,
		a.`agency_name`,
		aght.priority
        ";		
        
        // main query body
        $main_query = "
			FROM `property` AS p
			INNER JOIN `property_services` AS ps ON ( p.`property_id` = ps.`property_id` AND ps.`service` = 1 )
			LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
			LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
			LEFT JOIN `agency_priority` as aght ON a.`agency_id` = aght.`agency_id`
			WHERE  p.`deleted` = 0
			AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
        "; 

		$order_by_sql_str = "ORDER BY p.`address_2` ASC, p.`address_3` ASC, p.`address_1` ASC";

		if (!empty($search)) {
			// $custom_where_address = " AND (CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode)) 
			// 						OR CONCAT_WS(' ', UPPER(p.address_1), UPPER(p.address_2), UPPER(p.address_3), UPPER(p.state), UPPER(p.postcode))) ";

			$where_search = "AND (
								(LOWER(p.address_1) LIKE '%$search%' OR UPPER(p.address_1) LIKE '%$search%') 
								OR (LOWER(p.address_2) LIKE '%$search%' OR UPPER(p.address_2) LIKE '%$search%') 
								OR (LOWER(p.address_3) LIKE '%$search%' OR UPPER(p.address_3) LIKE '%$search%')
							)";
		}
		

		if( $agency_id > 0 ){

			$agency_filter = "AND a.`agency_id` = {$agency_id}";

			if ($export == 1) { //EXPORT        
            
				// file name
				$date_export = date('YmdHis');
				$file_title = strtolower(str_replace(' ', '_', $title));
				$filename = "{$file_title}_{$date_export}.csv";
	
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename={$filename}");
				header("Pragma: no-cache");
				header("Expires: 0");
	
				// file creation 
				$csv_file = fopen('php://output', 'w');            
	
				$header = array('Address','Service Type','Service Price', 'Current Variation');
	
				// insert csv header
				fputcsv($csv_file, $header);
	
				// main listing
				$property_sql = $this->db->query("
				{$sel_query}
				{$main_query}
				{$agency_filter}
				{$order_by_sql_str}
				");

				foreach ( $property_sql->result() as $row ){ 

					$csv_row = []; // clear   

					// service price
					$price_var_params = array(
						'service_type' => $row->service_type,
						'property_id' => $row->property_id
					);
					$price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
					$service_price = $price_var_arr['price_breakdown_text'];

					// current variation                 
					$pv_sql = $this->properties_model->get_property_current_variation($v->property_id);             
                    
					if( $pv_sql->num_rows() > 0 ){
						$pv_row = $pv_sql->row();
						$current_variation = "\$".( number_format($pv_row->amount, 2) )." ( ".( ( $pv_row->type == 1 )?'Discount':'Surcharge' )." - {$pv_row->reason} )";
					}else{
						$current_variation = "No variation applied";
					}
					
					$csv_row[] = "{$row->address_1} {$row->address_2}, {$row->address_3} {$row->state} {$row->postcode}"; // property address
					$csv_row[] = $row->service_type_name;          
					$csv_row[] = $service_price; 
					$csv_row[] = $current_variation; 
	
	
					// insert csv row
					fputcsv($csv_file,$csv_row);  
	
				} 
			
				fclose($csv_file); 
				exit; 
				
			} else {

				if (!empty($search)) {
					// $custom_where_address = " AND (CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode)) 
					// 						OR CONCAT_WS(' ', UPPER(p.address_1), UPPER(p.address_2), UPPER(p.address_3), UPPER(p.state), UPPER(p.postcode))) ";

					$where_search = "AND (
										(LOWER(p.address_1) LIKE '%$search%' OR UPPER(p.address_1) LIKE '%$search%') 
									  	OR (LOWER(p.address_2) LIKE '%$search%' OR UPPER(p.address_2) LIKE '%$search%') 
									  	OR (LOWER(p.address_3) LIKE '%$search%' OR UPPER(p.address_3) LIKE '%$search%')
									)";
				}

				// main listing
				$data['property_sql'] = $this->db->query("
				{$sel_query}
				{$main_query}
				{$agency_filter}
				{$where_search}  
				{$order_by_sql_str}				
				LIMIT {$offset}, {$per_page}                
				");

				$new_property_sql = $data['property_sql'];
                $property_arr = array();

                foreach($new_property_sql->result() as $key => $v) {

                    // service price
                    $price_var_params = array(
                        'service_type' => $v->service_type,
                        'property_id' => $v->property_id
                    );
                    $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                    $service_price = $price_var_arr['price_breakdown_text'];

                    // current variation                                
                    $pv_sql = $this->properties_model->get_property_current_variation($v->property_id);

                    if( $pv_sql->num_rows() > 0 ){
                        $pv_row = $pv_sql->row();
                        $current_variation = "\$".( number_format($pv_row->amount, 2) )." ( ".( ( $pv_row->type == 1 )?'Discount':'Surcharge' )." - {$pv_row->reason} )";
                    }else{
                        $current_variation = "No variation applied";
                    }

                    $v->service_price = $service_price;
                    $v->current_variation = $current_variation;
                    $dynamic_property_arr[] = $v;
                }
                
                
                $data['dynamic_property_sql'] = $dynamic_property_arr;

				$data['page_query'] = $this->db->last_query();
	
				// total rows            
				$total_rows_sql = $this->db->query("
				SELECT COUNT(ps.`property_services_id`) AS ps_count
				{$main_query}
				{$agency_filter}
				{$where_search} 
				");
				$total_rows = $total_rows_sql->row()->ps_count; 
	
				$pagi_links_params_arr = array(
					'agency_filter' => $agency_id
				);
				$pagi_link_params = $uri.'?'.http_build_query($pagi_links_params_arr);
				
				$data['export_link'] = "{$uri}/?export=1&".http_build_query($pagi_links_params_arr);
		
				// pagination settings
				$config['page_query_string'] = TRUE;
				$config['query_string_segment'] = 'offset';
				$config['total_rows'] = $total_rows;
				$config['per_page'] = $per_page;
				$config['base_url'] = $pagi_link_params;
		
				$this->pagination->initialize($config);
		
				$data['pagination'] = $this->pagination->create_links();
	
				// pagination count
				$pc_params = array(
				'total_rows' => $total_rows,
				'offset' => $offset,
				'per_page' => $per_page
				);
				$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
	
			}			
			
		}

		// get agency
		$data['agency_sql'] = $this->db->query("
			SELECT 
				`agency_id`, 
				`agency_name`
			FROM `agency` 
			WHERE `status` = 'active'  
			ORDER BY `agency_name` ASC    
		");

        $this->load->view('templates/inner_header', $data);
        $this->load->view($uri, $data);
        $this->load->view('templates/inner_footer', $data);
    }

	/** UPV V2 **/
	public function update_property_variation_v2() 
	{
        
		$title = "Update Property Variation";
        $data['title'] = $title;
        $country_id = $this->config->item('country');
        $uri = '/properties/update_property_variation_v2';
        $data['uri'] = $uri;

        $agency_id = $this->input->get_post('agency_filter');
        $date_filter_str = null;

		$export = $this->input->get_post('export');
		$search = $this->input->get_post('search');

        // pagination
        $per_page = $this->config->item('pagi_per_page');
        $offset = ($this->input->get_post('offset')!="")?$this->input->get_post('offset'):0;

        $agency_filter = null;
		$where_search = "";

		// select query
        $sel_query = "
        SELECT
		p.`property_id`,
		p.`address_1`,
		p.`address_2`,
		p.`address_3`,
		p.`state`,
		p.`postcode`,
		p.`qld_new_leg_alarm_num`,

		ps.`alarm_job_type_id` AS service_type,

		ajt.`type` AS service_type_name,

		a.`agency_id`,
		a.`agency_name`,
		aght.priority
        ";		
        
        // main query body
        $main_query = "
			FROM `property` AS p
			INNER JOIN `property_services` AS ps ON ( p.`property_id` = ps.`property_id` AND ps.`service` = 1 )
			LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
			LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
			LEFT JOIN `agency_priority` as aght ON a.`agency_id` = aght.`agency_id`
			WHERE  p.`deleted` = 0
			AND ( p.`is_nlm` = 0 OR p.`is_nlm` IS NULL )
        "; 

		$order_by_sql_str = "ORDER BY p.`address_2` ASC, p.`address_3` ASC, p.`address_1` ASC";

		if (!empty($search)) {
			// $custom_where_address = " AND (CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode)) 
			// 						OR CONCAT_WS(' ', UPPER(p.address_1), UPPER(p.address_2), UPPER(p.address_3), UPPER(p.state), UPPER(p.postcode))) ";

			$where_search = "AND (
								(LOWER(p.address_1) LIKE '%$search%' OR UPPER(p.address_1) LIKE '%$search%') 
								OR (LOWER(p.address_2) LIKE '%$search%' OR UPPER(p.address_2) LIKE '%$search%') 
								OR (LOWER(p.address_3) LIKE '%$search%' OR UPPER(p.address_3) LIKE '%$search%')
							)";
		}
		

		if( $agency_id > 0 ){

			$agency_filter = "AND a.`agency_id` = {$agency_id}";

			if ($export == 1) { //EXPORT        
            
				// file name
				$date_export = date('YmdHis');
				$file_title = strtolower(str_replace(' ', '_', $title));
				$filename = "{$file_title}_{$date_export}.csv";
	
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename={$filename}");
				header("Pragma: no-cache");
				header("Expires: 0");
	
				// file creation 
				$csv_file = fopen('php://output', 'w');            
	
				$header = array('Address','Service Type','Service Price', 'Current Variation');
	
				// insert csv header
				fputcsv($csv_file, $header);
	
				// main listing
				$property_sql = $this->db->query("
				{$sel_query}
				{$main_query}
				{$agency_filter}
				{$order_by_sql_str}
				");

				foreach ( $property_sql->result() as $row ){ 

					$csv_row = []; // clear   

					// service price
					$price_var_params = array(
						'service_type' => $row->service_type,
						'property_id' => $row->property_id
					);
					$price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
					$service_price = $price_var_arr['price_breakdown_text'];

					// current variation                 
					$pv_sql = $this->properties_model->get_property_current_variation($v->property_id);             
                    
					if( $pv_sql->num_rows() > 0 ){
						$pv_row = $pv_sql->row();
						$current_variation = "\$".( number_format($pv_row->amount, 2) )." ( ".( ( $pv_row->type == 1 )?'Discount':'Surcharge' )." - {$pv_row->reason} )";
					}else{
						$current_variation = "No variation applied";
					}
					
					$csv_row[] = "{$row->address_1} {$row->address_2}, {$row->address_3} {$row->state} {$row->postcode}"; // property address
					$csv_row[] = $row->service_type_name;          
					$csv_row[] = $service_price; 
					$csv_row[] = $current_variation; 
	
	
					// insert csv row
					fputcsv($csv_file,$csv_row);  
	
				} 
			
				fclose($csv_file); 
				exit; 
				
			} else {

				if (!empty($search)) {
					// $custom_where_address = " AND (CONCAT_WS(' ', LOWER(p.address_1), LOWER(p.address_2), LOWER(p.address_3), LOWER(p.state), LOWER(p.postcode)) 
					// 						OR CONCAT_WS(' ', UPPER(p.address_1), UPPER(p.address_2), UPPER(p.address_3), UPPER(p.state), UPPER(p.postcode))) ";

					$where_search = "AND (
										(LOWER(p.address_1) LIKE '%$search%' OR UPPER(p.address_1) LIKE '%$search%') 
									  	OR (LOWER(p.address_2) LIKE '%$search%' OR UPPER(p.address_2) LIKE '%$search%') 
									  	OR (LOWER(p.address_3) LIKE '%$search%' OR UPPER(p.address_3) LIKE '%$search%')
									)";
				}

				// main listing
				$data['property_sql'] = $this->db->query("
				{$sel_query}
				{$main_query}
				{$agency_filter}
				{$where_search}  
				{$order_by_sql_str}				
				LIMIT {$offset}, {$per_page}                
				");

				$new_property_sql = $data['property_sql'];
                $property_arr = array();

                foreach($new_property_sql->result() as $key => $v) {

                    // service price
                    $price_var_params = array(
                        'service_type' => $v->service_type,
                        'property_id' => $v->property_id
                    );
                    $price_var_arr = $this->system_model->get_property_price_variation($price_var_params);
                    $service_price = $price_var_arr['price_breakdown_text'];

                    // current variation                                
                    $pv_sql = $this->properties_model->get_property_current_variation($v->property_id);

                    if( $pv_sql->num_rows() > 0 ){
                        $pv_row = $pv_sql->row();
                        $current_variation = "\$".( number_format($pv_row->amount, 2) )." ( ".( ( $pv_row->type == 1 )?'Discount':'Surcharge' )." - {$pv_row->reason} )";
                    }else{
                        $current_variation = "No variation applied";
                    }

                    $v->service_price = $service_price;
                    $v->current_variation = $current_variation;
                    $dynamic_property_arr[] = $v;
                }
                
                
                $data['dynamic_property_sql'] = $dynamic_property_arr;

				$data['page_query'] = $this->db->last_query();
	
				// total rows            
				$total_rows_sql = $this->db->query("
				SELECT COUNT(ps.`property_services_id`) AS ps_count
				{$main_query}
				{$agency_filter}
				{$where_search} 
				");
				$total_rows = $total_rows_sql->row()->ps_count; 
	
				$pagi_links_params_arr = array(
					'agency_filter' => $agency_id
				);
				$pagi_link_params = $uri.'?'.http_build_query($pagi_links_params_arr);
				
				$data['export_link'] = "{$uri}/?export=1&".http_build_query($pagi_links_params_arr);
		
				// pagination settings
				$config['page_query_string'] = TRUE;
				$config['query_string_segment'] = 'offset';
				$config['total_rows'] = $total_rows;
				$config['per_page'] = $per_page;
				$config['base_url'] = $pagi_link_params;
		
				$this->pagination->initialize($config);
		
				$data['pagination'] = $this->pagination->create_links();
	
				// pagination count
				$pc_params = array(
				'total_rows' => $total_rows,
				'offset' => $offset,
				'per_page' => $per_page
				);
				$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
	
			}			
			
		}

		// get agency
		$data['agency_sql'] = $this->db->query("
			SELECT 
				`agency_id`, 
				`agency_name`
			FROM `agency` 
			WHERE `status` = 'active'  
			ORDER BY `agency_name` ASC    
		");

        $this->load->view('templates/inner_header', $data);
        $this->load->view($uri, $data);
        $this->load->view('templates/inner_footer', $data);
    }

	public function apply_property_variation(){
		
		$property_arr = $this->input->get_post('property_arr');
		$agency_id = $this->input->get_post('agency_id');
		$agency_price_variation = $this->input->get_post('agency_price_variation');

		$today = date('Y-m-d H:i:s');

		foreach( $property_arr as $property_id ){

			// get property variation
			$pv_sql = $this->db->query("
			SELECT COUNT(`id`) AS pv_count
			FROM `property_variation`
			WHERE `property_id` = {$property_id}                    
			AND `active` = 1
			");
			$pv_row = $pv_sql->row();

			// get agency price variation
			$apv_sql = $this->db->query("
			SELECT 
				apv.`id`,
				apv.`amount`,
				apv.`type`,
				apv.`reason` AS apv_reason,
				apv.`scope`,

				apvr.`reason` AS apvr_reason
			FROM `agency_price_variation` AS apv
			LEFT JOIN `agency_price_variation_reason` AS apvr ON apv.`reason` = apvr.`id`
			WHERE apv.`id` = {$agency_price_variation}                    
			"); 
			$apv_row = $apv_sql->row();

			if( $pv_row->pv_count > 0 ){ // it exist, update

				$this->db->query("
				UPDATE `property_variation`
				SET `agency_price_variation` = {$agency_price_variation}
				WHERE `property_id` = {$property_id}  
				AND `active` = 1                  
				");

				$log_details = "Property price variation updated to <b>\$".number_format($apv_row->amount, 2)."</b> ".( ( $apv_row->type == 1 )?'Discount':'Surcharge' );
				$params = array(
					'title' => 65, // Property Update
					'details' => $log_details,
					'display_in_vpd' => 1,
					'agency_id' => $agency_id,
					'created_by_staff' => $this->session->staff_id,
					'property_id' => $property_id
				);
				$this->system_model->insert_log($params);

			}else{ // insert

				if( $agency_price_variation > 0 ){
					
					// insert new 
					$this->db->query("
					INSERT INTO 
					`property_variation`(
						`property_id`,
						`agency_price_variation`,
						`date_applied`
					)
					VALUES(
						{$property_id},
						{$agency_price_variation},
						'{$today}'
					)                 
					");
			
					$log_details = "Property price variation set to <b>\$".number_format($apv_row->amount, 2)."</b> ".( ( $apv_row->type == 1 )?'Discount':'Surcharge' );
					$params = array(
						'title' => 65, // Property Update
						'details' => $log_details,
						'display_in_vpd' => 1,
						'agency_id' => $agency_id,
						'created_by_staff' => $this->session->staff_id,
						'property_id' => $property_id
					);
					$this->system_model->insert_log($params);

				}                        

			}

		}

		$success_message = "Price variation has been applied";
		$this->session->set_flashdata(array('success_msg'=>$success_message,'status'=>'success'));
		redirect("/properties/update_property_variation/?agency_filter={$agency_id}");
		
	}

	public function details(){
        if(empty($this->session->staff_id)){
            redirect('/login/index');
        }
        $this->load->model('properties_model');
		$this->load->model('agency_model');
		$this->load->model('api_model');

		$property_id = $this->input->get_post('id');

		// Get the previous URL from the referrer
		$previous_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		// Parse the previous URL to get the controller and method
		$parsed_url = parse_url($previous_url);
		$path_segments = explode('/', $parsed_url['path']);
		$previous_controller = isset($path_segments[1]) ? $path_segments[1] : '';
		$previous_method = isset($path_segments[2]) ? $path_segments[2] : '';

		if ($previous_controller == 'properties' && $previous_method == 'details') {
			if (empty($this->input->get_post('tab'))) {
				$tab = 1;
			} else {
				$tab  = $this->input->get_post('tab');
			}
			// Update the last selected tab in the session
			$this->session->set_userdata('last_selected_tab', $tab);
		} else {
			if (empty($this->input->get_post('tab'))) {
				$tab = $this->session->userdata('last_selected_tab') ?? 1;
			} else {
				$tab = $this->session->userdata('last_selected_tab') ?? $this->input->get_post('tab');
			}
		}

        if(!$property_id && empty($property_id)){ //catch empty property id
            show_404();
        }

        ##pass data
        $data['tab'] = $tab;

        ##pass data
        $data['property_id'] = $property_id; #pass to view

		//get state start
        $data['getCountryState'] = $this->properties_model->getCountryState();
        //get state end

        ## staff id
        $staff_id = $this->session->staff_id;
        $data['staff_id'] = $this->session->staff_id; #pass to view

			$sel_query = "
			p.`property_id`,
			p.`address_1`,
			p.`address_2`,
			p.`address_3`,
			p.`state`,
			p.`postcode`,
			p.`holiday_rental`,
			p.`comments`,
			opd.building_name,
			pl.`code`,
			p.key_number,
			p.alarm_code,
			p.prop_upgraded_to_ic_sa,
			p.bne_to_call,
			p.holiday_rental,
			p.send_to_email_not_api,
			p.no_keys,
			p.no_en,
			p.no_dk,
			p.nlm_display,
			p.is_sales,
			p.requires_ppe,
			p.service_garage,
			p.manual_renewal,
			p.qld_new_leg_alarm_num,
			p.subscription_billed,
			p.pm_id_new,
			p.is_nlm,
			p.deleted,
			p.agency_deleted,
			p.landlord_firstname,
			p.landlord_lastname,
			p.landlord_mob,
			p.landlord_ph,
			p.landlord_email,
			p.third_party_url,
			p.`lat`,
			p.`lng`,

			a.`agency_id`,
			a.`agency_name`,
			a.`status`,
			a.allow_upfront_billing,

			aat.`access_token`,

			apd.api_prop_id,
			apd.api,

			r.`region_name`,
			sr.`subregion_name`
			";

			// paginated
			$params = array(
				'sel_query' => $sel_query,

				'p_deleted' => 0,
				'exc_deleted' => 1,
				'is_nlm' => '0', // 0 means not include NLM filter in the query / not declaring is_nlm in the params means the is_nlm filter added
				'property_id' => $property_id,
				'join_table' => array('property_lockbox', 'api_property_data','join_regions'),

				'custom_joins' => array(
					'join_table' => 'other_property_details AS opd',
					'join_on' => 'p.`property_id` = opd.`property_id`',
					'join_type' => 'left'
				),

				'custom_joins_arr' => array(
                
					array(
						'join_table' => ' `agency_api_tokens` AS aat',
						'join_on' => 'a.`agency_id` = aat.`agency_id`',
						'join_type' => 'left'
					)					
				
				),

				'display_query' => 0
			);

		$prop_row = $this->properties_model->get_properties($params)->row_array();
		$data['row'] = $prop_row;
		$data['last_query'] = $this->db->last_query();

		$get_agency = $this->db->query("SELECT agency_id FROM property WHERE property_id = {$property_id}");
		$get_agency_row = $get_agency->row();
		$data['agency_id'] = $get_agency_row->agency_id; #pass to view

		// check if connected to API
		$sel_query = "
			agen_api_tok.`agency_api_token_id`,
			agen_api_tok.`agency_id`,
			agen_api_tok.`api_id`,

			agen_api.`api_name`
		";
		$api_token_params = array(
			'sel_query' => $sel_query,
			'active' => 1,
			'agency_id' => $get_agency_row->agency_id,
			'display_query' => 0
		);
		$data['api_token_sql'] = $this->api_model->get_agency_api_tokens($api_token_params);

		$data['connected_to_pme'] = false;
		$data['connected_to_palace'] = false;

		$check_staff_delete_sql = $this->properties_model->can_delete_property();
		$check_staff_delete = $check_staff_delete_sql->row();
		$data['can_delete_prop'] = ( $check_staff_delete->sp_count > 0 )?true:false;

		/* DISABLED BY GHERX: get rid to reduce API call and re use 'vjd_vpd_apis_error_warning_message' method instead
		$api_sel_query = "
            agen_api_tok.`agency_api_token_id`,
            agen_api_tok.`agency_id`,
            agen_api_tok.`api_id`,
            agen_api_tok.`access_token`,
            agen_api_tok.`refresh_token`,
            
            agen_api.`api_name`
        ";
		$api_token_params = array(
			'sel_query' => $api_sel_query,
			'active' => 1,
			'agency_id' => $get_agency_row->agency_id,
			'display_query' => 0
		);
		$api_token_sql = $this->api_model->get_agency_api_tokens($api_token_params);
		$data['api_token_row'] = $api_token_sql->row_array();

		$connTextApi = "";
		$controlerApi = "";
		$enableApi = false;
		$api_coonection_det_url = "";
		$checkIdApi="";

		$cak_sql = $this->db->query("
            SELECT id
            FROM `console_api_keys`
            WHERE `agency_id` = {$get_agency_row->agency_id}
            AND active = 1
        ");

		if($cak_sql->num_rows()>0){ //console api

			$cak_sql2 = $this->db->query("
                SELECT cp.console_prop_id
                FROM `property` AS p
                INNER JOIN `console_properties` AS cp ON p.`property_id` = cp.`crm_prop_id`
                WHERE cp.`active` = 1
                AND cp.`crm_prop_id` = {$property_id}
            ");
			$cak_row2 = $cak_sql2->row();

			$enableApi = true;
			$connTextApi = "Console";
			$controlerApi = "console";
			$checkIdApi = $cak_row2->console_prop_id;
			$prop_is_connected_to_api = false;

			if($cak_sql2->num_rows()>0){ //property is connected to console
				$prop_is_connected_to_api = true;
				$api_coonection_det_url = "/{$controlerApi}/connection_details/{$property_id}";
			} else {
				$prop_is_connected_to_api = false;
				$api_coonection_det_url = "/{$controlerApi}/to_connect/{$property_id}";
			}
		} else {
			$api_prop_data_row = $this->db->get_where('api_property_data',array('crm_prop_id'=>$property_id))->row();
			if( $api_token_sql->num_rows()>0 ){ //connected to api's != console
                    
				$enableApi = true;
				$checkIdApi = $api_prop_data_row->api_prop_id;
				foreach($api_token_sql->result() as $api_row){
					if($api_row->api_id==1){
						$connTextApi = "PropertyMe";
						$controlerApi = "property_me";
						$api_coonection_det_url = "/{$controlerApi}/property/{$property_id}/{$get_agency_row->agency_id}";
						//get pme api tenants
						if($checkIdApi!=""){
							$prop_is_connected_to_api = true;
						}
					} else if($api_row->api_id==3){
						$connTextApi = "MRI Property Tree";
						$controlerApi = "property_tree";
						$api_coonection_det_url = "/{$controlerApi}/connection_details/{$property_id}";
						if($checkIdApi!=""){
							$prop_is_connected_to_api = true;
						}
					} else if($api_row->api_id==4){
						$connTextApi = "Palace";
						$controlerApi = "palace";
						$api_coonection_det_url = "/{$controlerApi}/property/{$property_id}/{$get_agency_row->agency_id}";
						if($checkIdApi!=""){
							$prop_is_connected_to_api = true;
						}
					} else if($api_row->api_id==6){
						$connTextApi = "OurTradie";
						$controlerApi = "ourtradie";
						$api_coonection_det_url = "/{$controlerApi}/property/{$property_id}/{$get_agency_row->agency_id}";
						if($checkIdApi!=""){
							$prop_is_connected_to_api = true;
						}
					}
				}
			}
			$data['connTextApi'] = $connTextApi;
            $data['controlerApi'] = $controlerApi;
            $data['enableApi'] = $enableApi;
            $data['api_coonection_det_url'] = $api_coonection_det_url;
            $data['prop_is_connected_to_api'] = $prop_is_connected_to_api;
		}*/

		if ($tab==1) {
			// Source of Property
			$data['pfoc_sql'] = $this->db->query("
				SELECT pfoc.`company_id`, sac.company_name
				FROM `properties_from_other_company` AS pfoc
					LEFT JOIN smoke_alarms_company AS sac ON pfoc.company_id = sac.sac_id
				WHERE pfoc.`property_id` = {$property_id}
				AND pfoc.`active` = 1
			");

			// Property Manager
			$data['prop_manager'] = $this->db->query("
				SELECT agency_user_account_id,fname,lname,email
				FROM `agency_user_accounts`
				WHERE `active` = 1
				AND `agency_id` = {$get_agency_row->agency_id} ORDER BY fname                
			");

			// Agency Key Pick up Address
			$data['agency_add_sql_str'] = $this->db->query("
				SELECT 
					`id` AS agen_add_id,
					`address_1` AS agen_add_street_num, 
					`address_2` AS agen_add_street_name, 
					`address_3` AS agen_add_suburb 	
				FROM `agency_addresses`
				WHERE `agency_id`={$get_agency_row->agency_id}
				AND `type` = 2
			");

			// Property Price Variation
			$data['pv_sql'] = $this->db->query("
				SELECT `agency_price_variation`
				FROM `property_variation`
				WHERE `property_id` = {$property_id}                    
				AND `active` = 1      
			");
			$data['sql_agency_var'] = $this->db->query("
				SELECT 
					apv.`id`,
					apv.`amount`,
					apv.`type`,
					apv.`reason` AS apv_reason,
					apv.`scope`,

					apvr.`reason` AS apvr_reason
				FROM `agency_price_variation` AS apv
				LEFT JOIN `agency_price_variation_reason` AS apvr ON apv.`reason` = apvr.`id`
				WHERE apv.`agency_id` = {$get_agency_row->agency_id}                    
				AND apv.`active` = 1
				AND apv.`scope` = 1
				ORDER BY 
					apv.`type` ASC, 
					apv.`scope` ASC,
					apvr.`reason` ASC         
			");

			// Subscription Start Date
			$data['prop_subs_sql'] = $this->db->query("
				SELECT 
					`subscription_date`,
					`source`
				FROM `property_subscription`
				WHERE `property_id` = {$property_id}
			");

			// serviced to SATS
			$data['ps_sql3'] = $this->db->query("
			SELECT COUNT(`property_services_id`) AS ps_count
			FROM `property_services`
			WHERE `property_id` = {$property_id}
			AND `service` = 1
			");

			// get leaving reason                                                
			$data['lr_sql'] = $this->db->query("
			SELECT *
			FROM `leaving_reason`
			WHERE `active` = 1
			AND `display_on` IN(2,4,5)
			ORDER BY `reason` ASC
			");  

			// Source of Property
			$data['sa_comp_sql'] = $this->db->query("
				SELECT `sac_id`, `company_name`
				FROM `smoke_alarms_company`
				WHERE `active` = 1
			");

			$data['agency_add_sql_str1'] = $this->db->query("
				SELECT 
					`address_1`, 
					`address_2`, 
					`address_3`,
					`state`,
					`postcode`
				FROM `agency`
				WHERE `agency_id`={$get_agency_row->agency_id}
			");

			/**
			 *ON LOAD RUN QUERY (gherx)
			 *get/check recent job != onceoff/!=upgront
			 *update property retest_date
			 */

			//First query without assigned_tech and status filter
			$sql_recent_job_no_tech_filter = $this->db->query("
			SELECT j.id as j_id, j.date AS jdate, j.job_type as j_type
			FROM `jobs` AS j
			LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
			LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
			WHERE j.`property_id` = {$property_id}
			AND j.`del_job` = 0
			AND a.`country_id` = {$this->config->item('country')}
			ORDER BY j.`date` DESC
			LIMIT 1
			");
			$sql_recent_job_no_tech_filter_fet_arr = $sql_recent_job_no_tech_filter->row_array();

			if($sql_recent_job_no_tech_filter_fet_arr['jdate'] > '2015-12-31'){
				//$assigned_tech_filter = "AND j.`assigned_tech` IS NOT NULL AND j.`assigned_tech` !=1 AND j.`assigned_tech` !=2"; // Disabled > Reason: We are assuming that SOMEONE has attended that property at that time, so we only need to attend a year after that point
				$assigned_tech_filter = "AND j.`assigned_tech` IS NOT NULL AND j.`assigned_tech` !=2"; // New > removed Other Supplier filter > Reason: We are assuming that SOMEONE has attended that property at that time, so we only need to attend a year after that point
			}

			//Second query with assigned_tech filter option based on job date condition
			$sql_recent_job = $this->db->query("
				SELECT j.id as j_id, j.date AS jdate, j.job_type as j_type
				FROM `jobs` AS j
				LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
				LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
				WHERE j.`property_id` = {$property_id}
				AND j.`status` = 'Completed'
				AND j.`del_job` = 0
				AND a.`country_id` = {$this->config->item('country')}
				{$assigned_tech_filter}
				ORDER BY j.`date` DESC
				LIMIT 1
			");

			$recent_jobdate_fetch_arr = $sql_recent_job->row_array();
			$recent_jobdate = $recent_jobdate_fetch_arr['jdate'];
			$recent_job_type = $recent_jobdate_fetch_arr['j_type'];

			if( $sql_recent_job->num_rows()>0 ){ //recent completed job found
				if( isset($property_id) && $property_id!="" ){ //check property id url param
					if($recent_job_type=="Once-off"){ //once-off job > update retest_date to 1521-03-16
						$this->db->query("
							UPDATE `property`
							SET `retest_date` = '1521-03-16'
							WHERE `property_id` = {$property_id}
						");
					}else{ // not once-off job > update retest_date to job_date+1year
						$this->db->query("
							UPDATE `property`
							SET `retest_date` = DATE_ADD('$recent_jobdate', INTERVAL 1 YEAR)
							WHERE `property_id` = {$property_id}
						");
					}

				}
			}else{ //if empty result > find job != Completed if return row update retest_date to job_date+365 otherwise update to null
				$sql_not_completed_job = $this->db->query("
					SELECT j.id as j_id, j.date AS jdate, j.job_type as j_type, j.created as j_created
					FROM `jobs` AS j
					LEFT JOIN `property` AS p ON j.`property_id` = p.`property_id`
					LEFT JOIN `agency` AS a ON p.`agency_id` = a.`agency_id`
					WHERE j.`property_id` = {$property_id}
					AND j.`status` != 'Completed'
					AND j.`del_job` = 0
					AND a.`country_id` = {$this->config->item('country')}
					ORDER BY j.`date` DESC
					LIMIT 1
				");
				$recent_not_completed_jobdate_fetch_arr = $sql_not_completed_job->row_array();
				$recent_not_completed_jobdate = $recent_not_completed_jobdate_fetch_arr['j_created'];
				if( $sql_not_completed_job->num_rows()>0 ){ //active job found update to job date + 635 days
					if( isset($property_id) && $property_id!="" ){ //check property id
						$this->db->query("
							UPDATE `property`
							SET `retest_date` = DATE_ADD('$recent_not_completed_jobdate', INTERVAL 1 YEAR)
							WHERE `property_id` = {$property_id}
						");
					}
				}else{ //no active job found > update retest_date to NULL
					if( isset($property_id) && $property_id!="" ){ //check property id
						$this->db->query("
							UPDATE `property`
							SET `retest_date` = '1521-03-17'
							WHERE `property_id` = {$property_id}
						");
					}
				}

			}


		} elseif($tab==2){
			$data['services'] = $this->properties_model->get_properties_services($property_id);
			$data['last_query'] = $this->db->last_query();

			$data['service_to_sats_sql_str'] = $this->properties_model->service_to_sats_sql_str();
			$data['is_price_increase_excluded'] = $this->properties_model->price_increase_excluded_agency($property_id);

			$this->db->select('agency_id');
			$this->db->from('property');
			$this->db->where('property_id', $property_id);
			$pt_sql = $this->db->get();
			$pt_row = $pt_sql->row();
			$data['agency_id'] = $pt_row->agency_id;

			$data['agen_serv_sql'] = $this->properties_model->agen_serv_sql($property_id,$pt_row->agency_id);

			$agency_id_query = $this->properties_model->agency_id_query($property_id);
			$data['agency_id_row'] = $agency_id_query->row();

			// query for the VPD list jobs
			$data['plog_sql_str'] = $this->properties_model->plog_sql_str($property_id);

			// Invoice history
			$data['inv_his_sql'] = $this->properties_model->inv_his_sql($property_id);
			
			//dropdown list for creating new job
			$data['jt_Sql'] = $this->properties_model->create_jobs_vpd($property_id);

			// Alarm Preference
			$data['pref_al_sql'] = $this->db->query("
				SELECT al_p.`alarm_pwr_id`, al_p.`alarm_pwr`, al_p.`alarm_make`
				FROM `agency_alarms` AS aa
				LEFT JOIN `alarm_pwr` AS al_p ON aa.`alarm_pwr_id` = al_p.`alarm_pwr_id`
				WHERE aa.`agency_id` = {$pt_row->agency_id}
				AND aa.`alarm_pwr_id` IN (10,14,22)
			");

			$data['ps_sql'] = $this->db->query("
				SELECT 
					ps.`property_services_id`,
					ps.`price`,
					ps.`service` AS serv_status,

					ajt.`id` AS ajt_id,
					ajt.`type` AS ajt_type
				FROM `property_services` AS ps
				LEFT JOIN `alarm_job_type` AS ajt ON ps.`alarm_job_type_id` = ajt.`id`
				WHERE ps.`property_id` = {$property_id}
				AND ps.`service` != 1
			");

			// Subscription Start Date
			$data['prop_subs_sql'] = $this->db->query("
				SELECT 
					`subscription_date`,
					`source`
				FROM `property_subscription`
				WHERE `property_id` = {$property_id}
			");

			// serviced to SATS
			$data['ps_sql3'] = $this->db->query("
			SELECT COUNT(`property_services_id`) AS ps_count
			FROM `property_services`
			WHERE `property_id` = {$property_id}
			AND `service` = 1
			");

			//Get all property manager
			$pm_sel_query = "aua.agency_user_account_id, aua.email, aua.fname, aua.lname";
			$pm_params = array(
				'sel_query' => $pm_sel_query,
				'active' => 1,
				'agency_id' => $get_agency_row->agency_id,
				'sort_list' => array(
					array('order_by' => 'aua.fname','sort' => 'ASC')
				)
			);
			$data['pm'] = $this->properties_model->get_agency_pm($pm_params);

		} elseif($tab==3){
			// $data['property_old_logs'] = $this->properties_model->get_properties_old_logs($property_id);
			// $data['last_query'] = $this->db->last_query();

			// get console property ID
			$console_prop_sql = $this->db->query("
			SELECT cp.`console_prop_id`
			FROM `console_properties` AS cp 
			WHERE cp.`crm_prop_id` = {$property_id}													       
			");
			$data['console_prop_sql'] = $console_prop_sql;

		} elseif($tab==5){
			$data['property_old_logs'] = $this->properties_model->get_properties_old_logs($property_id);
			$data['last_query_old'] = $this->db->last_query();
			$per_page = $this->config->item('pagi_per_page');
			$offset = $this->input->get_post('offset');

			$params = array(
				'property_id' => $property_id,
				'limit' => $per_page,
				'offset' => $offset
			);

			$data['property_new_logs'] = $this->properties_model->get_properties_new_logs($params);
			$data['last_query'] = $this->db->last_query();

			$pagi_links_params_arr = array(
			);

			// pagination link
			$pagi_link_params = "/properties/details/?id={$property_id}&tab=5".http_build_query($pagi_links_params_arr);

			$params = array(
				'property_id' => $property_id
			);

			$sql_total = $this->properties_model->get_properties_new_logs($params);
			$total_rows = $sql_total->num_rows();

			// pagination settings
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'offset';
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['base_url'] = $pagi_link_params;

			$this->pagination->initialize($config);

			$data['pagination'] = $this->pagination->create_links();

			// pagination count
			$pc_params = array(
				'total_rows' => $total_rows,
				'offset' => $offset,
				'per_page' => $per_page
			);

			$data['pagi_count'] = $this->jcclass->pagination_count($pc_params);

			$this->db->select('ltup.log_titles_id AS log_title_id,lt.title_name');
            $this->db->from('log_title_usable_pages AS ltup');
            $this->db->join('log_titles AS lt','lt.log_title_id = ltup.log_titles_id','left');
            $this->db->where('ltup.show_in',2);
            $this->db->where('lt.active',1);
            $data['log_title_for_contact_type_dropdown'] = $this->db->get();

		} elseif($tab==4){
			$data['property_files'] = $this->properties_model->get_properties_files($property_id);
			$data['last_query'] = $this->db->last_query();
		}

		$data['exclude_gmap'] = true;
        $data['title'] = "Property Details";
		$data['prop_full_add'] = trim("{$prop_row['address_1']} {$prop_row['address_2']} {$prop_row['address_3']} {$prop_row['state']} {$prop_row['postcode']}");

        $this->load->view('templates/inner_header', $data);
        $this->load->view('properties/view_property_details', $data);
        $this->load->view('templates/inner_footer', $data);

    }

	public function ajax_update_property(){ 
		$response['status'] = false;
		$property_id = $this->input->get_post('property_id');
		$property_update = $this->input->get_post('property_update');
		$post = $this->input->post();

		// coordinates
		$p_lat = $this->db->escape_str($this->input->get_post('p_lat'));
		$p_lng = $this->db->escape_str($this->input->get_post('p_lng'));

		if ($property_update == 'update_address') {


			if( $p_lat != '' && $p_lng != '' ){ // if coordinates are empty, get it from google API

				$address_str = "{$this->input->get_post('address_1')} {$this->input->get_post('address_2')} {$this->input->get_post('address_3')} {$this->input->get_post('state')} {$this->input->get_post('postcode')}";
				$coordinate = $this->system_model->getGoogleMapCoordinates($address_str);

				$p_lat = $coordinate['lat']; // latitude
				$p_lng = $coordinate['lng']; // longitute

			}
			
			##update property name
			$property_data = array(
				'address_1' => $this->input->get_post('address_1'),
				'address_2' => $this->input->get_post('address_2'),
				'address_3' => $this->input->get_post('address_3'),
				'state' => $this->input->get_post('state'),
				'postcode' => $this->input->get_post('postcode'),
				'lat' => $p_lat,
				'lng' => $p_lng
			);
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif($property_update == 'update_building_name'){

			$check_other_property_details = $this->db->query("
				SELECT * FROM other_property_details WHERE property_id = {$property_id}
			");
			$check_row = $check_other_property_details->num_rows();

			if ($check_row == 0) {
				$property_data = array(
					'property_id' => $property_id,
					'building_name' => $this->input->get_post('building_name')
				); 
				$this->db->insert('other_property_details', $property_data);
			} else {
				$property_data = array(
					'building_name' => $this->input->get_post('building_name')
				);                    
				$this->db->where('property_id', $property_id);
				$this->db->update('other_property_details', $property_data);
			}
			
			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_comments') {
			$property_data = array(
				'comments' => $this->input->get_post('comments')
			);
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_lockbox_code') {

			$check_property_lockbox = $this->db->query("
				SELECT * FROM property_lockbox WHERE property_id = {$property_id}
			");
			$check_row = $check_property_lockbox->num_rows();

			if ($check_row == 0) {
				$data = array(
					'code' => $this->input->get_post('code'),
					'property_id' => $property_id
				);
				$this->db->insert('property_lockbox', $data);
			} else {
				$property_data = array(
					'code' => $this->input->get_post('code')
				);
				$this->db->where('property_id', $property_id);
				$this->db->update('property_lockbox', $property_data);
			}

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_api_id') {
			$this->db->query("
				UPDATE `api_property_data`
				SET `api_prop_id` = NULL, `active` = 0
				WHERE `crm_prop_id` = {$property_id}
			");

			// console API, clear CRM property ID to unlink from console properties table
			$this->db->query("
			UPDATE `console_properties`
			SET `crm_prop_id` = NULL
			WHERE `crm_prop_id` = {$property_id}
			");

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_key_number') {
			$property_data = array(
				'key_number' => $this->input->get_post('key_number')
			);

			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_alarm_code') {
			$property_data = array(
				'alarm_code' => $this->input->get_post('alarm_code')
			);
			
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_price_varation') {

			$today = date('Y-m-d');
			$pv_sql = $this->db->query("
			SELECT COUNT(`id`) AS pv_count
			FROM `property_variation`
			WHERE `property_id` = {$property_id}                    
			AND `active` = 1
			");
			$pv_row = $pv_sql->row();

			if ($pv_row->pv_count > 0) {
				$property_data = array(
					'agency_price_variation' => $this->input->get_post('agency_price_variation')
				);
				$this->db->where('property_id', $property_id);
				$this->db->update('property_variation', $property_data);
			} else {
				$this->db->query("
				INSERT INTO 
				`property_variation`(
					`property_id`,
					`agency_price_variation`,
					`date_applied`
				)
				VALUES(
					{$property_id},
					{$this->input->get_post('agency_price_variation')},
					'{$today}'
				) 
				");
			}

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_pm') {
			$property_data = array(
				'pm_id_new' => $this->input->get_post('pm_id_new')
			);
			
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_prop_upgraded_to_ic_sa') {
			$property_data = array(
				'prop_upgraded_to_ic_sa' => $this->input->get_post('prop_upgraded_to_ic_sa')
			);
			
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_agency_keys') {

			$check_address_sql = $this->db->query("SELECT `id` FROM `property_keys` WHERE `property_id`='{$property_id}'");
			if ($check_address_sql->num_rows() > 0 ) {
				$property_data = array(
					'agency_addresses_id' => $this->input->get_post('agency_addresses_id')
				);

				$this->db->where('property_id', $property_id);
				$this->db->update('property_keys', $property_data);
			} else {
				$data = array(
					'property_id' => $property_id,
					'agency_addresses_id' => $this->input->get_post('agency_addresses_id')
				);
				$this->db->insert('property_keys', $data);
			}

			##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_tt_boxes') {

			$property_data = array(
				$this->input->get_post('field_name') => $this->input->get_post('final_value')
			);
			
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited 
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_tt_boxes_lightbox') {

			$property_data = array(
				$this->input->get_post('name') => $this->input->get_post('value')
			);
			
			$this->properties_model->update_property($property_id,$property_data);

			##LOGS > Fields Edited 
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_nlm') {

			$nlm_from = $this->security->xss_clean($this->input->post('nlm_from'));
			$reason_they_left = $this->security->xss_clean($this->input->post('reason_they_left'));
			$other_reason = $this->security->xss_clean($this->input->post('other_reason'));
			$agency_id = $this->security->xss_clean($this->input->post('agency_id'));

			if($this->gherxlib->NLMjobStatusCheck($property_id)===TRUE){

				// $cntry = $this->gherxlib->getCountryViaCountryId($this->CI->session->country_id);

				$response['status'] = false;
				$response['has_active_jobs'] = true;
				$response['stat_msg'] = "This property has an active job so can’t be NLM";

			}else{											

				$nlm_params = array(
					'nlm_from'=> $nlm_from,
					'reason_they_left'=> $reason_they_left,
					'other_reason'=> $other_reason,
					'agency_id'=> $agency_id,
					'nlm_from_agency'=> false,
					'agency_status'=> ''
				);
				$this->properties_model->nlm_property($property_id, $nlm_params);

				$response['status'] = true;
			}

		} elseif ($property_update == 'restore_prop') {
			
			$del_tenant = $this->input->post('del_tenant');
			$this->properties_model->restore_property_vpd($property_id,$del_tenant);

			// ##LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'update_landlord') {
			
			$property_data = array(
				'landlord_firstname' => $this->input->get_post('landlord_firstname'),
				'landlord_lastname' => $this->input->get_post('landlord_lastname'),
				'landlord_mob' => $this->input->get_post('landlord_mob'),
				'landlord_ph' => $this->input->get_post('landlord_ph'),
				'landlord_email' => $this->input->get_post('landlord_email')
			);
			
			$this->properties_model->update_property($property_id,$property_data);

			// LOGS > Fields Edited
			$this->_insertPropertiesLogs($post,1);

			$response['status'] = true;
		} elseif ($property_update == 'delete_vpd_files') {
			
			$property_data = array(
				'active' => 0
			);

			$this->db->where('property_id', $property_id);
			$this->db->where('property_files_id', $this->input->get_post('property_files_id'));
			$this->db->update('property_files', $property_data);

			$property_files_id = $this->input->get_post('property_files_id');
			$property_files_sql = $this->db->query("SELECT `filename` FROM property_files WHERE `property_files_id`= {$property_files_id}");
			$file_row = $property_files_sql->row();
			
			$log_details = "<strong>Property Files</strong> {$file_row->filename} Deleted";
			$log_params = array(
				'title' => 41, // file upload
				'details' => $log_details,
				'display_in_vpd' => 1,
				'created_by_staff' => $this->session->staff_id,
				'property_id' => $property_id
			);
			$this->system_model->insert_log($log_params);

            $response['status'] = true;
		} elseif ($property_update == 'delete_vpd_files_old') {

			$file = $this->input->get_post('file');

			// non allowed chars
			$notallowed = array("/", "\\", "..");
			$file = str_replace($notallowed, "", $file);

			@unlink('./property_files/' . $property_id . "/" . $file);

			$log_details = "<strong>Property Files</strong> {$file} Deleted";
			$log_params = array(
				'title' => 41, // file upload
				'details' => $log_details,
				'display_in_vpd' => 1,
				'created_by_staff' => $this->session->staff_id,
				'property_id' => $property_id
			);
			$this->system_model->insert_log($log_params);

            $response['status'] = true;
		} elseif ($property_update == 'upload_vpd_files') {
			
			if (!empty($_FILES['fileupload']['name']) && $property_id!=""){

				$upload_path = "./uploads/property_files/";
				$upload_folder = "/uploads/property_files/";
	
				if(!is_dir($upload_folder)){
					mkdir($upload_path,0777,true);
				}
	
				// file name
				$filename = preg_replace('/#+/', 'num', $_FILES['fileupload']['name']);
				$filename2 = preg_replace('/\s+/', '_', $filename);
				$append_text = 'pf_'.rand().date('YmdHis');
				$file_new_name = "{$append_text}".$filename2;
	
				$updload_params = array(
					'upload_path' => $upload_path,
					'file_name' => $file_new_name,
					'allowed_types' => "gif|jpg|png|pdf|jped|doc|xls|xlsx|bmp|txt"
				);
				$upload_agency_file = $this->gherxlib->do_upload('fileupload', $updload_params);

				$data = array(
					'property_id' => $property_id,
					'path' => $upload_folder,
					'filename' => $file_new_name,
					'date_created' => date("Y-m-d H:i:s"),
					'active' => 1
				);
				$this->db->insert('property_files', $data);

				// Insert log
				$log_details = "<strong>Property Files</strong> {$file_new_name} Uploaded";
				$log_params = array(
					'title' => 41, // file upload
					'details' => $log_details,
					'display_in_vpd' => 1,
					'created_by_staff' => $this->session->staff_id,
					'property_id' => $property_id
				);
				$this->system_model->insert_log($log_params);

			}

			$response['status'] = true;
		} elseif ($property_update == 'add_event_new_logs') {
			// Insert log
			$log_params = array(
				'title' => $this->input->get_post('title'), // file upload
				'details' => $this->input->get_post('details'),
				'display_in_vpd' => 1,
				'created_by_staff' => $this->session->staff_id,
				'important' => $this->input->get_post('important'),
				'property_id' => $property_id
			);
			$this->system_model->insert_log($log_params);

			$response['status'] = true;
		} elseif ($property_update == 'change_service') {
			$property_data = array(
				'property_id' => $this->input->get_post('property_id'),
				'agency_id' => $this->input->get_post('agency_id'),
				'from_service_type' => $this->input->get_post('from_service_type'),
				'to_service_type' => $this->input->get_post('to_service_type')
			);
			$this->properties_model->vpd_change_service($property_data);

			$response['status'] = true;
		} elseif ($property_update == 'vpd_add_jobs') {
			$property_data = array(
				'property_id' => $this->input->get_post('property_id'),
				'alarm_job_type_id' => $this->input->get_post('alarm_job_type_id'),
				'job_type' => $this->input->get_post('job_type'),
				'price' => $this->input->get_post('price'),
				'vacant_from' => $this->input->get_post('vacant_from'),
				'new_ten_start' => $this->input->get_post('new_ten_start'),
				'problem' => $this->input->get_post('problem'),
				'service_name' => $this->input->get_post('service_name'),
				'staff_id' => $this->input->get_post('staff_id'),
				'delete_tenant' => $this->input->get_post('delete_tenant'),
				'vacant_prop' => $this->input->get_post('vacant_prop'),
				'agency_id' => $this->input->get_post('agency_id'),
				'workorder_notes' => $this->input->get_post('workorder_notes'),
				'job_status' => $this->input->get_post('job_status'),
				'onhold_start_date' => $this->input->get_post('onhold_start_date'),
				'onhold_end_date' => $this->input->get_post('onhold_end_date'),
				'job_date' => $this->input->get_post('job_date'),
				'jtech_sel' => $this->input->get_post('jtech_sel'),
				'work_order' => $this->input->get_post('work_order'),
				'preferred_alarm_id' => $this->input->get_post('preferred_alarm_id')
			);
			$this->properties_model->add_jobs_vpd($property_data);

			$response['status'] = true;
		} elseif ($property_update == 'update_subscription_source') {
			$subscription_date = $this->input->get_post('subscription_date');
			$subscription_source = $this->input->get_post('subscription_source');

			$property_subscription_sql = $this->db->query("SELECT `id` FROM property_subscription WHERE `property_id`='{$property_id}'");

			if ($property_subscription_sql->num_rows() > 0) {
				$property_data = array(
					'subscription_date' => $subscription_date,
					'source' => $subscription_source
				);
				$this->db->where('property_id', $property_id);
				$this->db->update('property_subscription', $property_data);
			} else {
				$data = array(
					'property_id' => $property_id,
					'subscription_date' => $subscription_date,
					'source' => $subscription_source,
					'date_updated' => date('Y-m-d')
				);
				$this->db->insert('property_subscription', $data);
			}

			$this->_insertPropertiesLogs($post,1);
			$response['status'] = true;
		} elseif ($property_update == 'update_to_ic_service') {
			$this->properties_model->job_to_upgrade_to_ic_service($this->input->get_post('job_to_upgrade_to_ic_service'));
			$response['status'] = true;
		} elseif ($property_update == 'vpd_service_due_job') {
			$this->properties_model->vpd_service_due_job($property_id,$this->input->get_post('hid_smoke_price'),$this->input->get_post('agency_id'));
			$response['status'] = true;
		} elseif ($property_update == 'non_active_service_update') {
			$this->properties_model->non_active_service_update($property_id,$this->input->get_post('non_active_ps_id_arr'),$this->input->get_post('non_active_service_status_arr'));
			$response['status'] = true;
		} elseif ($property_update == 'add_new_service_type') {
			$this->properties_model->add_new_service_type($property_id,$this->input->get_post('agency_id'),$this->input->get_post('new_service_type'),$this->input->get_post('new_service_type_status'));
			$response['status'] = true;
		} elseif ($property_update == 'check_invoice_payment') {
			$this->properties_model->check_invoice_payment($property_id);
			$response['status'] = true;
		} elseif ($property_update == 'delete_property_permanently') {
			$this->properties_model->delete_property_permanently($property_id,$this->input->get_post('delete_reason'));
			$response['status'] = true;
		} elseif ($property_update == 'update_agency_price_variation') {
			$this->properties_model->update_agency_price_variation($property_id,$this->input->get_post('agency_price_variation'));
			$response['status'] = true;
		} elseif ($property_update == 'update_from_other_company') {
			$this->properties_model->update_from_other_company($property_id,$this->input->get_post('sac_id'));
			$this->_insertPropertiesLogs($post,1);
			$response['status'] = true;
		} elseif ($property_update == 'remove_prop_variation') {
			$this->db->where('property_id', $property_id);
			$this->db->delete('property_variation');

			$og_agency_price_variation_name = $this->input->get_post('og_agency_price_variation_name');
			$log_details = "<strong>Agency Price Variation</strong> Removed <strong>{$og_agency_price_variation_name}</strong>"; 
			$log_params = array(
				'title' => 65, 
				'details' => $log_details,
				'display_in_vpd' => 1,
				'created_by_staff' => $this->session->staff_id,
				'property_id' => $property_id
			);
			$this->system_model->insert_log($log_params);

			$response['status'] = true;
		} elseif ($property_update == 'fetch_date'){
			// Getting the date of the most recent completed YM job for the subscription date
            // this functionality will be deprecated once confirmed to be not required with the automated changes being made
            $data = $this->jobs_model->get_last_completed_ym_info($property_id);
            $data['date'] = date("d-m-Y", strtotime($data['date']));

			if($data){
                $response['status'] = true;
                $response += $data;
			}
		} elseif ($property_update == 'update_service_status') {
			$alarm_job_type_id = $this->input->get_post('alarm_job_type_id');
			$property_services_id = $this->input->get_post('property_services_id');
			$service = $this->input->get_post('service');
			$serv = $this->input->get_post('serv');
			$price = $this->input->get_post('price') ?? '';

			// if changed to service SATS
			if( $service==1 ){
				$price_update_str = " ,`price` = '{$price}' ";
			}

			// get status changed date and is payable   
			$this_month_start = date("Y-m-01");
			$this_month_end = date("Y-m-t");    

			$ps_sql_str = "
			SELECT `status_changed`, `is_payable` 
			FROM `property_services`
			WHERE `alarm_job_type_id` = {$alarm_job_type_id} 
			AND `property_id` = {$property_id} 
			";        
			$ps_sql = $this->db->query($ps_sql_str); 
			$ps_sql_row = $ps_sql->row();
			$status_changed = date('Y-m-d',strtotime($ps_sql_row->status_changed));

			// If property has job where status = precom | merged | completed && date = this month, then keep is_payable = 1
			$has_completed_jobs_this_month = false;
			$job_sql = $this->db->query("
			SELECT COUNT(`id`) AS jcount
			FROM `jobs`
			WHERE `property_id` = {$property_id}
			AND `del_job` = 0
			AND `status` IN ('Pre Completion','Merged Certificates','Completed')
			AND `date` BETWEEN '{$this_month_start}' AND '{$this_month_end}' 				
			");
			$job_row = $job_sql->row();

			if( $job_row->jcount > 0 ){
				$has_completed_jobs_this_month = true;
			}				
	
			// if status changed to DIY, OP(or even NR if thats possible) and is payable, clear is_payable
			$is_payable_update_str = null;
			if( 
				$has_completed_jobs_this_month == false && $ps_sql_row->is_payable == 1 && $service != 1
			){
				$is_payable_update_str = " `is_payable` = 0, ";

				// add log
				// get service type name
				$ps_sql2 = $this->db->query("
				SELECT `type`
				FROM `alarm_job_type`			      
				WHERE `id` = {$alarm_job_type_id}    				
				");
				$ps_sql2_row = $ps_sql2->row();

				$details =  "<strong>Property Service</strong> <b>{$ps_sql2_row->type}</b> unmarked <b>payable</b>";
				$params = array(
					'title' => 3, // Property Service Updated
					'details' => $details,
					'display_in_vpd' => 1,									
					'created_by_staff' => $this->session->staff_id,
					'property_id' => $property_id
				);
				$this->system_model->insert_log($params);
			}

            // existing property service
            $this->db->query("
                UPDATE `property_services`
                SET `alarm_job_type_id` = '{$alarm_job_type_id}',
                    `service` = '{$service}',
                    {$is_payable_update_str}
                    `status_changed` = '".date("Y-m-d H:i:s")."'
                    {$price_update_str}
                WHERE `property_services_id` = {$property_services_id}
                AND `property_id` = '{$property_id}'
            ");

            // add log
            $serv_stat = $this->up_getServiceStatus($serv);
            $new_serv_stat = $this->up_getServiceStatus($service);

            $log_details = "<strong>Property Service</strong> Updated from <b>{$serv_stat}</b> to <b>{$new_serv_stat}</b>";
            $log_params = array(
                'title' => 3,
                'details' => $log_details,
                'display_in_vpd' => 1,
                'created_by_staff' => $this->session->staff_id,
                'property_id' => $property_id
            );
            $this->system_model->insert_log($log_params);

            // A changed service type could affect which job is considered the last completed YM job after a move etc EDGE CASE
            $this->load->model('property_subscription_model');
            $this->property_subscription_model->refresh($property_id);

			$response['status'] = true;
		}

        if(!empty($response)){
            echo json_encode($response);
        }
	}

	public function up_getServiceStatus($serv_status){
		switch($serv_status){
			case 0:
				$serv_stat = 'DIY';
			break;
			case 1:
				$serv_stat = config_item('company_name_short');
			break;
			case 2:
				$serv_stat = 'No Response';
			break;
			case 3:
				$serv_stat = 'Other Provider';
			break;
		}
		return $serv_stat;
	}

	private function _insertPropertiesLogs($post, $tab){
		$edited_field_arr = [];
		if ($tab==1) {
			if( $post['address_1']!=$post['og_address_1'] ){ 
				$edited_field_arr[] = array(
					'field' => "No Address",
					'msg' => "Updated from {$post['og_address_1']} to {$post['address_1']}"
				);
			}
			if( $post['address_2']!=$post['og_address_2'] ){ 
				$edited_field_arr[] = array(
					'field' => "Street Address",
					'msg' => "Updated from {$post['og_address_2']} to {$post['address_2']}"
				);
			}
			if( $post['address_3']!=$post['og_address_3'] ){ 
				$edited_field_arr[] = array(
					'field' => "Suburb Address",
					'msg' => "Updated from {$post['og_address_3']} to {$post['address_3']}"
				);
			}
			if( $post['state']!=$post['og_state'] ){ 
				$edited_field_arr[] = array(
					'field' => "State",
					'msg' => "Updated from {$post['og_state']} to {$post['state']}"
				);
			}
			if( $post['postcode']!=$post['og_postcode'] ){ 
				$edited_field_arr[] = array(
					'field' => "Postcode",
					'msg' => "Updated from {$post['og_postcode']} to {$post['postcode']}"
				);
			}
			if( $post['building_name']!=$post['og_building_name'] ){ 
				$edited_field_arr[] = array(
					'field' => "Building Name",
					'msg' => "Updated from {$post['og_building_name']} to {$post['building_name']}"
				);
			}
			if( $post['comments']!=$post['og_comments'] ){ 
				$edited_field_arr[] = array(
					'field' => "Property Notes",
					'msg' => "Updated from {$post['og_comments']} to {$post['comments']}"
				);
			}
			// if( $post['code']!=$post['og_code'] ){ 
			// 	$edited_field_arr[] = array(
			// 		'field' => "Property Lockbox Code",
			// 		'msg' => "Updated from {$post['og_code']} to {$post['code']}"
			// 	);
			// }
			if( $post['api_prop_id']!=$post['og_api_prop_id'] ){ 
				$edited_field_arr[] = array(
					'field' => "Property API ID",
					'msg' => "Updated from {$post['og_api_prop_id']} to {$post['api_prop_id']}"
				);
			}
			if( $post['key_number']!=$post['og_key_number'] ){ 
				$edited_field_arr[] = array(
					'field' => "Property Key Number",
					'msg' => "Updated from {$post['og_key_number']} to {$post['key_number']}"
				);
			}
			if( $post['alarm_code']!=$post['og_alarm_code'] ){ 
				$edited_field_arr[] = array(
					'field' => "Property Key Number",
					'msg' => "Updated from {$post['og_alarm_code']} to {$post['alarm_code']}"
				);
			}
			if( $post['sac_id']!=$post['og_sac_id'] ){ 
				$edited_field_arr[] = array(
					'field' => "Source of Property",
					'msg' => "Updated from <strong>{$post['og_company_name']}</strong> to <strong>{$post['company_name']}</strong>"
				);
			}
			if( $post['prop_upgraded_to_ic_sa']!=$post['og_prop_upgraded_to_ic_sa'] ){ 
				$og_prop_upgraded = ($post['og_prop_upgraded_to_ic_sa'] == 0) ? 'No':'Yes';
				$prop_upgraded = ($post['prop_upgraded_to_ic_sa'] == 0) ? 'No':'Yes';
				$edited_field_arr[] = array(
					'field' => "Property Upgraded",
					'msg' => "Updated from <strong>{$og_prop_upgraded}</strong> to <strong>{$prop_upgraded}</strong>"
				);
			}
			if( $post['pm_id_new']!=$post['og_pm_id_new'] ){ 
				$edited_field_arr[] = array(
					'field' => "Property Manager",
					'msg' => "Updated from <strong>{$post['og_pm_name']}</strong> to <strong>{$post['pm_name']}</strong>"
				);
			}
			if( $post['agency_price_variation']!=$post['og_agency_price_variation'] ){ 
				$edited_field_arr[] = array(
					'field' => "Agency Price Variation",
					'msg' => "Updated from $<strong>{$post['og_agency_price_variation_name']}</strong> to $<strong>{$post['agency_price_variation_name']}</strong>"
				);
			}
			if( $post['field_name']!= '' ){ 
				$from = ($post['final_value'] == 0)?'checked':'unchecked';
				$to = ($post['final_value'] == 1)?'checked':'unchecked';
				$edited_field_arr[] = array(
					'field' => $post['log_details'],
					'msg' => "Updated from <strong>{$from}</strong> to <strong>$to</strong>"
				);
			}
			if( $post['code']!=$post['og_code'] ){ 
				$edited_field_arr[] = array(
					'field' => "Lockbox Code",
					'msg' => "Updated from <strong>{$post['og_code']}</strong> to <strong>{$post['code']}</strong>"
				);
			}
			if( $post['value']!=$post['og_value'] ){ 
				$from = ($post['og_value'] == 0)?'No':'Yes';
				$to = ($post['value'] == 1)?'Yes':'No';
				$edited_field_arr[] = array(
					'field' => $post['log_details'],
					'msg' => "Updated from <strong>{$from}</strong> to <strong>$to</strong>"
				);
			}
			if( $post['subscription_date']!=$post['og_sub_date'] ){ 
				$edited_field_arr[] = array(
					'field' => "Subscription Start Date",
					'msg' => "Updated from <strong>{$post['og_sub_date']}</strong> to <strong>{$post['subscription_date']}</strong>"
				);
			}
			if( $post['subscription_source']!=$post['og_subscription_source'] ){ 
				$subscription_source_sql = $this->db->query("SELECT `source_name` FROM subscription_source WHERE `id`= {$post['subscription_source']}");
				$row = $subscription_source_sql->row();
				$edited_field_arr[] = array(
					'field' => "Subscription Source",
					'msg' => "Updated from <strong>{$post['og_subscription_source']}</strong> to <strong>{$row->source_name}</strong>"
				);
			}
		}

		##insert logs
        if( !empty($edited_field_arr) ){
            foreach($edited_field_arr as $log_row){
                $title = ($log_row['log_title'] && $log_row['log_title']!="") ? $log_row['log_title'] : 65; // default log title 65 > Property Update
                $log_details = "<strong>{$log_row['field']}</strong> {$log_row['msg']}";
                $log_params = array(
                    'title' => $title,
                    'details' => $log_details,
                    'display_in_vpd' => 1,
                    'created_by_staff' => $this->session->staff_id,
                    'property_id' => $post['property_id']
                );
                $this->system_model->insert_log($log_params);
            }
        }
	}

	public function change_agency_static() 
	{
		
        $data['title'] = "Change Agency";
        $country_id = $this->config->item('country');
        $uri = '/properties/change_agency_static';
        $data['uri'] = $uri;

		$property_id = $this->input->get_post('id');

		$agency_result = $this->db->select('agency_id, landlord_firstname, landlord_lastname, landlord_mob, landlord_email')->from('property')->where('property_id', $property_id)->get()->row();
		$agency_id = $agency_result->agency_id;
		$landlord_firstname = $agency_result->landlord_firstname;
		$landlord_lastname = $agency_result->landlord_lastname;
		$landlord_mob = $agency_result->landlord_mob;
		$landlord_email = $agency_result->landlord_email;

		/*
		if ($landlord_firstname == '' || $landlord_lastname == '' || $landlord_mob == '' || $landlord_email == '') {
			$data['check_landlord'] = 0;
		}
		*/

		if( $landlord_firstname != '' && $landlord_lastname != '' && $landlord_mob != '' && $landlord_email != '' ){
			$data['landlord_has_req_details'] = 1;
		}

		$data['agency_list'] = $this->db->select('`agency_id`, `agency_name`, `address_3`, `franchise_groups_id`')
								->from('agency')
								->where('status', 'active')
								->where('deleted', 0)
								->where('country_id', $country_id)
								->order_by('agency_name', 'ASC')
								->get()->result();
				
		$data['previous_agency_id'] = $agency_id;

		$this->load->view('templates/inner_header', $data);
        $this->load->view($uri, $data);
        $this->load->view('templates/inner_footer', $data);
	}

}
