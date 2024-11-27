<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChangePropertyTenantsLandlineColLenght extends AbstractMigration
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
        /**
         * Change property_tenants.tenant_landline column lenght from 50 to 100
         */
        $this->query("ALTER TABLE property_tenants MODIFY COLUMN tenant_landline VARCHAR(100)");
    }
}
