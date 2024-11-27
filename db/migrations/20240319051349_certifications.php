<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Certifications extends AbstractMigration
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
        $table = $this->table('certifications', ['signed' => true]);

        $table->addColumn('certification_id', 'integer', ['null' => true, 'comment' => 'Certification Type ID'])
            ->addColumn('job_id', 'integer', ['null' => true, 'comment' => 'Job ID'])
            ->addColumn('certification_number', 'string', ['limit' => 20, 'null' => true, 'comment' => '3rd party number of the certificate or document'])
            ->addColumn('file_name', 'string', ['limit' => 150, 'null' => true, 'comment' => 'path to PDF certificate'])
            ->addColumn('app_completed_date', 'datetime', ['null' => true, 'comment' => 'Timestamp of when the certification was completed'])
            ->addColumn('app_completed_by', 'integer', ['null' => true, 'comment' => 'Tech ID aka Staff ID'])
            ->addColumn('crm_completed_date', 'datetime', ['null' => true, 'comment' => 'Timestamp of completion on the CRM'])
            ->addColumn('crm_completed_by', 'integer', ['null' => true, 'comment' => 'Staff ID'])
            ->addColumn('status', 'enum', ['values' => ['open','submitted','completed','send_back','cancelled'], 'default'=>'open', 'null' => false])
            ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'null' => false]);
        $table->create();
    }
}
