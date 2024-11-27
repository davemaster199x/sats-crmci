<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property_subscription_model extends MY_Model
{
    public $table = 'property_subscription'; // you MUST mention the table name
    public $primary_key = 'id'; // you MUST mention the primary key

    // If you want, you can set an array with the fields that can be filled by insert/update
    public $fillable = [
        'property_id',
        'subscription_date',
        'source',
        'date_updated',
    ];

    protected $debug_log = true;

    // ...Or you can set an array with the fields that cannot be filled by insert/update
//    public $protected = [
//        'id'
//    ];



    /**
     * With our yearly maintenance plans, we need to visit the property within 1 year of the last inspection
     * So each property needs to maintain when it was last visited
     * @param int $property_id
     * @return void
     */
    public function refresh(int $property_id = 0)
    {
        if(empty($property_id)){
            log_message('error', 'update_property_subscription property_id cannot be 0');
            return;
        }

        $CI =& get_instance();

        $date_updated = date('Y-m-d');

        // Get the date of the last completed YM job for the property
        $last_completed_ym_job = $CI->jobs_model->get_last_completed_ym_info($property_id);

        // Get the property_subscription record or create it if it doesnt exist
        $property_subscription = $this->as_array()->get(['property_id' => $property_id]);
        if(!$property_subscription){
            // Set Source - Either existing source value or if techid 1 and 2
            // Check if assigned tech is 1 or 2 and if so, set the source
            $sats_internal_assigned_techs = [1,2];
            $source = 0;
            if(!empty($last_completed_ym_job['assigned_tech']) && in_array($last_completed_ym_job['assigned_tech'], $sats_internal_assigned_techs)){
                $source = 8;
            }

            $property_subscription = [
                'property_id'       => $property_id,
                'subscription_date' => $last_completed_ym_job['date'] ?? NULL,
                'source'            => $source,
                'date_updated'      => $date_updated,
            ];

            $this->insert($property_subscription);
        } else if(strtotime($property_subscription['subscription_date']) != strtotime($last_completed_ym_job['date'])){
            // The last completed ym job date is different now to what is stored, this should be expected if this job is it
            $data = [
                'subscription_date' => $last_completed_ym_job['date'] ?? NULL,
                'date_updated'      => $date_updated,
            ];

            $property_subscription['subscription_date'] = $last_completed_ym_job['date'];
            $property_subscription['date_updated'] = $date_updated;

            $this->update($data, $property_subscription['id']);
        }
    }

	public function refresh_batch($property_ids = [])
	{

		// We only want to check for properties that have been updated
		$valid_ids = [];
		foreach($property_ids as $property_id){
			if($property_id > 0){
				$valid_ids[] = $property_id;
			}
		}

		// log any invalid ids
		$invalid_ids = array_diff($property_ids, $valid_ids);
		if(!empty($invalid_ids)){
			log_message('error', 'Invalid Ids: ' . join(', ', $invalid_ids));
		}

		// return here if we have no valid ids
		if(empty($valid_ids)){
			return;
		}




		$sql = "
SELECT
    jobs.property_id,
    jobs.id as 'job_id',
    MAX(`date`) as last_visit
FROM jobs
LEFT JOIN property_services ON jobs.service = property_services.alarm_job_type_id
                            AND jobs.property_id = property_services.property_id
WHERE jobs.property_id IN (" . join(',', $valid_ids) . ")
  AND job_type = 'Yearly Maintenance'
  AND status = 'Completed'
  AND del_job = 0
  AND property_services.service = 1
GROUP BY jobs.property_id
ORDER BY jobs.property_id;
";
		$last_visits = [];
		$last_visit_ids = [];
		$last_visits_results = $this->db->query($sql)->result_array();
		if(!empty($last_visits_results)){
			foreach($last_visits_results as $row){
				$last_visits[$row['property_id']] = $row['last_visit'];
				$last_visit_ids[$row['property_id']] = $row['job_id'];
			}
		}


		$property_subscriptions = $this->property_subscription_model
			->use_column_as_key('property_id')
			->where('property_id', $valid_ids)
			->get_all();

		// Go through all active properties
		//$sql = "SELECT property_id FROM property WHERE deleted=0 AND is_nlm=0";
		//$active_properties = $this->db->query($sql)->result_array();
		/*
		$params = [
			'sel_query' => 'p.property_id',
			'a_status' => 'active',
			'a_deleted' => 0,
			'ps_service' => 1,
			'custom_where' => 'a.franchise_groups_id != 14',
			'join_table' => array('property_services'),
			'sort_list' => [
				[
					'order_by'=> 'property_id',
					'sort'=> 'DESC',
				]
			],
			//'display_query' => 1,
		];
		$active_properties = $this->properties_model->get_properties($params)->result_array();
		*/


		$date_updated = date('Y-m-d H:i:s');
		$data = [];
		$totals = [
			'new' => 0,
			'updated' => 0,
			'unchanged' => 0,
		];
		$ids = [
			'new' => [],
			'updated' => [],
			'unchanged' => [],
		];


		foreach ($valid_ids as $property_id) {
			if(!empty($last_visits[$property_id])){
				$subscription_date = $last_visits[$property_id];
			} else {
				$subscription_date = NULL;
			}

			if(!empty($property_subscriptions[$property_id])){
				if($property_subscriptions[$property_id]['subscription_date'] == $subscription_date){
					$totals['unchanged']++;
					$ids['unchanged'][] = $property_id  . ' | ' . $last_visit_ids[$property_id];;
				} else {
					$totals['updated']++;
					$ids['updated'][] = $property_id . ' | ' . $property_subscriptions[$property_id]['subscription_date'] . ' > ' . ($subscription_date ?? '*REMOVED*')  . ' | ' . $last_visit_ids[$property_id];;
				}

				$update[] = [
					'id'                => $property_subscriptions[$property_id]['id'],
					'subscription_date' => $subscription_date,
					'date_updated'      => $date_updated,
				];
			} else {
				$totals['new']++;
				$ids['new'][] = $property_id . ' | ' . $subscription_date . ' | ' . print_r($last_visit_ids[$property_id], true);


				$insert[] = [
					'property_id'       => $property_id,
					'subscription_date' => $subscription_date,
					'source'            => 0,
					'date_updated'      => $date_updated,
				];
			}
		}

		if(!empty($update)){
			$this->db->update_batch('property_subscription', $update, 'id');
		}

		if(!empty($insert)){
			$this->db->insert_batch('property_subscription', $insert);
		}


		define('BR', "\n");

		log_message(
			'debug',

			"Summary===============" . BR .
			'Property IDs: ' . count($property_ids) . BR .
			'Valid IDs: ' . count($valid_ids) . BR .
			'Last Visits: ' . count($last_visits) . BR .
			'Subscriptions Records Before: ' . count($property_subscriptions) . BR .
			'Subscriptions Records After: ' . count($data) . BR .
			'=============================' . BR .
			'New: ' . $totals['new'] . BR .
			'Updated: ' . $totals['updated'] . BR .
			'Unchanged: ' . $totals['unchanged'] . BR .
			'TOTAL: ' . array_sum($totals) . BR . BR .
			'=============================' . BR .
			'IDS' . BR .
			'New: ' . BR  . join(BR, $ids['new']) . BR . BR .
			'Updated: ' . BR  . join(BR, $ids['updated']) . BR . BR .
			'Unchanged: ' . BR  . join(BR, $ids['unchanged'])
		);
	}
}