<?php

class Bundle_services_model extends MY_Model
{

	public $table = 'bundle_services'; // you MUST mention the table name
	public $primary_key = 'bundle_services_id'; // you MUST mention the primary key

	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'bundle_services_id'
	];


}
