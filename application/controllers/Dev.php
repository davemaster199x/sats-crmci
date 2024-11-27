<?php

use \Aidid\BladeView\BladeView;

class Dev extends MY_Controller {

	protected $data = [];

	public $bladeview;
	public function __construct() {
		parent::__construct();
		$this->bladeview = new BladeView();


		// breadcrumbs template
		$this->data['bc_items'][] = [
			'title' => 'Dev',
			'status' => 'active',
			'link' => "/dev/index"
		];
	}

	public function page_speed() {
		$this->bladeview->render('_blade/dev/page-speed', $this->data);
	}

	public function session_logs() {
		$this->bladeview->render('_blade/dev/session-logs', $this->data);
	}



	public function ajax_page_speed()
	{
		// CodeIgniter 3 Example

// Here we will select all fields from posts table
// and make a join with categories table
// Please note: we don't need to call ->get() here
		$queryBuilder = $this->db
			->select('url,
				       count(id) as count,
				       avg(seconds) as avg_seconds')
			->from('session_logs')
			->group_by('url')
			->order_by('avg_seconds', 'desc');

		/**
		 * The first parameter is the query builder instance and the second is the codeigniter version (3 or 4)
		 */
		$datatables = new Ngekoding\CodeIgniterDataTables\DataTables($queryBuilder, '3');
		$datatables->generate();
	}


	public function ajax_session_logs()
	{
		$queryBuilder = $this->db
			->select("
				session_id,
				user_id,
				COUNT(id) AS '# of Requests'
			")
			->from("sessions")
			->group_by('url')
			->having('seconds NOT NULL')
			->order_by('milliseconds', 'desc');

		/**
		 * The first parameter is the query builder instance and the second is the codeigniter version (3 or 4)
		 */
		$datatables = new Ngekoding\CodeIgniterDataTables\DataTables($queryBuilder, '3');
		$datatables->generate();
	}
}

