<?php
use App\Exceptions\HttpException;

class Tenants extends MY_ApiController {

    public function __construct() {
        parent::__construct();

        $this->allowedActions = ['login', 'header_check'];
        $this->load->model('tenant_api_model');
    }

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    public function login() {   
        $tmp_mobile = trim($this->api->getPostData('mobile'));

        $pieces = explode("+61", $tmp_mobile);
        $mobile = $pieces[1];
        $mobile_check = strlen($mobile);

        $this->db->select('property_tenant_id, property_id, tenant_firstname, tenant_lastname, tenant_mobile, active');
        $this->db->where('tenant_mobile', $mobile);
        $this->db->order_by('modifiedDate', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get('property_tenants')->result();
        
        //echo $this->db->last_query();
        //exit();

        if(!empty($result)){
            $security_code = random_int(100000, 999999);
            $property_id = $result[0]->property_id;

            //echo "Property ID: ".$property_id;
            //exit();

            $insert_data = array(
                'tenant_property_id' => $result[0]->property_tenant_id,
                'property_id'        => $result[0]->property_id,
                'security_code'      => $security_code
            );

            $tenant_auth = $this->tenant_api_model->tenant_Auth($property_id);
            
            /*
            echo $this->db->last_query();
            print_r($tenant_auth);
            exit();
            */

            if(empty($tenant_auth)){
                $add_property = $this->tenant_api_model->insert_Auth($insert_data);
                $insert_id = $this->db->insert_id();
            }
            else{
                $update_data = array(
                    'tenant_property_id' => $result[0]->property_tenant_id,
                    'security_code'      => $security_code
                );
                $this->tenant_api_model->update_Auth($property_id, $update_data);
            }
            
            $adata_res = array(
                'property_tenant_id' => $result[0]->property_tenant_id,
                'property_id'        => $result[0]->property_id,
                'tenant_firstname'   => $result[0]->tenant_firstname,
                'tenant_lastname'    => $result[0]->tenant_lastname,
                'tenant_mobile'      => $result[0]->tenant_mobile,
                'active'             => 1,
                'security_code'      => $security_code,
                'status_code'        => 200
            );

            //print_r($result);
            //exit();

            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
            $this->api->setMessage('Login successful.');

            $this->api->putData('country_id', $this->config->item('country'));
            $this->api->putData('tenant_data', $adata_res);

            $this->api->putData('token', Authorization::generateToken([
                'timestamp' =>  time() + ($this->config->item('token_timeout') * 60),
                'type' => 'tech',
            ]));

            return;
        }

        $this->api->setStatusCode(200);
        $this->api->setSuccess(false);
        $this->api->setMessage('Invalid mobile.');

    }

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    public function refresh_token() {
        $staffId = $this->api->getJWTItem('staff_id');
        $classId = $this->api->getJWTItem('class_id');

        $this->api->putData('token', Authorization::generateToken([
            'staff_id' => $staffId,
            'class_id' => $classId,
            'timestamp' =>  time() + ($this->config->item('token_timeout') * 60),
            'type' => 'tech',
        ]));

        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
    }

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    public function logout() {
        // invalidate token, maybe?
    }

    /*
    * Bhagvan: not using this method in mobile App APIs
    */
    public function header_check() {
        $headers = $this->input->request_headers();
        if ($this->config->item('is_dev_server')) {
            $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? $headers['AUTHORIZATION'] ?? $this->input->get('tkn', null);

            $this->api->putData('headers', $headers);
            $this->api->putData("authorization", $authorization);
            $this->api->setStatusCode(!is_null($authorization) ? 200 : 401);
            $this->api->setSuccess(!is_null($authorization));
        }
        else {
            $this->api->setStatusCode(200);
            $this->api->setSuccess(true);
        }
    }
}
