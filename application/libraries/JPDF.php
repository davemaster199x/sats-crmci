<?php

class JPDF extends FPDF {

    protected $CI;
    protected $base_path;
    protected $image_path;

	function __construct() {
		parent::__construct();
		$this->base_path = FCPATH . 'theme/pdf_templates/' . config_item('theme') . '/';
		$this->image_path = $this->base_path . 'images/';
	}

    function Header() {
        $this->Image($this->image_path . 'header-default.png', 150, 10, 50);
    }

    function Footer() {
		// No default footer for SAS
	    if (config_item('theme') == 'sas'){
		    return;
	    }

	    $this->Image($this->image_path . 'footer-default-' . config_item('cc') . '.png', 0, 273, 210);
    }

    function setCountryData($country_id) {
        $this->country_id = $country_id;
    }

}

