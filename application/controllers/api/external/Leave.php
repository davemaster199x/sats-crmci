<?php
class Leave extends CI_Controller  {

    public function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper('email_helper');
        $this->load->model('users_model');
        $this->load->model('system_model');
    }

    public function save_leave() {
        $today = date('Y-m-d');

        // $this->form_validation->set_rules('employee', 'Employee', 'required');
        $this->form_validation->set_rules('leaveType', 'Type of leave', 'required');
        $this->form_validation->set_rules('start', 'Last date of leave', 'required');
        $this->form_validation->set_rules('end', 'First date of leave', 'required');
        $this->form_validation->set_rules('reason', 'Reason for leave', 'required');
        // $this->form_validation->set_rules('lineManager', 'Line manager', 'required');

        // Run the validation
        if ($this->form_validation->run() === FALSE) {
            // Validation failed
            $response = array('status' => 'error', 'message' => validation_errors());
        } else {
            // Validation passed, continue with saving the record


            $data = array(
                'date'              => $today,
                'employee'          => $this->input->post('employee') 
                                        ? $this->input->post('employee') 
                                        : $this->get_staff_id($this->input->post('email')),
                'type_of_leave'     => $this->input->post('leaveType'),
                'lday_of_work'      => $this->system_model->formatDate($this->input->post('start'), 'Y-m-d H:i:s'),
                'fday_back'         => $this->system_model->formatDate($this->input->post('end'), 'Y-m-d H:i:s'),
                'num_of_days'       => $this->input->post('totalHours'),
                'reason_for_leave'  => $this->input->post('reason'),
                'line_manager'      => $this->input->post('lineManager') ? $this->input->post('lineManager') : $this->get_staff_id($this->input->post('manager_email')),
                'status'            => 'Pending',
                'backup_leave'      => $this->input->post('backupLeave'),
                'country_id'        => $this->config->item('country'),
            );


            $last_id = $this->users_model->insert_leave($data);

            
            if ($last_id) {

                //PDF
                // $pdf_data = $this->getLeavePdf('S', $last_id);

                //Employee name
                $employee_name_params = array('sel_query' => 'sa.FirstName, sa.LastName, sa.Email','staff_id'=>$data['employee']);
                $employee_name_row = $this->gherxlib->getStaffInfo($employee_name_params)->row_array();

                //Line manager name
                $line_manager_params = array('sel_query' => 'sa.FirstName, sa.LastName, sa.Email','staff_id'=>$data['line_manager']);
                $line_manager_row = $this->gherxlib->getStaffInfo($line_manager_params)->row_array();


                // Send Email
                $pdf_filename = 'leave_request' . date('dmYHis') . '.pdf';

                if($data['backup_leave']!=""){

                    $param_obj = (object) [
                        'leave_type_id' => $data['backup_leave']
                    ];
                    $leave_types_sql = $this->system_model->get_leave_types($param_obj);
                    $leave_types_row = $leave_types_sql->row();

                    $tol_str = $leave_types_row->leave_name;
                    
                }else{
                    $tol_str = "";
                }

                $email_data['today'] = $today;
                $email_data['employee_name']    = "{$employee_name_row['FirstName']} {$employee_name_row['LastName']}";
                $email_data['type_of_leave']    = $this->getTypesofLeave($data['type_of_leave']);
                $email_data['tol_str']          = $tol_str;
                $email_data['lday_of_work']     = $this->system_model->formatDate($data['lday_of_work'], 'Y-m-d H:i');
                $email_data['fday_back']        = $this->system_model->formatDate($data['fday_back'], 'Y-m-d H:i');
                $email_data['num_of_days']      = $data['num_of_days'];
                $email_data['reason_for_leave'] = $data['reason_for_leave'];
                $email_data['lm_name']          = "{$line_manager_row['FirstName']} {$line_manager_row['LastName']}";

                $to = $line_manager_row['Email'];
                $subject = "Leave request for {$email_data['employee_name']}";

                $this->email->to($to);
                $this->email->subject($subject);
                $e_body = $this->load->view('emails/leave_form_email', $email_data, TRUE);
                // $this->email->attach($pdf_data, 'attachment', $pdf_filename, 'application/pdf'); // remove the pdf for now
                $this->email->message($e_body);

                $responseData = array(
                    'name'          => $email_data['employee_name'],
                    'email'         => $employee_name_row['Email'],
                    'leaveType'     => $email_data['type_of_leave'],
                    'backupLeave'   => '',
                    'start'         => $email_data['lday_of_work'],
                    'end'           => $email_data['fday_back'],
                    'totalHours'    => $email_data['num_of_days'],
                    'reason'        => $email_data['reason_for_leave'],
                    'manager'       => $email_data['lm_name'],
                    'managerEmail' => $line_manager_row['Email']
                );

                // Send the email
                if ($this->email->send()) {
                    $response = array('status' => 'success', 'message' => 'Leave record saved successfully', 'data'=>$responseData);
                } else {
                    // Email sending failed
                    echo 'Email not sent: ' . $this->email->print_debugger();
                }

                
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to save leave record');
            }
        }

        // Return a JSON response
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function get_staff_id($email) {

        if($email){
            $query =  $this->db->select('StaffID')
                            ->from('staff_accounts')
                            ->where('Email', $email)
                            ->get();
            
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $StaffID = $row->StaffID;
                return $StaffID;
            } else {
                return null;
            }
        }
    }


    /**
     * Leave Details PDF LAYOUT
     */
    public function getLeavePdf($output, $leave_id) {
        $this->load->library('JPDF');

        $country_id = $this->config->item('country');

        //GET LEAVE DETAILS
        $sel_query = "
            l.leave_id,
            l.`date`,
            l.lday_of_work,
            l.fday_back,
            l.reason_for_leave,
            l.hr_app,
            l.hr_app_timestamp,
            l.line_manager_app,
            l.line_manager_app_timestamp,
            l.added_to_cal,
            l.added_to_cal_timestamp,
            l.staff_notified,
            l.staff_notified_timestamp,
            l.status,
            l.type_of_leave,
            l.num_of_days,

            sa_emp.`StaffID` AS emp_staff_id,
            sa_emp.`FirstName` AS emp_fname,
            sa_emp.`LastName` AS emp_lname,
            sa_emp.`Email` AS emp_email,

            sa_lm.`StaffID` AS sa_lm_staff_id,
            sa_lm.`FirstName` AS lm_fname,
            sa_lm.`LastName` AS lm_lname,
            sa_lm.`Email` AS lm_email,

            lma.`FirstName` AS lma_fname,
            lma.`LastName` AS lma_lname,
            hra.`FirstName` AS hra_fname,
            hra.`LastName` AS hra_lname,
            atc.`FirstName` AS atc_fname,
            atc.`LastName` AS atc_lname,
            sn.`FirstName` AS sn_fname,
            sn.`LastName` AS sn_lname
        ";
        $params = array(
            'sel_query' => $sel_query,
            'country_id' => $country_id,
            'emp_id' => $employee,
            'lm_id' => $line_manager,
            'l_status' => $status,
            'leave_id' => $leave_id,
            'sort_list' => array(
                array(
                    'order_by' => 'l.date',
                    'sort' => 'DESC',
                ),
            ),
        );
        $leave = $this->users_model->getLeave($params)->row_array();



        // pdf initiation
        $pdf = new JPDF();

        // settings
        $pdf->SetTopMargin(40);
        $pdf->SetAutoPageBreak(true, 50);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // set default values
        $header_space = 6.5;
        $header_width = 100;
        $header_height = 10;
        $header_border = 0;
        $header_new_line = 1;
        $header_align = null;

        $cell_width = 64;
        $cell_height = 6;
        $cell_border = 0;
        $col1_cell_new_line = 0;
        $col2_cell_new_line = 1;
        $col1_cell_align = 'L';
        $col2_cell_align = 'L';


        // LEAVE REQUEST
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell($header_width, $header_height, 'Leave Request', $header_border, $header_new_line, $header_align);

        $pdf->Ln($header_space);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell($cell_width, $cell_height, 'Date: ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, date('d/m/Y', strtotime($leave['date'])), $cell_border, $col2_cell_new_line, $col2_cell_align);
        $pdf->Cell($cell_width, $cell_height, 'Name: ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $leave['emp_fname'] . ' ' . $leave['emp_lname'], $cell_border, $col2_cell_new_line, $col2_cell_align);
        $pdf->Cell($cell_width, $cell_height, 'Type of Leave: ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $this->getTypesofLeave($leave['type_of_leave']), $cell_border, $col2_cell_new_line, $col2_cell_align);
        $pdf->Cell($cell_width, $cell_height, 'First Day of Leave: ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, date('d/m/Y', strtotime($leave['lday_of_work'])), $cell_border, $col2_cell_new_line, $col2_cell_align);
        $pdf->Cell($cell_width, $cell_height, 'Last Day of Leave: ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, date('d/m/Y', strtotime($leave['fday_back'])), $cell_border, $col2_cell_new_line, $col2_cell_align);
        $pdf->Cell($cell_width, $cell_height, 'Number of days : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $leave['num_of_days'], $cell_border, $col2_cell_new_line, $col2_cell_align);
        $pdf->Cell($cell_width, $cell_height, 'Reason for Leave : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->MultiCell($cell_width + 50, $cell_height, $leave['reason_for_leave'], $cell_border, $col2_cell_align);

        $pdf->Ln($header_space);

        // OFFICIAL USE ONLY
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell($header_width, $header_height, 'Office Use Only', $header_border, $header_new_line, $header_align);

        $pdf->Ln($header_space);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell($cell_width, $cell_height, 'Line Manager : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $leave['lm_fname'] . ' ' . $leave['lm_lname'], $cell_border, $col2_cell_new_line, $col2_cell_align);

        // HR Approved
        if (is_numeric($leave['hr_app']) && $leave['hr_app'] == 1) {
            $sel_str = 'Yes';
        } else if (is_numeric($leave['hr_app']) && $leave['hr_app'] == 0) {
            $sel_str = 'No';
        } else {
            $sel_str = '';
        }
        $pdf->Cell($cell_width, $cell_height, 'HR Approved : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $sel_str, $cell_border, $col2_cell_new_line, $col2_cell_align);

        // Line Manager Approved
        if (is_numeric($leave['line_manager_app']) && $leave['line_manager_app'] == 1) {
            $sel_str = 'Yes';
        } else if (is_numeric($leave['line_manager_app']) && $leave['line_manager_app'] == 0) {
            $sel_str = 'No';
        } else {
            $sel_str = '';
        }
        $pdf->Cell($cell_width, $cell_height, 'Line Manager Approved : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $sel_str, $cell_border, $col2_cell_new_line, $col2_cell_align);

        // Added to Calendar
        if (is_numeric($leave['added_to_cal']) && $leave['added_to_cal'] == 1) {
            $sel_str = 'Yes';
        } else if (is_numeric($leave['added_to_cal']) && $leave['added_to_cal'] == 0) {
            $sel_str = 'No';
        } else {
            $sel_str = '';
        }
        $pdf->Cell($cell_width, $cell_height, 'Added to Calendar : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $sel_str, $cell_border, $col2_cell_new_line, $col2_cell_align);

        // Added to MYOB
        if (is_numeric($leave['staff_notified']) && $leave['staff_notified'] == 1) {
            $sel_str = 'Yes';
        } else if (is_numeric($leave['staff_notified']) && $leave['staff_notified'] == 0) {
            $sel_str = 'No';
        } else {
            $sel_str = '';
        }
        $pdf->Cell($cell_width, $cell_height, 'Staff notified in writing : ', $cell_border, $col1_cell_new_line, $col1_cell_align);
        $pdf->Cell($cell_width, $cell_height, $sel_str, $cell_border, $col2_cell_new_line, $col2_cell_align);

        $pdf_filename = 'leave_' . date('dmYHis') . '.pdf';
        return $pdf->Output($pdf_filename, $output);
    }

    public function getTypesofLeave( $leave_type_id = null ) {
  
        if( $leave_type_id > 0 ){

            $param_obj = (object) [
                'leave_type_id' => $leave_type_id
            ];
            $leave_types_sql = $this->system_model->get_leave_types($param_obj);
            $leave_types_row = $leave_types_sql->row();

            return $leave_types_row->leave_name;

        }        
    }

}