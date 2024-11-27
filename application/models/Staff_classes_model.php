<?php

class Staff_classes_model extends MY_Model
{
	public $table = 'staff_classes'; // you MUST mention the table name
	public $primary_key = 'ClassID'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'ClassID'
	];
    // or $this->has_many['posts'] = array('Posts_model','foreign_key','another_local_key');



    public function __construct() {
        $this->has_many['staff_accounts'] = ['Staff_accounts_model','StaffID','StaffID'];

        parent::__construct();
    }
}

