<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertCoreValuesHomepageTileData extends AbstractMigration
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
        // insert data to home_content_block table
        $homeContenBlockRows = [
            [
                'content_block_id'  =>  60,
                'content_name'      =>  'Core Values',
                'category'          =>  2
            ]
        ];

        $this->table('home_content_block')
            ->insert($homeContenBlockRows)
            ->saveData();
        // insert data to home_content_block table end

    }
}
