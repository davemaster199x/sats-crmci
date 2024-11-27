<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Aidid\BladeView\BladeView;

class Logs extends MY_Controller
{

	protected $data = [];

	public $bladeview;

    public function __construct() {
        parent::__construct();
	    $this->bladeview = new BladeView();

	    $this->load->library('logviewer');
		//$this->logviewer = new Logviewer();


	    // breadcrumbs template
	    $this->data['bc_items'][] = [
		    'title' => 'Logviewer',
		    'status' => 'active',
		    'link' => "/logviewer/index"
	    ];
    }

    public function index() {
		$html = $this->logviewer->showLogs();

	    $this->bladeview->render('_blade/logviewer/index', ['html' => $html]);
    }
}