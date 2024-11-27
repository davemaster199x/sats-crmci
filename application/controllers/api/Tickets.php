<?php

class Tickets extends MY_ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        $this->load->model('reports_model');
        $this->load->model('crmtasks_model');
    }

    public function list()
    {
        // select
        $custom_select = '
        ct.`crm_task_id`,
        ct.`date_created`,
        ct.`page_link`,
        ct.`describe_issue`,
        ct.`response`,
        ct.`status` AS ct_status,
        ct.`issue_summary`,
        ct.`category`,
        ct.`sub_category`,
        ct.`ticket_priority`,
        ct.`completed_ts`,
        ct.`last_updated_ts`,
        ct.`last_updated_by`,
        ct.`requested_by`,
        cts.`status` AS cts_status,
        cts.`hex`,

        rb.`StaffID`,
        rb.`FirstName`,
        rb.`LastName`,
        rb.`Email`
        ';

        $params = array(
            'custom_select' => $custom_select,
            'active' => 1,
            'api_ticket' => 1,
            'group_by' => 'ct.`crm_task_id`',
            'echo_query' => 0
        );

        if ($this->api->getPostData('assigned_to') != "") {
            $params["conditions"][] = array(
                "type" => "where",
                "column" => "user_assigned.Email",
                "value" => $this->api->getPostData('assigned_to'),
            );
        }

        if ($this->api->getPostData('created_by') != "") {
            $params["conditions"][] = array(
                "type" => "where",
                "column" => "rb.Email",
                "value" => $this->api->getPostData('created_by'),
            );
        }

        if ($this->api->getPostData('priority') != "") {
            $params["conditions"][] = array(
                "type" => "where",
                "column" => "ct.ticket_priority",
                "value" => $this->api->getPostData('priority'),
            );
        }

        if ($this->api->getPostData('category') != "") {
            $params["conditions"][] = array(
                "type" => "where",
                "column" => "ctc.category_name",
                "value" => $this->api->getPostData('category'),
            );
        }

        if ($this->api->getPostData('sub_category') != "") {
            $params["conditions"][] = array(
                "type" => "where",
                "column" => "ctht.sub_category_name",
                "value" => $this->api->getPostData('sub_category'),
            );
        }

        if ($this->api->getPostData('offset') != "") {
            $offset = $this->api->getPostData('offset') <= 0 ? 0 : $this->api->getPostData('offset');
            $per_page = $this->api->getPostData('limit') <= 0 ? 10 : $this->api->getPostData('offset');
            $params["paginate"] = array(
                "offset" => $offset,
                'limit' => $per_page
            );
        }

        /*
        'sort_list' => array(
            array(
                'order_by' => $date_order,
                'sort' => $sort_order
            )
        ),*/

        $plist = $this->crmtasks_model->getButtonCrmTasks($params);
        $data['tasks'] = $plist->result_array();

        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
        $this->api->putData('tickets', $data['tasks']);
    }

    public function details($id)
    {
        $sql_str = "
            SELECT *
            FROM `crm_tasks`
            WHERE `crm_task_id` = {$id}
            ORDER BY `date_created` DESC
            ";
        $crm_task_sql = $this->db->query($sql_str);
        $data['crm_task_row'] = $crm_task_sql->row();

        // Get email of the requestor
        $sql_requestor = "
            SELECT EMAIL
            FROM `staff_accounts`
            WHERE `StaffID` = {$data['crm_task_row']->requested_by}
            ";
        $task_requestor_sql = $this->db->query($sql_requestor);
        $data['task_requestor'] = $task_requestor_sql->row();

        // Get email of the assignee
        $sql_assignee = "
            SELECT EMAIL
            FROM `staff_accounts`
            WHERE `StaffID` = {$data['crm_task_row']->last_updated_by}
            ";
        $task_assignee_sql = $this->db->query($sql_assignee);
        $data['task_assignee'] = $task_assignee_sql->row();

        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
        $this->api->putData('details', $data['crm_task_row']);
        $this->api->putData('requestor', $data['task_requestor']);
        $this->api->putData('assignee', $data['task_assignee']);
    }

    public function add()
    {
        $this->api->assertMethod('post');

        $priority    = $this->api->getPostData('priority');
        $category    = $this->api->getPostData('category');
        $subject     = $this->api->getPostData('subject');
        $description = $this->api->getPostData('description');
        $staffId     = $this->api->getPostData('requested_by');
        
        $params = array(
            'ticket_priority' => $priority,
            'category' => $category,
            'issue_summary' => $subject,
            'describe_issue' => $description,
            'requested_by' => $staffId
        );

        // save new task
        $success = $this->db->set($params)
            ->insert("crm_tasks");

        // get new task
        $newTaskId = $this->db->insert_id();
        $newTask = $this->db->select()
            ->from("crm_tasks")
            ->where("crm_task_id", $newTaskId)
            ->limit(1)
            ->get()->row_array();

        $this->api->setStatusCode(200);
        $this->api->setSuccess(true);
        $this->api->putData('task', $newTask);
    }
}
