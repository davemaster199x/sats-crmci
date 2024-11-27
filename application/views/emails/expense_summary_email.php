<?php $this->load->view('emails/template/email_header.php') ?>


<!-- CONTENT START HERE -->
<?Php echo $content; ?>

<p>Kind Regards,<br /><?=$this->config->item('company_name_short')?> Team</p>

<!-- CONTENT END HERE -->


<?php $this->load->view('emails/template/email_footer.php');