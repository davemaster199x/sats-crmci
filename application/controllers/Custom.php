<?php

class Custom extends MY_Controller
{
	/**
	 * @var array breadcrumbs moved out of view to here as its global
	 */
	private $breadcrumbs = 	[
		[
			'title' => 'Custom',
			'link' => "/custom/duplicate_properties"
		],
	];
	private $cacheTTL = 600;
	private $cacheIdDuplicatePropertiesResults = 'DuplicatePropertiesResults';

	private $sql;
	public function __construct()
	{
		parent::__construct();
		$this->load->driver('cache',  ['adapter' => 'file']);
	}

    public function index()
	{

    }

	/**
	 * Returns data for email template tags from a file cache if it exists, if not then from the database
	 * @return array
	 */
	private function get_duplicate_properties_data()
	{
		$this->sql = "SELECT
    p1.property_id as 'Property ID',
    p1.address_1 as 'Number',
    p1.address_2 as 'Street',
    p1.address_3 as 'Suburb',
    p1.state as 'State',
    p1.postcode as 'Postcode',
    IF(p1.deleted=1,'Y','') as 'Deleted',
    IF(p1.agency_deleted=1,'Y','') as 'Agency Deleted',
    p1.agency_id as 'Agency ID',
    agency_name as 'Agency Name'
FROM property p1
LEFT JOIN agency ON p1.agency_id = agency.agency_id
WHERE (
       TRIM(p1.address_1),
       TRIM(p1.address_2),
       TRIM(p1.address_3)

) IN (
    SELECT
        TRIM(p2.address_1) AS address_1,
        TRIM(p2.address_2) AS address_2,
        TRIM(p2.address_3) AS address_3

    FROM property p2
    WHERE p2.deleted != 1
    GROUP BY
        TRIM(p2.address_1),
        TRIM(p2.address_2),
        TRIM(p2.address_3),
        TRIM(p2.state),
        TRIM(p2.postcode)
    HAVING COUNT(*) > 1
)
ORDER BY p1.address_2 ASC, p1.address_1 ASC
";

		// Check cache
		$cached_data = $this->cache->get($this->cacheIdDuplicatePropertiesResults);

		// If no cache file found, run the query and save to cache
		if (empty($cached_data)){
			$query = $this->db->query($this->sql);
			//var_dump($result);
//		foreach($data)
			$count = 1;
			$cached_data = [
				'cols' => [],
				'data' => [],
			];
			foreach ($query->result_array() as $row){
				if($count == 1){
					foreach($row as $key=>$value) {
						$cached_data['cols'][] = $key;
					}
					$count=false;
				}
				$cached_data['data'][] = $row;

			}


			$this->cache->save($this->cacheIdDuplicatePropertiesResults, $cached_data, $this->cacheTTL);
		}

		return $cached_data;
	}

	public function duplicate_properties()
	{

		$data = $this->get_duplicate_properties_data();

		$data['crm_link'] = $this->config->item('crm_link');

		$this->breadcrumbs[] = [
			'title' => 'Duplicate Properties',
			'status' => 'active',
		];

		$data['bc_items'] = $this->breadcrumbs;

		$data['sql'] = $this->sql;

		$data['ordering'] = [
			'Street' => 'sorting_1',
			'Number' => 'sorting_2',
		];

		$this->load->view('custom/duplicate_properties', $data);
	}


}