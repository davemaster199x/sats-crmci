<div class="row">
    <div class="col-md-12">
        <section class="card card-blue-fill">
            <header class="card-header">Invoice Details</header>
            <div class="card-block invoice_details_div">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label>Address</label>
                        <?php echo "{$job_row['p_address_1']} {$job_row['p_address_2']} {$job_row['p_address_3']}, {$job_row['p_state']} {$job_row['p_postcode']}" ?>
                    </div>
                    <div class="col-md-4">
                        <label>Agency Name</label>
                        <?php echo $job_row['agency_name']; ?> <?php echo ($job_row['a_priority']>0) ? "(".$job_row['abbreviation'].")" :NULL ?>
                    </div>
                    <div class="col-md-4">
                        <label>Job Type</label>
                        <?php echo $job_row['j_type'] ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-4">
                        <label>Invoice Date</label>
                        <?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? $this->system_model->formatDate($job_row['j_date'],'d/m/Y') : 'No Data'; ?>
                    </div>
                    <div class="col-md-4">
                        <label>Due</label>
                        <?php echo ($this->system_model->isDateNotEmpty($job_row['j_date'])) ? date('d/m/Y', strtotime($job_row['j_date'] . "+30 days")) : 'No Data'; ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-12">
                        <table style="width: 200px;">
                            <tr>
                                <td><label>Invoice Amount</label></td>
                                <td> $<?php echo number_format($job_row['invoice_amount'], 2); ?></td>
                            </tr>
                            <tr style="border:1px solid #ccc;padding:5px;">
                                <td style="padding-left:3px;"><label>Balance</label></td>
                                <td> <strong class="<?php echo ( $job_row['invoice_balance'] > 0 ) ? 'text-red' : ''; ?>">$<span class="invoice-balance"><?php echo number_format($job_row['invoice_balance'], 2) ?></span></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
<div class="row">

    <div class="col-md-4">
        <section class="card card-blue-fill">
            <header class="card-header">Payments</header>
            <div class="card-block">
                <table class="table">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Reference</th>
                        <th>&nbsp;</th>
                    </tr>
                   
                    <?php foreach( $invoicePaymentsData->result_array() as $invoicePaymentsData_row ){ ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($invoicePaymentsData_row['payment_date'])) ?></td>
                        <td><?php echo $invoicePaymentsData_row['amount_paid']; ?></td>
                        <td><?php echo $invoicePaymentsData_row['pt_name']; ?></td>
                        <td><?php echo $invoicePaymentsData_row['payment_reference'] ?></td>
                        <td style="width: 45px;">
                            <?php if( ( $this->system_model->getStaffClassID() == 2 || $this->system_model->getStaffClassID() == 9 ) && $this->system_model->can_edit_account(4) == true ){ ?>
                                <a data-fancybox="" data-src="#edit_pament_fb_<?php echo $invoicePaymentsData_row['invoice_payment_id'] ?>" data-toggle="tooltip" title="Edit" class="icon_actions btn_edit_payment"><span class="font-icon font-icon-pencil"></span></a>
                                <a data-payment_id="<?php echo $invoicePaymentsData_row['invoice_payment_id'] ?>" data-toggle="tooltip" title="Remove" class="icon_actions btn_delete_payment"><span class="glyphicon glyphicon-trash"></span></a>
                            <?php } ?>

                            <!-- Edit payment fancybox -->
                            <div class="edit_pament_fb" id="edit_pament_fb_<?php echo $invoicePaymentsData_row['invoice_payment_id'] ?>" style="display: none;">

                                <section class="card card-blue-fill">
                                    <header class="card-header">Edit Payment</header>
                                    <div class="card-block">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="text" class="payment_date form-control flatpickr" value="<?php echo date('d/m/Y', strtotime($invoicePaymentsData_row['payment_date'])) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="payment_amount form-control" value="<?php echo $invoicePaymentsData_row['amount_paid']; ?>">
                                            <input type="hidden" class="orig_payment_amount form-control" value="<?php echo $invoicePaymentsData_row['amount_paid']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Type</label>
                                            <select class="form-control payment_type">
                                                <option value="">Please select</option>
                                                <?php foreach( $this->system_model->getPaymentTypes(array('sel_query'=>'*'))->result_array() as $payments_row ){ ?>
                                                <option <?php echo ($payments_row['payment_type_id']==$invoicePaymentsData_row['type_of_payment']) ? 'selected' : null; ?> value="<?php echo $payments_row['payment_type_id'] ?>"><?php echo $payments_row['pt_name'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Payment Reference</label>
                                            <input type="text" class="payment_reference form-control" value="<?php echo $invoicePaymentsData_row['payment_reference'] ?>">
                                        </div>
                                    </div>
                                </section>
                                <div class="form-group text-right">
                                    <input type="hidden" class="invoice_payment_id" value="<?php echo $invoicePaymentsData_row['invoice_payment_id'] ?>">
                                    <button type="button" class="edit_payment_details_btn btn btn-sm">Update</button>
                                </div>
                            </div>
                            <!-- Edit payment fancybox end -->
                            
                        </td>
                    </tr>
                    <?php } ?>
                    
                </table>

                <?php
                if( $this->system_model->can_add_account(10)==true || $this->system_model->can_edit_account(4) == true ){ ?>
                <div class="text-right" style="margin-top:10px;">
                    <a href="#" data-fancybox data-src="#add_payment_fb"  id="add_payment_btn" class="btn btn-sm"><span class="fa fa-plus"></span> Payment</a>&nbsp;&nbsp;
                </div>
                <?php } ?>

                <!-- payment add fancybox -->
                <div id="add_payment_fb" style="display: none;">
                    <section class="card card-blue-fill">
                        <header class="card-header">Add Payment</header>
                        <div class="card-block">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" id="payment_date" class="payment_date form-control flatpickr" value="<?php echo Date("d/m/Y"); ?>">
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" id="payment_amount" class="payment_amount form-control" value="">
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select id="payment_type" class="form-control payment_type">
                                    <option value="">Please select</option>
                                    <?php foreach( $this->system_model->getPaymentTypes(array('sel_query'=>'*'))->result_array() as $payments_row ){ ?>
                                    <option value="<?php echo $payments_row['payment_type_id'] ?>"><?php echo $payments_row['pt_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Payment Reference</label>
                                <input type="text" id="payment_reference" class="payment_reference form-control" value="">
                            </div>
                        </div>
                    </section>
                        <div class="form-group text-right">
                            <button type="button" id="save_payment_details_btn" class="btn btn-sm"><span class="fa fa-save"></span> Save</button>
                        </div>
                </div>
                <!-- payment add fancybox end -->

            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="card card-blue-fill">
            <header class="card-header">Refunds</header>
            <div class="card-block">
                <table class="table">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Payment Reference</th>
                        <th></th>
                    </tr>
                    <?php foreach( $invoiceRefundsData->result_array() as $getInvoiceRefundsData_row ){ ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($getInvoiceRefundsData_row['payment_date'])) ?></td>
                        <td><?php echo $getInvoiceRefundsData_row['amount_paid']; ?></td>
                        <td><?php echo $getInvoiceRefundsData_row['pt_name']; ?></td>
                        <td><?php echo $getInvoiceRefundsData_row['payment_reference'] ?></td>
                        <td style="width: 45px;">
                            <?php if( ( $this->system_model->getStaffClassID() == 2 || $this->system_model->getStaffClassID() == 9 ) && $this->system_model->can_edit_account(4) == true ){ ?>
                                <a data-fancybox="" data-src="#edit_refund_fb_<?php echo $getInvoiceRefundsData_row['invoice_refund_id'] ?>" data-toggle="tooltip" title="Edit" class="icon_actions btn_edit_refund"><span class="font-icon font-icon-pencil"></span></a>
                                <a data-refund_id="<?php echo $getInvoiceRefundsData_row['invoice_refund_id'] ?>" data-toggle="tooltip" title="Remove" class="icon_actions btn_delete_refund"><span class="glyphicon glyphicon-trash"></span></a>
                            <?php } ?>

                            <!-- edit refund fancybox -->
                            <div class="edit_refund_fb" id="edit_refund_fb_<?php echo $getInvoiceRefundsData_row['invoice_refund_id'] ?>" style="display:none;">
                                <section class="card card-blue-fill">
                                        <header class="card-header">Edit Refund</header>
                                        <div class="card-block">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="text" class="refund_date form-control flatpickr" value="<?php echo date('d/m/Y', strtotime($getInvoiceRefundsData_row['payment_date'])) ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="hidden" class="orig_refund_amount form-control" value="<?php echo $getInvoiceRefundsData_row['amount_paid']; ?>">
                                                <input type="text" class="refund_amount form-control" value="<?php echo $getInvoiceRefundsData_row['amount_paid']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select class="form-control refund_type">
                                                    <option value="">Please select</option>
                                                    <?php foreach( $this->system_model->getPaymentTypes(array('sel_query'=>'*','custom_where'=>'payment_type_id IN (3,5,6)'))->result_array() as $payments_row ){ ?>
                                                    <option <?php echo ($payments_row['payment_type_id']==$getInvoiceRefundsData_row['type_of_payment']) ? 'selected' : null; ?> value="<?php echo $payments_row['payment_type_id'] ?>"><?php echo $payments_row['pt_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Refund Reference</label>
                                                <input type="text" class="refund_reference form-control" value="<?php echo $getInvoiceRefundsData_row['payment_reference'] ?>">
                                            </div>
                                        </div>
                                </section>
                                <div class="form-group text-right">
                                    <input type="hidden" class="invoice_refund_id" value="<?php echo $getInvoiceRefundsData_row['invoice_refund_id'] ?>">
                                    <button type="button" class="btn btn-sm edit_refund_details_btn"><span class="fa fa-save"></span> Save</button>
                                </div>
                            </div>
                            <!-- edit refund fancybox end -->

                        </td>
                    </tr>
                    <?php } ?>
                </table>

                <?php if( $this->system_model->can_add_account(10)==true || $this->system_model->can_edit_account(4) == true ){ ?>
                <div class="text-right" style="margin-top:10px;">
                    <a href="#" data-fancybox data-src="#add_refund_fb" id="add_refund_btn" class="btn btn-sm"><span class="fa fa-plus"></span> Refund</a>
                </div>
                <?php } ?>

                <!-- add new refund fb -->
                <div id="add_refund_fb" style="display: none;">
                    <section class="card card-blue-fill">
                        <header class="card-header">Add Refund</header>
                        <div class="card-block">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" id="refund_date" class="refund_date form-control flatpickr" value="<?php echo Date("d/m/Y"); ?>">
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" id="refund_amount" class="refund_amount form-control" value="">
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select id="refund_type" class="form-control refund_type">
                                    <option value="">Please select</option>
                                    <?php foreach( $this->system_model->getPaymentTypes(array('sel_query'=>'*','custom_where'=>'payment_type_id IN (3,5,6)'))->result_array() as $payments_row ){ ?>
                                    <option value="<?php echo $payments_row['payment_type_id'] ?>"><?php echo $payments_row['pt_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Refund Reference</label>
                                <input type="text" id="refund_reference" class="refund_reference form-control" value="">
                            </div>
                        </div>
                    </section>
                    <div class="form-group text-right">
                        <button type="button" id="save_refund_details_btn" class="btn btn-sm"><span class="fa fa-save"></span> Save</button>
                    </div>
                </div>
                <!-- add new refund fb end -->

            </div>
        </section>
    </div>

    <div class="col-md-4">
        <section class="card card-blue-fill">
            <header class="card-header">Credits</header>
            <div class="card-block">
                <table class="table">
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Reason for Credit</th>
                        <th>Approved by</th>
                        <th>Payment Reference</th>
                        <th>&nbsp;</th>
                    </tr>
                    <?php foreach( $invoiceCreditsData->result_array() as $invoiceCreditsData_row ){ ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($invoiceCreditsData_row['credit_date'])) ?></td>
                        <td><?php echo $invoiceCreditsData_row['credit_paid']; ?></td>
                        <td><?php echo $invoiceCreditsData_row['reason']; ?></td>
                        <td><?php echo "{$invoiceCreditsData_row['FirstName']} {$invoiceCreditsData_row['LastName']}"; ?></td>
                        <td><?php echo $invoiceCreditsData_row['payment_reference']; ?></td>
                        <td style="width: 45px;">
                            <?php if( ( $this->system_model->getStaffClassID() == 2 || $this->system_model->getStaffClassID() == 9 ) && $this->system_model->can_edit_account(4) == true ){ ?>
                                <a data-fancybox="" data-src="#edit_credit_fb_<?php echo $invoiceCreditsData_row['invoice_credit_id'] ?>" data-toggle="tooltip" title="Edit" class="icon_actions btn_edit_credit"><span class="font-icon font-icon-pencil"></span></a>
                                <a data-invoice_credit_id="<?php echo $invoiceCreditsData_row['invoice_credit_id'] ?>" data-toggle="tooltip" title="Remove" class="icon_actions btn_delete_credit"><span class="glyphicon glyphicon-trash"></span></a>
                            <?php } ?>

                            <div class="edit_credit_fb" id="edit_credit_fb_<?php echo $invoiceCreditsData_row['invoice_credit_id'] ?>" style="display: none;">
                                <section class="card card-blue-fill">
                                    <header class="card-header">Edit Credit</header>
                                    <div class="card-block">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="text" class="credit_date form-control flatpickr" value="<?php echo date('d/m/Y', strtotime($invoiceCreditsData_row['credit_date'])) ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="credit_amount form-control" value="<?php echo $invoiceCreditsData_row['credit_paid'] ?>">
                                            <input type="hidden" class="orig_credit_amount" value="<?php echo $invoiceCreditsData_row['credit_paid'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Reason for Credit</label>
                                            <select class="form-control credit_reason">
                                                <option value="">Please select</option>
                                                <?php foreach( $this->system_model->getCreditReason_v2()->result_array() as $cr_row ){ ?>
                                                    <option <?php echo ($cr_row['credit_reason_id']==$invoiceCreditsData_row['credit_reason_id']) ? 'selected' : null;  ?> value="<?php echo $cr_row['credit_reason_id'] ?>" <?php echo ( $invoiceCreditsData_row['reason'] == $cr_row['credit_reason_id'] ) ? 'selected="selected"' : ''; ?>><?php echo $cr_row['reason'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Approved by</label>
                                            <select type="text" class="credit_approved_by form-control">
                                                <option value="">Please select</option>
                                                <?php
                                                    $credit_approved_by_params = array(
                                                        'sel_query' => "sa.StaffID, sa.FirstName, sa.LastName",
                                                        'joins' => array('country_access'),
                                                        'country_id' => $this->config->item('country'),
                                                        'sort_list' => array(
                                                            array(
                                                                'order_by' => 'sa.`FirstName`',
                                                                'sort' => 'ASC'
                                                            ),
                                                            array(
                                                                'order_by' => 'sa.`LastName`',
                                                                'sort' => 'ASC'
                                                            )
                                                        ),
                                                        'active' => 1,
                                                        'deleted' => 0
                                                    );
                                                    $credit_approved_by_q = $this->staff_accounts_model->get_staff_accounts($credit_approved_by_params)->result_array();
                                                    foreach( $credit_approved_by_q as $credit_approved_by_row ){
                                                ?>
                                                    <option <?php echo ($invoiceCreditsData_row['StaffID']==$credit_approved_by_row['StaffID']) ? 'selected' : null; ?> value="<?php echo $credit_approved_by_row['StaffID'] ?>"><?php echo "{$credit_approved_by_row['FirstName']} {$credit_approved_by_row['LastName']}" ?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Credit Reference</label>
                                            <input type="text" class="credit_reference form-control" value="<?php echo $invoiceCreditsData_row['payment_reference']; ?>">
                                        </div>
                                    </div>
                                </section>
                                <div class="form-group text-right">
                                    <input type="hidden" class="invoice_credit_id" value="<?php echo $invoiceCreditsData_row['invoice_credit_id'] ?>">
                                    <button type="button" class="btn btn-sm update_credit_details_btn"><span class="fa fa-save"></span> Update</button>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <?php } ?>
                </table>

                <div class="text-right" style="margin-top:10px;">
                    
                    <a target="_blank" class="btn" style="background-color:#fb6067;border:none;font-size:16px;padding:3px 10px;" href="/pdf/invoices/<?php echo $encrypted_job_id; ?>"><span class="fa fa-file-pdf-o"></span></a>&nbsp;&nbsp;

                    <?php if( $this->system_model->can_add_account(10)==true || $this->system_model->can_edit_account(4) == true ){ ?>
                    <a href="#" data-fancybox="" data-src="#add_credits_fb" id="add_credit_btn" class="btn btn-sm"><span class="fa fa-plus"></span> Credits</a>
                    <?php } ?>

                    <!-- Add credits fancybox -->
                    <div id="add_credits_fb" style="display: none;">
                        <section class="card card-blue-fill">
                            <header class="card-header">Add Credit</header>
                            <div class="card-block">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" id="credit_date" class="credit_date form-control flatpickr" value="<?php echo Date("d/m/Y"); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" id="credit_amount" class="credit_amount form-control" value="">
                                </div>
                                <div class="form-group">
                                    <label>Reason for Credit</label>
                                    <select id="credit_reason" class="form-control credit_reason">
                                        <option value="">Please select</option>
                                        <?php foreach( $this->system_model->getCreditReason_v2()->result_array() as $cr_row ){ ?>
                                            <option value="<?php echo $cr_row['credit_reason_id'] ?>" <?php echo ( $invoiceCreditsData_row['reason'] == $cr_row['credit_reason_id'] ) ? 'selected="selected"' : ''; ?>><?php echo $cr_row['reason'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Approved by</label>
                                    <select type="text" id="credit_approved_by" class="credit_approved_by form-control">
                                        <option value="">Please select</option>
                                        <?php
                                            $credit_approved_by_params = array(
                                                'sel_query' => "sa.StaffID, sa.FirstName, sa.LastName",
                                                'joins' => array('country_access'),
                                                'country_id' => $this->config->item('country'),
                                                'sort_list' => array(
                                                    array(
                                                        'order_by' => 'sa.`FirstName`',
                                                        'sort' => 'ASC'
                                                    ),
                                                    array(
                                                        'order_by' => 'sa.`LastName`',
                                                        'sort' => 'ASC'
                                                    )
                                                ),
                                                'active' => 1,
                                                'deleted' => 0
                                            );
                                            $credit_approved_by_q = $this->staff_accounts_model->get_staff_accounts($credit_approved_by_params)->result_array();
                                            foreach( $credit_approved_by_q as $credit_approved_by_row ){
                                        ?>
                                            <option value="<?php echo $credit_approved_by_row['StaffID'] ?>"><?php echo "{$credit_approved_by_row['FirstName']} {$credit_approved_by_row['LastName']}" ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Credit Reference</label>
                                    <input type="text" id="credit_reference" class="credit_reference form-control">
                                </div>
                            </div>
                        </section>
                        <div class="form-group text-right">
                            <button type="button" id="save_credit_details_btn" class="btn btn-sm"><span class="fa fa-save"></span> Save</button>
                        </div>
                    </div>
                    <!-- Add credits fancybox end -->

                </div>
                
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="checkbox" style="margin-bottom:25px;">
            <input class="form-control" type="checkbox" id="unpaid_chk" <?php echo ( $job_row['unpaid'] == 1 ) ? 'checked="checked"' : null; ?> />
            <label for="unpaid_chk">Unpaid ( if Ticked, this job will show on Debtors report and Agency portal as Unpaid until invoice Balance = $0 )</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="card card-blue-fill">
            <header class="card-header">Old Accounts Notes</header>
            <div class="card-block">
                <table class="table table-hover main-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Who</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if( $old_account_notes->num_rows()>0 ){
                            foreach( $old_account_notes->result_array() as $old_account_notes_row ){ 
                        ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($old_account_notes_row['created_date'])); ?></td>
                                <td><?php echo date('H:i', strtotime($old_account_notes_row['created_date'])); ?></td>
                                <td><?php echo $old_account_notes_row['contact_type']; ?></td>
                                <td><?php echo $this->system_model->formatStaffName($old_account_notes_row['FirstName'], $old_account_notes_row['LastName']); ?></td>
                                <td><?php echo $old_account_notes_row['comments']; ?></td>
                            </tr>
                        <?php }}else{
                            echo "<tr><td colspan='100%'>No Data</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="card card-blue-fill">
            <header class="card-header">New Account Notes</header>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-2">
                        <label>Date</label>
                        <input type="text" class="form-control today_max_flatpickr al_date" value="<?php echo date("d/m/Y") ?>">
                    </div>
                    <div class="col-md-8">
                        <label>Comment</label>
                        <input type="text" class="form-control al_comment">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button id="btn_add_accounts_log" class="btn">Add Event</button>
                    </div>
                </div>
                <div class="row" style="margin-top:15px;">
                    <div class="col-md-12">
                        <table class="table table-hover main-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Title</th>
                                    <th>Who</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if( $new_accounts_logs->num_rows() >0 ){
                                foreach( $new_accounts_logs->result_array() as $new_accounts_logs_row ){ ?>
                                <tr>
                                <td>
								    <?php echo date('d/m/Y',strtotime($new_accounts_logs_row['created_date'])); ?>
                                </td>
                                <td>
                                    <?php echo date('H:i',strtotime($new_accounts_logs_row['created_date'])); ?>
                                </td>
                                <td>
                                    <?php echo $new_accounts_logs_row['title_name']; ?>
                                </td>
                                <td>
                                    <?php
                                    if( $new_accounts_logs_row['StaffID'] != '' ){ // sats staff
                                        echo $this->system_model->formatStaffName($new_accounts_logs_row['FirstName'],$new_accounts_logs_row['LastName']);
                                    }else{ // agency portal users
                                        echo "{$new_accounts_logs_row['fname']} {$new_accounts_logs_row['lname']}";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $new_accounts_logs_row['details'] ?></td>
                                </tr>
                                <?php }
                                }else{
                                    echo "<tr><td colspan='100%'>No Data</td></tr>";
                                } ?>
                            </tbody>
                        </table>

                        <nav aria-label="Page navigation example" style="text-align:center">
                            <?php echo $new_accounts_logs_pagination; ?>
                        </nav>

                        <div class="pagi_count text-center">
                            <?php echo $new_accounts_logs_pagi_count; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<script type="text/javascript">

    function unpaid_check_action() {
        var job_id = <?php echo $job_row['jid']; ?>;;
        var unpaid = ($('#unpaid_chk').prop("checked") == true) ? 1 : 0;

        if (parseInt(job_id) > 0) {

            jQuery("#load-screen").show();
            jQuery.ajax({
                type: "POST",
                url: "/jobs/ajax_toggle_unpaid_marker",
                dataType: 'json',
                data: {
                    job_id: job_id,
                    unpaid: unpaid
                }
            }).done(function (ret) {
                if(ret.status){
                    jQuery("#load-screen").hide();

                    swal({
                        title:"Success!",
                        text: "Unpaid status successfully updated",
                        type: "success",
                        showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                        timer: <?php echo $this->config->item('timer') ?>
                    });


                    var full_url = window.location.href;
                    setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                }
            });

        }
    }

    $(document).ready(function(){ 
        var job_id  =  <?php echo $job_row['jid'] ?? 0 ?>;

        jQuery('.today_max_flatpickr').flatpickr({
            dateFormat: "d/m/Y",
            maxDate: "today"
        });

        //Paid/Unpaid checkbox check
        var invoice_balance = parseFloat($('span.invoice-balance').text());
        var chkUnpaid = $('#unpaid_chk:checked').length;
        if (invoice_balance === 0) {
            if (chkUnpaid === 1) {
                $('#unpaid_chk').removeAttr("checked");
                unpaid_check_action();
            }
        }
        //Paid/Unpaid checkbox check end
        
        $('#save_payment_details_btn').on('click',function(e){

            var node = $(this).parents('#add_payment_fb');
            var payment_date = node.find('#payment_date').val();
            var payment_amount = node.find('#payment_amount').val();
            var payment_type = node.find('#payment_type').val();
            var payment_reference = node.find('#payment_reference').val();

            swal({
                title: "Warning!",
                text: "Add payment?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_submit_invoice_payment",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                payment_date: payment_date,
                                payment_amount: payment_amount,
                                payment_type: payment_type,
                                payment_reference: payment_reference
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Payment successfully added",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('.edit_payment_details_btn').on('click',function(e){

            var node = $(this).parents('.edit_pament_fb');
            var invoice_payment_id = node.find('.invoice_payment_id').val();
            var payment_date = node.find('.payment_date').val();
            var payment_amount = node.find('.payment_amount').val();
            var orig_payment_amount = node.find('.orig_payment_amount').val();
            var payment_type = node.find('.payment_type').val();
            var payment_reference = node.find('.payment_reference').val();

            swal({
                title: "Warning!",
                text: "Edit payment?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_submit_invoice_payment",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                invoice_payment_id: invoice_payment_id,
                                payment_date: payment_date,
                                payment_amount: payment_amount,
                                orig_payment_amount: orig_payment_amount,
                                payment_type: payment_type,
                                payment_reference: payment_reference
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Payment successfully updated",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('.btn_delete_payment').on('click',function(e){

            var invoice_payment_id = $(this).attr('data-payment_id');

            swal({
                title: "Warning!",
                text: "Delete payment?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_delete_invoice_payment",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                invoice_payment_id: invoice_payment_id
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Payment successfully deleted",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('#save_refund_details_btn').on('click',function(e){

            var node = $(this).parents('#add_refund_fb');
            var refund_date = node.find('#refund_date').val();
            var refund_amount = node.find('#refund_amount').val();
            var refund_type = node.find('#refund_type').val();
            var refund_reference = node.find('#refund_reference').val();

            swal({
                title: "Warning!",
                text: "Add refund?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_submit_invoice_refund",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                refund_date: refund_date,
                                refund_amount: refund_amount,
                                refund_type: refund_type,
                                refund_reference: refund_reference
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Refund successfully added",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('.edit_refund_details_btn').on('click',function(e){

            var node = $(this).parents('.edit_refund_fb');
            var invoice_refund_id = node.find('.invoice_refund_id').val();
            var refund_date = node.find('.refund_date').val();
            var refund_amount = node.find('.refund_amount').val();
            var orig_refund_amount = node.find('.orig_refund_amount').val();
            var refund_type = node.find('.refund_type').val();
            var refund_reference = node.find('.refund_reference').val();

            swal({
                title: "Warning!",
                text: "Edit refund?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_submit_invoice_refund",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                invoice_refund_id: invoice_refund_id,
                                refund_date: refund_date,
                                refund_amount: refund_amount,
                                orig_refund_amount: orig_refund_amount,
                                refund_type: refund_type,
                                refund_reference: refund_reference
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Refund successfully updated",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('.btn_delete_refund').on('click',function(e){
            e.preventDefault();

            var refund_payment_id = $(this).attr('data-refund_id');

            swal({
                title: "Warning!",
                text: "Delete refund?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_delete_refund_payment",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                refund_payment_id: refund_payment_id
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Refund successfully deleted",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('#save_credit_details_btn').on('click',function(e){

            var node = $(this).parents('#add_credits_fb');
            var credit_date = node.find('#credit_date').val();
            var credit_amount = node.find('#credit_amount').val();
            var credit_reason = node.find('#credit_reason').val();
            var credit_approved_by = node.find('#credit_approved_by').val();
            var credit_reference = node.find('#credit_reference').val();

            swal({
                title: "Warning!",
                text: "Add credit?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_save_credit_details",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                credit_date: credit_date,
                                credit_amount: credit_amount,
                                credit_reason: credit_reason,
                                credit_approved_by: credit_approved_by,
                                credit_reference: credit_reference,
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Credit successfully added",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('.update_credit_details_btn').on('click',function(e){

            var node = $(this).parents('.edit_credit_fb');
            var invoice_credit_id = node.find('.invoice_credit_id').val();
            var credit_date = node.find('.credit_date').val();
            var credit_amount = node.find('.credit_amount').val();
            var orig_credit_amount = node.find('.orig_credit_amount').val();
            var credit_reason = node.find('.credit_reason').val();
            var credit_approved_by = node.find('.credit_approved_by').val();
            var credit_reference = node.find('.credit_reference').val();

            swal({
                title: "Warning!",
                text: "Edit credit?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_save_credit_details",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                invoice_credit_id: invoice_credit_id,
                                credit_date: credit_date,
                                credit_amount: credit_amount,
                                orig_credit_amount: orig_credit_amount,
                                credit_reason: credit_reason,
                                credit_approved_by: credit_approved_by,
                                credit_reference: credit_reference
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Credit successfully updated",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        $('.btn_delete_credit').on('click',function(e){
            e.preventDefault();

            var invoice_credit_id = $(this).attr('data-invoice_credit_id');

            swal({
                title: "Warning!",
                text: "Delete credit?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_delete_credit_details",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                invoice_credit_id: invoice_credit_id
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Credit successfully deleted",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })
        })

        // unpaid marker
        jQuery("#unpaid_chk").click(function () {
            unpaid_check_action();
        });

        $('#btn_add_accounts_log').on('click',function(){

            var al_date = $('.al_date').val();
            var al_comment = $('.al_comment').val();

            swal({
                title: "Warning!",
                text: "Add Event?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel!",
                cancelButtonClass: "btn-danger",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
            },
            function(isConfirm) {

                if (isConfirm) { // yes

                        $('#load-screen').show(); //show loader
                        jQuery.ajax({
                            type: "POST",
                            url: "/jobs/ajax_add_job_account_logs",
                            dataType: 'json',
                            data: {
                                job_id: job_id,
                                al_date: al_date,
                                al_comment: al_comment
                            }

                        }).done(function( retval ) {
                            $('#load-screen').hide(); //hide loader
                            if(retval.status){

                                swal({
                                    title:"Success!",
                                    text: "Event successfully added",
                                    type: "success",
                                    showConfirmButton: <?php echo $this->config->item('showConfirmButton') ?>,
                                    timer: <?php echo $this->config->item('timer') ?>
                                });

                                var full_url = window.location.href;
                                setTimeout(function(){ window.location=full_url }, <?php echo $this->config->item('timer') ?>);
                            
                            }else{
                                if( retval.error!="" ){
                                    swal('Error',retval.error,'error');
                                }else{
                                    swal('Error','Internal error please contact admin','error');
                                }
                            }

                        });
                }

            })

        })
        

    })

</script>