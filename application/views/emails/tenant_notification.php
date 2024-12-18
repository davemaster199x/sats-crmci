<?php $this->load->view('emails/template/email_header.php') ?>


<!-- CONTENT START HERE -->


<p>Dear <?=$agency_name?>,</p>
<p>The Tenants at <?=$prop_address?> have now been notified that <?=$this->config->item('company_name_short')?> will be contacting them to book an appointment to service their property.</p>
<p>Any questions please feel free to contact us on <?=$agent_number?></p>
<br/>
            

<p>Kind Regards,<br /><?=$this->config->item('company_name_short')?> Team</p>

<!-- CONTENT END HERE -->


<?php $this->load->view('emails/template/email_footer.php');