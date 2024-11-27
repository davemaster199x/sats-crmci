<?php
class Agency_api extends MY_Controller {

	public function __construct(){

		parent::__construct();

        $this->load->model('palace_model');
        $this->load->model('api_model');
        $this->load->model('agency_api_model');

	}

	public function hide_api_property_toggle(){

        $agency_id = $this->input->get_post('agency_id');
        $api_prop_id_arr = $this->input->get_post('api_prop_id_arr');
        $hide_it = $this->input->get_post('hide_it');
        
        foreach( $api_prop_id_arr as $api_prop_id ){

            if( $agency_id > 0 && $api_prop_id != ''  ){

                // clear
                $this->db->where('agency_id', $agency_id);
                $this->db->where('api_prop_id', $api_prop_id);
                $this->db->delete('hidden_api_property');

                if( $hide_it == 1 ){

                    $insert_data = array(
                        'agency_id' => $agency_id,
                        'api_prop_id' => $api_prop_id
                    );                    
                    $this->db->insert('hidden_api_property', $insert_data);

                }
                
    
            }

        }                

    }

    public function linked_properties(){

        $this->load->model('properties_model');

        
        $data['title'] = "Linked Properties";
        $uri = '/agency_api/linked_properties';        

        $country_id = $this->config->item('country');
        $staff_id = $this->session->staff_id;

        $agency_filter = $this->input->get_post('agency_filter');
        $search_p_address = $this->input->get_post('search_p_address');

        // pagination
        //$per_page = $this->config->item('pagi_per_page');
        $per_page = 100;
        $offset = $this->input->get_post('offset');

        // exclude not linked and not active properties
        $custom_where = "
        (

            (

                apd_pme.`api_prop_id` != '' AND 
                apd_pme.`api` = 1

            ) OR (

                apd_palace.`api_prop_id` != '' AND 
                apd_palace.`api` = 4

            ) OR (

                apd_pt.`api_prop_id` != '' AND 
                apd_pt.`api` = 3

            ) OR 
            p.`ourtradie_prop_id` != '' OR
            cp.`crm_prop_id` > 0

        ) 
        AND  p.`deleted` = 0 
        AND  (

            p.`is_nlm` = 0 OR
            p.`is_nlm` IS NULL
            
        )
        ";
        
        // paginated list               
        $sel_query = "
            p.`property_id`,
            p.`address_1` AS p_address_1,
            p.`address_2` AS p_address_2,
            p.`address_3` AS p_address_3,
            p.`state` AS p_state,
            p.`postcode` AS p_postcode,
            p.`is_nlm`,            
            p.`ourtradie_prop_id`,
            p.`deleted`,

            apd_pme.`api` AS pme_api,
            apd_pme.`api_prop_id` AS pme_prop_id,

            apd_palace.`api` AS palace_api,
            apd_palace.`api_prop_id` AS palace_prop_id,

            apd_pt.`api` AS pt_api,
            apd_pt.`api_prop_id` AS pt_prop_id,

            cp.`id` AS cp_id,
            cp.`console_prop_id`,

            a.`agency_id`,
            a.`agency_name`            
        ";
        $params = array(
            'sel_query' => $sel_query,                                                                
            'active' => 1,
            'agency_filter' => $agency_filter,
            'ignore_issue' => 0,
            'search' => $search_p_address,

            'custom_where' => $custom_where,

            'join_table' => array('api_property_data_pme','api_property_data_palace','api_property_data_pt','console'),

            'sort_list' => array(
                array(
                    'order_by' => 'p.`address_2`',
                    'sort' => 'ASC',
                ),
                array(
                    'order_by' => 'p.`address_3`',
                    'sort' => 'ASC',
                )
            ),

            'limit' => $per_page,
            'offset' => $offset,
                        
            'display_query' => 0
        );
        $data['list'] = $this->properties_model->get_properties($params);
        $data['last_query'] = $this->db->last_query();

        // total row
        $sel_query = "COUNT(p.`property_id`) AS p_count";
        $params = array(
            'sel_query' => $sel_query,                                                                
            'active' => 1,
            'agency_filter' => $agency_filter,
            'ignore_issue' => 0,

            'custom_where' => $custom_where,

            'join_table' => array('api_property_data_pme','api_property_data_palace','api_property_data_pt','console'),
                        
            'display_query' => 0
        );
        $tot_row_sql = $this->properties_model->get_properties($params);
        $total_rows = $tot_row_sql->row()->p_count;



        // distinct agency
        $sel_query = "DISTINCT(a.`agency_id`), a.`agency_name`";
        $params = array(
            'sel_query' => $sel_query,                                                                
            'active' => 1,   
            'ignore_issue' => 0,
            
            'custom_where' => $custom_where,

            'join_table' => array('api_property_data_pme','api_property_data_palace','api_property_data_pt','console'),
            
            'sort_list' => array(
                array(
                    'order_by' => 'a.`agency_name`',
                    'sort' => 'ASC',
                )
            ),
                        
            'display_query' => 0
        );            
        $data['agency_filter'] =$this->properties_model->get_properties($params);

        $pagi_links_params_arr = array(
            'agency_filter' => $agency_filter,
        );
        $pagi_link_params = $uri.'/?'.http_build_query($pagi_links_params_arr);


         // pagination
         $config['page_query_string'] = TRUE;
         $config['query_string_segment'] = 'offset';
         $config['total_rows'] = $total_rows;
         $config['per_page'] = $per_page;
         $config['base_url'] = $pagi_link_params;
 
         $this->pagination->initialize($config);
 
         $data['pagination'] = $this->pagination->create_links();
 
         $pc_params = array(
             'total_rows' => $total_rows,
             'offset' => $offset,
             'per_page' => $per_page
         );
         $data['pagi_count'] = $this->jcclass->pagination_count($pc_params);
         $data['uri'] = $uri;

        $this->load->view('templates/inner_header', $data);
        $this->load->view('/authentication/linked_properties', $data);
        $this->load->view('templates/inner_footer', $data);

    }


    public function mark_property_cant_connect_to_api(){

        $property_id = $this->db->escape_str($this->input->get_post('property_id'));
        $cant_connect_to_api_reason = $this->db->escape_str($this->input->get_post('cant_connect_to_api_reason'));

        if( $property_id > 0 ){

            $params_obj = (object)[
                'property_id' => $property_id,
                'cant_connect_to_api_reason' => $cant_connect_to_api_reason
            ];
            $this->agency_api_model->mark_property_cant_connect_to_api($params_obj);

        }              

    }

    public function unmark_property_cant_connect_to_api(){

        $property_id = $this->db->escape_str($this->input->get_post('property_id'));

        if( $property_id > 0 ){

            $this->agency_api_model->unmark_property_cant_connect_to_api($property_id);

        }         

    }

}
