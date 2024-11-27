<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveFirstAgencyRow extends AbstractMigration
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
        // This does not require a reverse migration, just here to do this automatically on push to prod
        $result = $this->query("DELETE FROM agency WHERE agency_id=1 && agency_name LIKE '%SELECT AGENCY%'");
    }

	public function down(): void
	{
		return;
	}
}
