<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddNewAgencyVariationReason extends AbstractMigration
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

        $table = $this->table('agency_price_variation_reason');

        // inserting only one row
        $singleRow = [
            'id' => 9,
            'reason' => 'Console Fee',
            'is_discount' => 0
        ];

        $table->insert($singleRow)->saveData();

    }
}
