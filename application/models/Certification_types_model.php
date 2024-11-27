<?php

class Certification_types_model extends MY_Model
{
    public $table = 'certification_types'; 
    public $primary_key = 'id'; 
	
    public function __construct() {
		parent::__construct();
	}
}
