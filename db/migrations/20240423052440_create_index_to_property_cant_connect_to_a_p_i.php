<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateIndexToPropertyCantConnectToAPI extends AbstractMigration
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

        //Create property_id index for property_cant_connect_to_api table
        //Drop index if exist else create new one
        $result = $this->query("SHOW INDEX FROM property_cant_connect_to_api WHERE Key_name = 'property_id_idx'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX property_id_idx ON property_cant_connect_to_api");
	    }
	    $this->query("CREATE INDEX property_id_idx ON property_cant_connect_to_api(property_id)");

    }
}
