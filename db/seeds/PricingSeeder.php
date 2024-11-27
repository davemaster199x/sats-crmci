<?php


use Phinx\Seed\AbstractSeed;

class PricingSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
	public function run(): void
	{
		$skipped = [
			'__construct',
			'run',
		];

		$methods = array_diff(get_class_methods($this), get_class_methods(get_parent_class($this)));

		foreach ($methods as $method) {
			if (!in_array($method, $skipped)) {
				// reset indexes before insert
				$this->query("TRUNCATE TABLE " . $method);
				$sql = $this->$method();
				if(!empty($sql)){
					$this->query($sql);
				}
			}
		}
	}

    private function alarm_job_type(){
        return "insert into alarm_job_type (id, type, html_id, include_file, bundle, bundle_ids, short_name, sync_marker, active, full_name, excluded_bundle_ids, is_ic)
values  (1, 'Test & Tag Appliances', 'test-tag', 'tab_appliances.php', 0, '', null, null, 0, null, '', 0),
        (2, 'Smoke Alarms', 'smoke-co2', 'tab_alarms.php', 0, '', 'SA', 'alarms_synced', 1, 'Smoke Alarms', '8,9,11,12,13,14,15,16,17,18', 0),
        (3, 'Safety Switch - View', 'ss-view', 'tab_ss_view.php', 0, '', null, null, 0, null, '4,5,8,9,13,14,15,16,17,18', 0),
        (4, 'Safety Switch - Mechanical Test', 'ss-mech-test', 'tab_ss_mech.php', 0, '', null, null, 0, null, '3,5,8,9,13,14,15,16,17,18', 0),
        (5, 'Safety Switch', 'ss-full-test', 'tab_safety_switch.php', 0, '', 'SS', 'ss_sync', 0, 'Safety Switch', '3,4,8,9,13,14,15,16,17,18', 0),
        (6, 'Corded Windows', 'corded-window-compliance', 'tab_corded_window.php', 0, '', 'CW', 'cw_sync', 1, 'Corded Windows', '8,9,11,13,14,15,16,17,18', 0),
        (7, 'Water Meter', 'water-meter', 'tab_water_meter.php', 0, '', 'WM', 'wm_sync', 0, 'Water Meter', '11', 0),
        (8, 'Smoke Alarm & Safety Switch', 'smoke-alarm-safety-switch', null, 1, '2,5', 'SASS', null, 1, 'Smoke Alarm & Safety Switch', '2,3,4,5,6,9,11,12,13,14,15,16,17,18', 0),
        (9, 'Bundle SA.CW.SS	', 'smoke-switch-windows-bundle', null, 1, '2,5,6', 'SASSCW', null, 1, 'Smoke Alarm, Corded Windows and Safety Switch Bundle', '2,3,4,5,6,7,8,11,12,13,14,15,16,17,18', 0),
        (11, 'Smoke Alarm & Water Meter', 'smoke-alarm-water-meter', null, 1, '2,7', 'SAWM', null, 1, 'Smoke Alarm & Water Meter', '2,6,7,8,9,12,13,14,15,16,17,18', 0),
        (12, 'Smoke Alarms (IC)', 'sa-ic', 'tab_alarms.php', 0, '', 'SA(IC)', 'alarms_synced', 1, 'Smoke Alarms (Interconnected)', '2,8,9,11,13,14,15,16,17,18', 1),
        (13, 'Smoke Alarm & Safety Switch (IC)', 'sa-ss-ic', null, 1, '2,5', 'SASS(IC)', null, 1, 'Smoke Alarm & Safety Switch (Interconnected)', '2,3,4,5,8,9,11,12,14,15,16,17,18', 1),
        (14, 'Bundle SA.CW.SS (IC)', 'sa-cw-ss(ic)', null, 1, '2,5,6', 'sacwss(IC)', null, 1, 'Smoke Alarm, Corded Windows and Safety Switch Bundle (Interconnected)', '2,3,4,5,6,8,9,11,12,13,25,16,17,18', 1),
        (15, 'Water Efficiency', 'water-efficiency', 'tab_water_efficiency.php', 0, '', 'WE', 'we_sync', 1, 'Water Efficiency', '2,3,4,5,6,7,8,9,11,12,13,14,16,17,18', 0),
        (16, 'Smoke Alarms & Water Efficiency', 'sa-we', null, 1, '2,15', 'SAWE', null, 1, 'Smoke Alarm & Water Efficiency', '2,3,4,5,6,7,8,9,11,12,13,14,15,17,18', 0),
        (17, 'Bundle SA.SS.WE', 'sa-ss-we', null, 1, '2,5,15', 'SASSWE', null, 1, 'Smoke Alarm, Safety Switch and Water Efficiency', '2,3,4,5,6,7,8,9,11,12,13,14,15,16,18', 0),
        (18, 'Bundle SA.SS.CW.WE', 'sa-ss-cw-we', null, 1, '2,5,6,15', 'SASSCWWE', null, 1, 'Smoke Alarm, Safety Switch, Corded Window and Water Efficiency', '2,3,4,5,6,7,8,9,11,12,13,14,15,16,17', 0),
        (19, 'Smoke Alarms & Corded Windows', 'sa-cw', '', 1, '2,6', 'SACW', null, 1, 'Smoke Alarms & Corded Windows', '2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18', 0),
        (20, 'Smoke Alarms & Corded Windows (IC)', 'sa-cw-ic', null, 1, '2,6', 'SACW(IC)', null, 1, 'Smoke Alarms & Corded Windows (Interconnected)', '2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18', 1);";

    }

    private function agency_default_service_price()
    {
        return "insert into agency_default_service_price (id, service_type, price, active)
values  (1, 2, 119.00, 1),
        (2, 8, 129.00, 1),
        (3, 16, 139.00, 1),
        (4, 17, 149.00, 1),
        (5, 9, 179.00, 1),
        (6, 18, 199.00, 1),
        (7, 12, 129.00, 1),
        (8, 13, 139.00, 1),
        (9, 14, 199.00, 1),
        (10, 6, 75.00, 1),
        (11, 19, 126.50, 1),
        (12, 20, 126.50, 1)";
    }
}
