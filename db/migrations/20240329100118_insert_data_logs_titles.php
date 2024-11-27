<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertDataLogsTitles extends AbstractMigration
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
        $table = $this->table('log_titles');
        $table->insert([['log_title_id'=>114,'title_name' => 'Job Certification', 'active' => '1']])->save();
    }
}
