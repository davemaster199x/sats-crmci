<?php

/**
 * SATS Custom Controller Class
 *
 * We can use this class to set debugging for all controllers when not on production
 * Though we want to ensure for security its not shown to general public
 *
 * @property Output $output
 * @property DB $db
 * @property System_model $system_model
 * @property Session $session
 */
class MY_Controller extends CI_Controller {
	public function __construct() {
        parent::__construct();

        // If its NOT production AND if its NOT an ajax request then show profiling
        if(
            ENVIRONMENT != 'production' &&
            (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
        ){
            // https://codeigniter.com/userguide3/general/profiling.html?highlight=profiler
            $this->output->enable_profiler(TRUE);
	    }
	}
}






/**
 * @property Api $api Loads api library
 */
class MY_ApiController extends CI_Controller
{
    public $allowedActions = [];

    //MY_APiController
    public function __construct()
    {
        parent::__construct();

        $this->output = &load_class('Output', 'core');
        $this->load->config('jwt');

        $this->load->library('Api');

        $this->load->helper('jwt');
        $this->load->helper('authorization');

        $this->output->set_content_type('application/json');

        $api_log_csv = APPPATH . 'logs/api_log-' . date('Y_m_d_H') . '.csv';

        $request_header = $this->input->request_headers();
        $request_data = array(
            "get"   => $this->input->get() ?? [],
            "post"  => $this->input->post() ?? [],
            "raw"   => file_get_contents('php://input') ?? '',
        );

        $csv_data = array(
            date('c'),
            current_url(),
            $request_header["appversion"],
            $request_header["userid"],
            $this->input->method(),
            json_encode($request_data),
        );
        $csv_file = fopen($api_log_csv, 'a+');
        fputcsv($csv_file, $csv_data);
        fclose($csv_file);
    }

    public function _exists($value, $field)
    {
        if (!$value) {
            return FALSE;
        }
        sscanf($field, '%[^.].%[^.]', $table, $field);

        return isset($this->db)
            ? ($this->db->limit(1)->get_where($table, array($field => $value))->num_rows() > 0)
            : FALSE;
    }
}
