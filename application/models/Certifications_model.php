<?php

class Certifications_model extends MY_Model
{
    public $table = 'certifications'; 
    public $primary_key = 'id'; 
	
    public function __construct() {
		parent::__construct();
	}
}
