<?php

class Job_platform_invoice_note_model extends MY_Model
{
	public $table = 'job_platform_invoice_note'; // you MUST mention the table name
	public $primary_key = 'id'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'id'
	];
	
    public function __construct()
	{
		parent::__construct();
	}
}