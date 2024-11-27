<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InitDatabase extends AbstractMigration
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
        $sql = file_get_contents(__DIR__ . '/../sql/init.sql');
        $result = $this->query($sql);
        //var_dump($result);
    }

    public function down(): void
    {
        // DROP ALL TABLES
        $query = $this->query("SHOW FULL TABLES WHERE `Table_Type` != 'VIEW'");
        //var_dump($query);
        //var_dump($query);
        $rows = $query->fetchAll();
        //var_dump($rows);
        foreach($rows as $row){
            $table = $this->table($row[0]);
            //var_dump($table);
            if($table->getName() != 'phinxlog'){
                $table->drop()->save();
            }
        }
    }
}
