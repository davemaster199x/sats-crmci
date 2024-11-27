<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlarmJobTypeUpdates extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {

        $table = $this->table('alarm_job_type');

        // inserting multiple rows
        $rows = [
            [
                'id' => 21,
                'type' => 'Smoke Alarm & Safety Switch (View Only)',
                'html_id' => 'smoke-alarm-safety-switch-view',
                'bundle' => 1,
                'bundle_ids' => '2,3',
                'short_name' => 'SASSv',
                'full_name' => 'Smoke Alarm & Safety Switch (View Only)',
                'excluded_bundle_ids' => '2,3,4,5,6,8,9,11,12,13,14,15,16,17,18,19,20,22,23,24,25,26'
            ],
            [
                'id' => 22,
                'type' => 'Bundle SA.CW.SSv',
                'html_id' => 'smoke-switch-view-windows-bundle',
                'bundle' => 1,
                'bundle_ids' => '2,3,6',
                'short_name' => 'SASSvCW',
                'full_name' => 'Smoke Alarm, Corded Windows and Safety Switch (View Only) Bundle',
                'excluded_bundle_ids' => '2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,23,24,25,26'
            ],
            [
                'id' => 23,
                'type' => 'Smoke Alarm & Safety Switch (View Only) (IC)',
                'html_id' => 'sa-ssv-ic',
                'bundle' => 1,
                'bundle_ids' => '2,3',
                'short_name' => 'SASSv(IC)',
                'full_name' => 'Smoke Alarm & Safety Switch (View Only) (Interconnected)',
                'excluded_bundle_ids' => '2,3,4,5,8,9,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26',
                'is_ic' => 1
            ],
            [
                'id' => 24,
                'type' => 'Bundle SA.CW.SSv (IC)',
                'html_id' => 'sa-cw-ssv(ic)',
                'bundle' => 1,
                'bundle_ids' => '2,3,6',
                'short_name' => 'SACWSSv(IC)',
                'full_name' => 'Smoke Alarm, Corded Windows and Safety Switch (View Only) Bundle (Interconnected)',
                'excluded_bundle_ids' => '2,3,4,5,8,9,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26',
                'is_ic' => 1
            ],
            [
                'id' => 25,
                'type' => 'Bundle SA.SSv.WE',
                'html_id' => 'sa-ssv-we',
                'bundle' => 1,
                'bundle_ids' => '2,3,15',
                'short_name' => 'SASSvWE',
                'full_name' => 'Smoke Alarm, Safety Switch (View Only) and Water Efficiency',
                'excluded_bundle_ids' => '2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,22,23,24,26'
            ],
            [
                'id' => 26,
                'type' => 'Bundle SA.SSv.CW.WE',
                'html_id' => 'sa-ssv-cw-we',
                'bundle' => 1,
                'bundle_ids' => '2,3,6,15',
                'short_name' => 'SASSvCWWE',
                'full_name' => 'Smoke Alarm, Safety Switch (View Only), Corded Window and Water Efficiency',
                'excluded_bundle_ids' => '2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25'
            ]
        ];
        
        $table->insert($rows)->saveData();      
        
        // update short name        
        $this->execute("UPDATE alarm_job_type SET short_name = 'SSv' WHERE id = 3");
        $this->execute("UPDATE alarm_job_type SET short_name = 'SACWSS(IC)' WHERE id = 14");

    }

}
