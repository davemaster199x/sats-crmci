<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColsCertType extends AbstractMigration
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
        $certification_types = $this->table('certification_types');
	    $certification_types->addColumn('question', 'string', ['limit' => 150, 'null' => true,'after'=>'name'])->update();
        
        // Update the first record
        if ($this->isMigratingUp()) {
            $builder = $this->getQueryBuilder();
            $builder
                ->update('certification_types')
                ->set('question', 'Is ECOC required?')
                ->where(['id'=>1])
                ->execute();
        }
    }
}
