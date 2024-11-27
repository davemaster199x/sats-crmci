<?php

class States_def_model extends MY_Model
{
	public $table = 'states_def'; // you MUST mention the table name
	public $primary_key = 'StateID'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'StateID'
	];
	// or $this->has_many['posts'] = array('Posts_model','foreign_key','another_local_key');


}