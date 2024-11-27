<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PropertyCantConnectToApi extends AbstractMigration
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
        $table  = $this->table('property_cant_connect_to_api', ['id' => 'pccta_id']);
        $table->addColumn('property_id', 'integer')
            ->addColumn('comment', 'text', ['null' => true])
            ->create();
    }
}
