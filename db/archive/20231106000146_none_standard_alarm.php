<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class NoneStandardAlarm extends AbstractMigration
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
        //create none_standard_alarm table
        $table = $this->table('none_standard_alarm');
        $table->addColumn('alarm_id', 'integer')
            ->addColumn('none_standard_type', 'integer', ['default' => 0])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true, 'default' => null])
            ->create();

    }

    public function down()
    {
        $this->table('none_standard_alarm')->drop()->save();
    }
}
