<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSessionLogsTable extends AbstractMigration
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
	    $table = $this->table('session_logs');
	    $table->addColumn('session_id', 'string')
		    ->addColumn('user_id', 'integer')
		    ->addColumn('request_method', 'string')
		    ->addColumn('domain', 'string')
		    ->addColumn('url', 'string')
		    ->addColumn('query_string', 'string')
		    ->addColumn('referrer', 'string')
		    ->addColumn('ajax', 'boolean')
		    ->addColumn('user_ip', 'string')
		    ->addColumn('user_agent', 'string')
		    ->addColumn('seconds', 'decimal', ['precision'=>8,'scale'=>4])
		    ->addColumn('pre_system', 'datetime')
		    ->addColumn('pre_controller', 'datetime')
		    ->addColumn('post_controller_constructor', 'datetime')
		    ->addColumn('post_controller', 'datetime')
		    ->addColumn('post_system', 'datetime')
		    ->addIndex(['session_id'])
		    ->addIndex(['user_id'])
		    ->addIndex(['url'])
		    ->addIndex(['user_ip'])
		    ->addIndex(['pre_system'])
		    ->addIndex(['post_system'])
		    ->create();

		// phinx doesnt support microsecs
		$this->query("alter table session_logs
    modify pre_system datetime(6) null;

alter table session_logs
    modify pre_controller datetime(6) null;

alter table session_logs
    modify post_controller_constructor datetime(6) null;

alter table session_logs
    modify post_controller datetime(6) null;

alter table session_logs
    modify post_system datetime(6) null;
");
    }
}
