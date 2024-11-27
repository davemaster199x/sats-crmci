<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertNewHomeContentBlock extends AbstractMigration
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

        // insert data to home_content_block table
        $homeContenBlockRows = [
            [
                'content_block_id'  =>  56,
                'content_name'      =>  'PropertyMe API - Waiting to Connect',
                'category'          =>  1
            ],
            [
                'content_block_id'  =>  57,
                'content_name'      =>  'Property Tree API - Waiting to Connect',
                'category'          =>  1
            ]
        ];

        $this->table('home_content_block')
            ->insert($homeContenBlockRows)
            ->saveData();

        // insert data to home_content_block table end

        // insert data to main_page_total table
        $mainPageTotalRows = [
            [
                'name' => 'propertyMe-API-waiting-to-connect'
            ],
            [
                'name' => 'property-tree-API-waiting-to-connect'
            ]
        ];
        
        $this->table('main_page_total')
            ->insert($mainPageTotalRows)
            ->saveData();
        // insert data to main_page_total table end
        
        // insert dta to page_total table
        $pageTotalRows = [
            'page'          => '/property_tree/connect_agency '
        ];

        $this->table('page_total')
            ->insert($pageTotalRows)
            ->saveData();
        // insert dta to page_total table end

    }
}
