<?php

class Sms_api_replies_model extends MY_Model {
	public $table = 'sms_api_replies'; // you MUST mention the table name
	public $primary_key = 'sms_api_replies_id'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'id'
	];
}