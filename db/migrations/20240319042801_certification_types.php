<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CertificationTypes extends AbstractMigration
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
        $table = $this->table('certification_types', ['signed' => true]);

        $table->addColumn('name', 'string', ['limit' => 150, 'null' => true, 'comment' => 'Name of the certification e.g. eCoC'])
            ->addColumn('description', 'string', ['limit' => 255, 'null' => true, 'comment' => 'Short description of the certification'])
            ->addColumn('url', 'string', ['limit' => 150, 'null' => true, 'comment' => 'Login link'])
            ->addColumn('time_to_complete', 'integer', ['default' =>0, 'null' => false, 'comment' => 'Time to complete the certification in Days'])
            ->addColumn('scope', 'string', ['limit' => 15, 'null' => true, 'comment' => 'Which state or region this certification applies to(SA, QLD)'])
            ->addColumn('active', 'boolean', ['limit' => 1,'default' =>1, 'null' => false, 'comment' => '0->Inactive, 1->Avtive']);
        $table->create();
        
        // Insert the first record
        if ($this->isMigratingUp()) {
            $table->insert([['id' => 1, 'name' => 'eCoC (SA)', 'description' => 'Electrical certificate of compliance for South Australa','url' => 'https://ecoc.otr.sa.gov.au/home/Login','time_to_complete' => '30','scope' => 'SA']])
                  ->save();
        }
    }
}
