<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class FpdfLoader
{
    public function __construct(){
      require APPPATH . '../inc/fpdf/fpdf.php';	
      require APPPATH . '../inc/fpdi-1.4.4/fpdi.php';
    }
}
