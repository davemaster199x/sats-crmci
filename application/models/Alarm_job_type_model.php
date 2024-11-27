<?php

/**
 * This is really our services model that we need to rename at some point
 */
class Alarm_job_type_model extends MY_Model
{
	public $table = 'alarm_job_type'; // you MUST mention the table name
	public $primary_key = 'id'; // you MUST mention the primary key


	// ...Or you can set an array with the fields that cannot be filled by insert/update
	public $protected = [
		'id'
	];

	function __construct()
	{
		$this->has_many['job'] = array(
			'foreign_model'=>'Jobs_model',
			'foreign_table'=>'jobs',
			'foreign_key'=>'job_id',
			'local_key'=>'id'
		);


		parent::__construct();
	}

	const SMOKE_ALARM_IDS = [2,12,32,50];

	const SAFETY_SWITCH_VIEW_ONLY_IDS = [3];

	const SAFETY_SWITCH_IDS = [5];
	const CORDED_WINDOWS_IDS = [6];
	const WATER_METER_IDS = [7];
	const WATER_EFFICIENCY_IDS = [15];

	/**
	 * Our services are smoke alarm, safety switch etc
	 * Though this table also has groups of these services which is not best practice so these are hacks
	 *
	 * This function is used to get a service's child bundled id because we do not have separate tables for these things ;/
	 * The alarm table saves the child id of the alarm
	 * @param array $ajt_id
	 * @return false|int
	 */
	public static function get_bundled_smoke_alarm_service_id($ajt_id = 0)
	{
		if(empty($ajt_id)){
			log_message('error', 'AJT - get_bundled_smoke_alarm_id() passed invalid parameter: ' . print_r($ajt_id, true) . PHP_EOL . print_r(debug_backtrace()[0], true) . PHP_EOL . print_r(debug_backtrace()[1], true) . PHP_EOL . print_r(debug_backtrace()[2], true));
			return false;
		}

		// if it is an integer its prob a single id so check for its bundle ids
		if(!is_array($ajt_id)){
			// get the ajt bundle_id list
			$self = new self();
			$ajt = $self->get($ajt_id);
			$ajt_bundle_ids = explode(',', $ajt->bundle_ids);
		}

		// if bundle_ids is blank, then use the original ajt_id
		if(empty($ajt_bundle_ids)){
			$ajt_bundle_ids = [$ajt_id];
		}

		// Now we match against list of smoke alarm ids
		$smoke_alarm_ajt_id = self::show_smoke_alarms($ajt_bundle_ids);

		// If we get no match, log error
		if(empty($smoke_alarm_ajt_id)){
			log_message('error', 'AJT - array_intersect() returned no matches for: ' . $ajt_id . ' > ' . print_r($smoke_alarm_ajt_id,true) . PHP_EOL . print_r(debug_backtrace()[1], true) . PHP_EOL . print_r(debug_backtrace()[2], true));
			return false;
		}

		return (int) reset($smoke_alarm_ajt_id);
	}

	public static function icons($ajt_id)
	{
		$data = [];
		$cache_name = 'service-icons';
		$cache_ttl = 604800;

		$self = new self();
		if ( !$data = $self->cache->get($cache_name) ) {
			$services = $self->get_all();
			foreach($services as $service){
				$icons = explode('-',$service->html_id);
				$html = '<span class="service-icons" title="' . $service->full_name . '">';
				foreach($icons as $icon){
					$html .= '<img src="/images/icons-services/' . $icon . '.png" alt="' . $icon . '" />';
				}
				$html .= '</span>';
				$data[$service->id] = $html;
			}
			$self->cache->save($cache_name, $data, $cache_ttl);
		}
		return $data[$ajt_id];
	}

	public static function show_smoke_alarms($bundle_ids = [])
	{
		// if its not already an array, covert it into one
		// bundle ids will come through as array or comma list
		// string 1,2,3 converts into an array [1,2,3]
		// string 1 or int 1 will also explode into an array [1]
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return array_intersect(Alarm_job_type_model::SMOKE_ALARM_IDS, $bundle_ids);
	}
	public static function show_safety_switches($bundle_ids = [])
	{
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return array_intersect(Alarm_job_type_model::SAFETY_SWITCH_IDS, $bundle_ids);
	}
	public static function show_safety_switches_view_only($bundle_ids = [])
	{
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return array_intersect(Alarm_job_type_model::SAFETY_SWITCH_VIEW_ONLY_IDS, $bundle_ids);
	}
	public static function show_safety_switches_both($bundle_ids = [])
	{
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return (self::show_safety_switches($bundle_ids) || self::show_safety_switches_view_only($bundle_ids));
	}
	public static function show_corded_windows($bundle_ids = [])
	{
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return array_intersect(Alarm_job_type_model::CORDED_WINDOWS_IDS, $bundle_ids);
	}
	public static function show_water_meters($bundle_ids = [])
	{
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return array_intersect(Alarm_job_type_model::WATER_METER_IDS, $bundle_ids);
	}
	public static function show_water_efficiency($bundle_ids = [])
	{
		if(!is_array($bundle_ids)){
			$bundle_ids = explode(',', $bundle_ids);
		}
		return array_intersect(Alarm_job_type_model::WATER_EFFICIENCY_IDS, $bundle_ids);
	}
}