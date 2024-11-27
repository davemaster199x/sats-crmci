<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddEscalatedJobsDataToContactType extends AbstractMigration
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
        $table = $this->table('log_title_usable_pages');

        //insert only one row 'Escalated Job' into 'log_title_usable_pages' table
        $singleRow = [
            'id'            => 12,
            'log_titles_id' => 15,
            'show_in'       => 1
        ];

        $table->insert($singleRow)->saveData();
    }
}
