<?php

use \Aidid\BladeView\BladeView;

class Benchmark extends MY_Controller {

	public $bladeview;
	public function __construct() {
		parent::__construct();
		$this->bladeview = new BladeView();
	}

    public function index() {


	    $this->bladeview->render('_blade/session-logs/index');
    }


	public function ajax()
	{
		// CodeIgniter 3 Example

// Here we will select all fields from posts table
// and make a join with categories table
// Please note: we don't need to call ->get() here
		$queryBuilder = $this->db
			->select('url,
					       count(*) as count,
					       avg(post_system - pre_system) / 1000 as milliseconds,
					       seconds
					       ')
			->from('sessions')
			->group_by('url')
			->order_by('milliseconds', 'desc');

		/**
		 * The first parameter is the query builder instance
		 * and the second is the codeigniter version (3 or 4)
		 */
		$datatables = new Ngekoding\CodeIgniterDataTables\DataTables($queryBuilder, '3');
		$datatables->generate(); // done
	}

}

