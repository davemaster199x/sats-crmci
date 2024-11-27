<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logrotation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

    public function index() {
        $log_path = APPPATH . 'logs/';

        if (!is_dir($log_path) || !is_writable($log_path)) {
            show_error('Logs directory is not accessible.');
            return;
        }

        $log_files = get_filenames($log_path);

        if (empty($log_files)) {
            // No log files found, no further action needed
            return;
        }
        
        // Define the maximum age (in seconds) for log files (1 week)
        $max_age = 7 * 24 * 60 * 60;

        foreach ($log_files as $file) {

            $file_path = $log_path . $file;
            $file_age = time() - filemtime($file_path);

            if ($file_age > $max_age) {
                // If the file is older than 1 week, delete it
                unlink($file_path);
            }
        }
    }
}