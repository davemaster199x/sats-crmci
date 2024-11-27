<?php

class Job_sync_log_model extends MY_Model
{
	public $table = 'job_sync_log'; // you MUST mention the table name
	public $primary_key = 'id'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'id'
	];

}