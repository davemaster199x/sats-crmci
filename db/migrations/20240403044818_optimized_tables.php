<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OptimizedTables extends AbstractMigration
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
		//Modify properties_needs_verification table
	    $table = $this->table('properties_needs_verification');
	    $table->changeColumn('property_address', 'string', ['limit' => 255])
		    ->changeColumn('note', 'string', ['limit' => 255])
		    ->changeColumn('last_contact_info', 'string', ['limit' => 255])
		    ->update();
	    
	    /**
	     * If not empty, drop the index; otherwise, create the index.
	     */
	    $result = $this->query("SHOW INDEX FROM api_last_tenant_update WHERE Key_name = 'idx_api_last_tenant_update'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_api_last_tenant_update ON api_last_tenant_update");
	    }
	    $this->query("CREATE INDEX idx_api_last_tenant_update ON api_last_tenant_update(api_property_data_id)");
	    
	    //properties_needs_verification
	    $result = $this->query("SHOW INDEX FROM properties_needs_verification WHERE Key_name = 'idx_pnv_columns'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_pnv_columns ON properties_needs_verification");
	    }
	    $this->query("CREATE INDEX idx_pnv_columns ON properties_needs_verification(property_source,property_id,agency_id,ignore_issue,active)");
	    
	    //property_services
	    $result = $this->query("SHOW INDEX FROM property_services WHERE Key_name = 'idx_property_services'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_property_services ON property_services");
	    }
	    $this->query("CREATE INDEX idx_property_services ON property_services(property_id,service)");
		
		//extra_job_notes
	    $result = $this->query("SHOW INDEX FROM extra_job_notes WHERE Key_name = 'idx_extra_job_notes'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_extra_job_notes ON extra_job_notes");
	    }
	    $this->query("CREATE INDEX idx_extra_job_notes ON extra_job_notes(job_id)");
	    
	    //jobs
	    $result = $this->query("SHOW INDEX FROM jobs WHERE Key_name = 'idx_jobs'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_jobs ON jobs");
	    }
	    $this->query("CREATE INDEX idx_jobs ON jobs(property_id)");
	    
		//agency_user_accounts
	    $result = $this->query("SHOW INDEX FROM agency_user_accounts WHERE Key_name = 'idx_agency_user_accounts'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_agency_user_accounts ON agency_user_accounts");
	    }
	    $this->query("CREATE INDEX idx_agency_user_accounts ON agency_user_accounts(agency_user_account_id)");
		
		//property table
	    $result = $this->query("SHOW INDEX FROM property WHERE Key_name = 'idx_properties'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_properties ON property");
	    }
	    $this->query("CREATE INDEX idx_properties ON property(pm_id_new)");
	    
	    //api_property_data
	    $result = $this->query("SHOW INDEX FROM api_property_data WHERE Key_name = 'crm_prop_id_idx'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX crm_prop_id_idx ON api_property_data");
	    }
	    $this->query("CREATE INDEX crm_prop_id_idx ON api_property_data(crm_prop_id,api_prop_id)");
	    
	    //agency_priority
	    $result = $this->query("SHOW INDEX FROM agency_priority WHERE Key_name = 'idx_agency_priority'");
	    if (!empty($result->fetchAll())) {
		    $this->query("DROP INDEX idx_agency_priority ON agency_priority");
	    }
	    $this->query("CREATE INDEX idx_agency_priority ON agency_priority(priority)");
		
	    //agency_priority_marker_definition
    }
}
