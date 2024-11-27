<?php

class JPDI extends FPDI
{

	protected $CI;
	protected $dont_display_footer = 0;
	protected $dont_display_header = 0;
	protected $is_compliance_second_page_bg = 0;
	protected $is_compliance_second_page_bg_for_WE = 0;
	protected $_tplIdx;

	protected $is_new_invoice_template = 0;
	protected $is_new_combined_template = 0;
	protected $show_compliance_template = 0;
	protected $is_service_report_template = 0;
	protected $is_generic_template = 0;
	protected $en_pdf = 0;

    protected $base_path;
    protected $template_path;

	public function __construct()
	{
		parent:: __construct();
		$this->CI = &get_instance();

        $this->base_path = FCPATH . 'theme/pdf_templates/' . config_item('theme') . '/';

        if (config_item('theme') === 'sas'){
            $this->template_path = $this->base_path . '/';
        } else {
            if (config_item('country') == 1) {
                $this->template_path = $this->base_path . 'au/';
            } else {
                $this->template_path = $this->base_path . 'nz/';
            }
        }

	}

	/**
	 * TODO - THIS NEEDS WORK, ITS A MESS
	 * 4 Main Template Types
	 * is_service_report_template
	 * is_compliance_second_page_bg
	 * is_new_invoice_template
	 * is_new_combined_template
	 *
	 * Within each we check if there is an existing tplIDx, then check the theme
	 *
	 * TODO - Instead this should be organised into folders and we simply tweak the folder or filename being used
	 * /THEME/FILENAME.pdf
	 *
	 * @return void
	 */
	function Header()
	{
		if ($this->dont_display_header != 1) {
			if( $this->en_pdf == 1 ){ // for EN pdf only
				$this->Image(FCPATH.'theme/pdf_templates/'.config_item('theme').'/images/header-default.png', 150, 4, 45);
			}else{ // default
				$this->Image($_SERVER['DOCUMENT_ROOT'] . '/documents/inv_cert_pdf_header.png', 150, 10, 50);
			}			
		}

        if ($this->is_generic_template !== 0 || $this->is_generic_template > 1) {
            $this->setSourceFile($this->template_path . 'generic_letterhead.pdf');
            $this->_tplIdx = $this->importPage(1);
            $this->useTemplate($this->_tplIdx);
        }

        if ((!$this->is_generic_template || $this->is_generic_template == 0) && ($this->is_compliance_second_page_bg || $this->is_compliance_second_page_bg > 1)){
            $this->setSourceFile($this->template_path . 'letterHead_footer_only');
            $this->_tplIdx = $this->importPage(1);
            $this->useTemplate($this->_tplIdx);
        }

	}

	function Footer()
	{
		if ($this->dont_display_footer != 1) {

			if( $this->en_pdf == 1 ){ // for EN pdf only

				// Go to 1.5 cm from bottom
				$this->SetY(-10);
				$this->SetFont('Arial','',10);

				$country_id = config_item('country');

				// get postal code and agent number
				$country_params = array(
					'sel_query' => 'c.`tenant_number`, c.`postal_address`',
					'country_id' => $country_id
				);
				$country_sql = $this->CI->system_model->get_countries($country_params);
				$country_row = $country_sql->row();
				$tenant_number = $country_row->tenant_number;
				$postal_address = $country_row->postal_address;				
				
				$x_pos = $this->GetX();
				$footer_header = 0;
				$new_line = 0;
				$alignment = 'C';

				// PO box
				$this->setX($x_pos+15);
				$this->Cell(60,3, $postal_address,$footer_header,$new_line,$alignment);

				// base domain
				$x_pos = $this->GetX();
				$this->setX($x_pos+3);
				$this->Cell(60,3, config_item('base_domain'),$footer_header,$new_line,$alignment);

				// tenant number
				$x_pos = $this->GetX();
				$this->setX($x_pos+3);
				$this->Cell(60,3, $tenant_number,$footer_header,$new_line,$alignment);

			}else{ // default

				if ($this->CI->config->item('theme') == 'sas') {
					return;
				}
				
				if ($this->CI->config->item('country') == 1) { // AU
					$image = '/documents/inv_cert_pdf_footer_au.png';
				} else {
					if ($this->CI->config->item('country') == 2) { // NZ
						$image = '/documents/inv_cert_pdf_footer_nz.png';
					}
				}
	
				$this->Image($_SERVER['DOCUMENT_ROOT'] . $image, 0, 273, 210);

			}
			
		} else { // footer for pdf using template > display page count

			$this->AliasNbPages();
			// Go to 1.5 cm from bottom
			$this->SetY(-10);
			// Select Arial italic 8
			$this->SetFont('Arial', 'I', 8);
			// Print current and total page numbers
			$this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
		}
	}

	function setCountryData($country_id)
	{
		$this->country_id = $country_id;
	}

	function set_dont_display_footer($dont_display_footer)
	{
		$this->dont_display_footer = $dont_display_footer;
	}

	function set_dont_display_header($dont_display_header)
	{
		$this->dont_display_header = $dont_display_header;
	}

	function is_compliance_second_page_bg($is_compliance_second_page_bg)
	{
		$this->is_compliance_second_page_bg = $is_compliance_second_page_bg;
	}

	function is_compliance_second_page_bg_for_WE($is_compliance_second_page_bg_for_WE)
	{
		$this->is_compliance_second_page_bg_for_WE = $is_compliance_second_page_bg_for_WE;
	}

	function is_new_invoice_template($is_new_invoice_template)
	{
		$this->is_new_invoice_template = $is_new_invoice_template;
	}

	function is_new_combined_template($is_new_combined_template)
	{
		$this->is_new_combined_template = $is_new_combined_template;
	}

	function show_compliance_template($show_compliance_template)
	{
		$this->show_compliance_template = $show_compliance_template;
	}

	function show_service_report_template($is_service_report_template)
	{
		$this->is_service_report_template = $is_service_report_template;
	}

	function is_service_report_template($is_service_report_template)
	{
		$this->is_service_report_template = $is_service_report_template;
	}

    function set_generic_template($is_generic_template)
    {
        $this->is_generic_template = $is_generic_template;
    }

	function en_pdf($en_pdf)
	{
		$this->en_pdf = $en_pdf;
	}

	function TextWithDirection($x, $y, $txt, $direction = 'R')
	{
		if ($direction == 'R') {
			$s = sprintf(
				'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',
				1,
				0,
				0,
				1,
				$x * $this->k,
				($this->h - $y) * $this->k,
				$this->_escape($txt)
			);
		} elseif ($direction == 'L') {
			$s = sprintf(
				'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',
				-1,
				0,
				0,
				-1,
				$x * $this->k,
				($this->h - $y) * $this->k,
				$this->_escape($txt)
			);
		} elseif ($direction == 'U') {
			$s = sprintf(
				'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',
				0,
				1,
				-1,
				0,
				$x * $this->k,
				($this->h - $y) * $this->k,
				$this->_escape($txt)
			);
		} elseif ($direction == 'D') {
			$s = sprintf(
				'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',
				0,
				-1,
				1,
				0,
				$x * $this->k,
				($this->h - $y) * $this->k,
				$this->_escape($txt)
			);
		} else {
			$s = sprintf(
				'BT %.2F %.2F Td (%s) Tj ET',
				$x * $this->k,
				($this->h - $y) * $this->k,
				$this->_escape($txt)
			);
		}
		if ($this->ColorFlag) {
			$s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
		}

		$this->_out($s);
	}

	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle = 0)
	{
		$font_angle += 90 + $txt_angle;
		$txt_angle *= M_PI / 180;
		$font_angle *= M_PI / 180;

		$txt_dx = cos($txt_angle);
		$txt_dy = sin($txt_angle);
		$font_dx = cos($font_angle);
		$font_dy = sin($font_angle);

		$s = sprintf(
			'BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',
			$txt_dx,
			$txt_dy,
			$font_dx,
			$font_dy,
			$x * $this->k,
			($this->h - $y) * $this->k,
			$this->_escape($txt)
		);
		if ($this->ColorFlag) {
			$s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
		}
		$this->_out($s);
	}

	public function MultiAlignCell($w, $h, $text, $border = 0, $ln = 0, $align = 'L', $fill = false)
	{
		// Store reset values for (x,y) positions
		$x = $this->GetX() + $w;
		$y = $this->GetY();

		// Make a call to FPDF's MultiCell
		$this->MultiCell($w, $h, $text, $border, $align, $fill);

		// Reset the line position to the right, like in Cell
		if ($ln == 0) {
			$this->SetXY($x, $y);
		}
	}

	// Set the Draw color dynamic for SATS and SAS
	public function SetDrawColorTheme(): void
	{
		if (config_item('theme') === 'sats') {
			$this->SetDrawColor(180, 32, 37); //sats red
		} else {
			$this->SetDrawColor(0, 38, 50); //sas theme blue
		}
	}

	// Set the Text color dynamic for SATS and SAS
	public function SetTextColorTheme(): void
	{
		if (config_item('theme') === 'sats') {
			$this->SetTextColor(180, 32, 37); //sats red
		} else {
			$this->SetTextColor(0, 38, 50); // sas blue
		}
	}

    /**
     * @param $satsColor
     * @param $sasColor
     * @return void
     * accept rgb value as array
     */
    public function SetTextColorHeader(): void
    {
        if (config_item('theme') === 'sats') {
            $this->SetTextColor(255, 255, 255); // white
        } else {
            $this->SetTextColor(25, 96, 125); // sas blue
        }
    }

    /**
     * @param $headerTextTitle
     * @param $template (['report', 'invoice']) default value is report
     * this function overwrite the default template header text title
     */
    public function setHeaderTextTitle($headerTextTitle, string $template = 'report')
    {
        if ($headerTextTitle !== ''){

            if (config_item('theme') === 'sas'){

                $this->SetFillColor(0, 96, 128); // RGB color for blue

                // Create a filled rectangle for the header background
                $this->Rect(100, 0, 110, 26, 'F');
                // Set text color to white
                $this->SetTextColor(255, 255, 255);

                // Title
                if ($template === 'report'){
                    $this->SetY(12); // Adjust the vertical position for text
                    $headerTextAlign = 'R';
                } else {
                    $this->SetY(10); // Adjust the vertical position for text
                    $headerTextAlign = 'L';
                }

                $this->SETX(110);
                $this->Cell(30); // Move to the right
                $this->SetFont('Arial', 'B', 15); // Set font for the header text
                $this->Cell(0, 5, $headerTextTitle, 0, 0, $headerTextAlign); // Adjust the text and alignment
                $this->Ln(20);

                $this->SetFont('Arial','',10);
                $this->SetTextColor(0, 0, 0);
            } else {

                $this->SetFillColor(180, 32, 37); // RGB color for red

                // Create a filled rectangle for the header background
                $this->Rect(100, 0, 110, 28.8, 'F');
                // Set text color to white
                $this->SetTextColor(255, 255, 255);

                // Title
                if ($template === 'report'){
                    $this->SetY(16); // Adjust the vertical position for text
                    $headerTextAlign = 'R';
                } else {
                    $this->SetY(14); // Adjust the vertical position for text
                    $headerTextAlign = 'L';
                }

                $this->SETX(110);
                $this->Cell(30); // Move to the right
                $this->SetFont('Arial', 'B', 18); // Set font for the header text
                $this->Cell(0, 5, $headerTextTitle, 0, 0, $headerTextAlign); // Adjust the text and alignment
                $this->Ln(23);

                $this->SetFont('Arial','',10);
                $this->SetTextColor(0, 0, 0);
            }
        }
    }
}

