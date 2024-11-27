<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class HomeTileApiPropertyNeedsConnecting extends AbstractMigration
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
        // insert data to home_content_block table
        $homeContenBlockRows = [
            [
                'content_block_id'  =>  61,
                'content_name'      =>  'API Property Needs Connecting',
                'category'          =>  1
            ]
        ];

        $this->table('home_content_block')
            ->insert($homeContenBlockRows)
            ->saveData();


        // insert data to page_total table
        $pageTotalRows = [
            [
                'page'      => '/reports/api_unlinked_properties',
                'total'     => 0,
                'active'    => 1
            ]
        ];
        
        $this->table('page_total')
            ->insert($pageTotalRows)
            ->saveData();

            
        // insert data to main_page_total table
        $mainPageTotalRows = [
            [
                'name' => 'api-property-needs-connecting'
            ]
        ];
        
        $this->table('main_page_total')
            ->insert($mainPageTotalRows)
            ->saveData();
    }
}
