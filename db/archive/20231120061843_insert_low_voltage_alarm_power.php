<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertLowVoltageAlarmPower extends AbstractMigration
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
    public function change(): void
    {
       
        $this->table('alarm_type')
            ->insert(
                [
                    'alarm_type' => 'Fire Panel', 
                    'alarm_job_type_id' => 2,
                ]
            )
            ->save();
        $alarmTypeId = $this->adapter->getConnection()->lastInsertId();
      
        $data = [
            'alarm_pwr'         => 'Low Voltage',
            'alarm_price_ex'    => '0.00',
            'alarm_price_inc'   => '0.00',
            'alarm_job_type_id' => 2,
            'alarm_make'        => null,
            'alarm_model'       => null,
            'alarm_expiry'      => null,
            'alarm_type'        => $alarmTypeId ?? 2,
            'active'            => 1,
            'is_240v'           => 0,
            'battery_type'      => null,
            'is_replaceable'      => null,
            'alarm_pwr_source' => 'LV',
            'is_li' => 0,
        ];

        $this->table('alarm_pwr')
            ->insert($data)
            ->save();
    }
}
