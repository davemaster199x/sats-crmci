<?php

class Water_meter_model extends MY_Model
{

    public $table = 'water_meter'; // you MUST mention the table name
	public $primary_key = 'water_meter_id'; // you MUST mention the primary key

    public static function upload_path($file = '')
    {
        switch(config_item('country_code')){
            case 2:
                $country_code = 'nz';
                break;
            default:
                $country_code = 'au';
                break;
        }
        return 'uploads/water_meter/' . $file;
    }

    public static function image($file = '')
    {
        return '/' . trim($file, '/');
    }

}