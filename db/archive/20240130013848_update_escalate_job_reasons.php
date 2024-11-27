<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateEscalateJobReasons extends AbstractMigration
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
        $this->execute("UPDATE escalate_job_reasons SET reason = 'Please contact SAS to discuss further' WHERE id = 3");
        $this->execute("UPDATE escalate_job_reasons SET reason = 'Tenant requests agent to attend SAS inspection' WHERE id = 10");
    }
}
