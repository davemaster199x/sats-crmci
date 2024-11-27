<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {
	
	public function __construct(){
		parent::__construct(); 
		$this->load->model('property_tree_model');
		//$this->load->library('pagination');
  	}  

	public function index(){

		//echo config_item('crm_link');
		echo $this->config->item('agency_link');
		
	}

	public function add_users_permission(){

		$users_arr = []; // clear

		if( config_item('theme') == 'sats' ){ // SATS

			if( config_item('country') == 1 ){ // AU

				// user ID
				/*
				2070 - Developer testing
				2025 - Daniel
				2287 - Ben Taylor
				11 - Ness
				2296 - Shaquille Smith
				2175 - Thalia
				2294 - Amber
				2495 - peter
				*/
				$users_arr = [2070, 2025, 2287, 11, 2296, 2175, 2294, 2495];
	
			}else if( config_item('country') == 2 ){ // NZ
	
				// user ID
				/*
				2070 - Developer testing
				2025 - Daniel
				2231 - Ben Taylor
				11 - Ness
				2259 - Shaquille Smith
				2193 - Thalia
				2233 - Amber
				2325 - peter
				*/
				$users_arr = [2070, 2025, 2231, 11, 2259, 2193, 2233, 2325];
	
			}			

		}else if( config_item('theme') == 'sas' ){ // SAS

			// user ID
			/*
			12 - Dev User 
			34 - Daniel
			31 - Thalia
			13 - Sam
			4 - global user
			29 - peter
			*/
			$users_arr = [12, 34, 31, 13, 4, 29];

		}	
		
		// permissions
		$perm_arr = [7];

		if( count($users_arr) > 0 ){

			foreach( $users_arr as $user_id ){ // loop per user

				foreach( $perm_arr as $permission ){ // loop per permission
	
					$insert_data = array(
						'staff_id' => $user_id,
						'has_permission_on' => $permission
					);
					$this->db->insert('staff_permissions', $insert_data);
	
				}								
				
			}

		}		

	}
		
	public function php_info(){
		phpinfo();
	}
	
	public function session(){
		print_r($_SESSION);
	}
	
	public function get_document_root(){
		echo $_SERVER["DOCUMENT_ROOT"].'/session';
	}	

	public function testonlycommit(){
		echo "test committ";
	}

}
