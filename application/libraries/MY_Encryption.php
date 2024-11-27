<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Encryption extends CI_Encryption {

    protected $_key;
    protected $_cipher;
    
    protected $_mode;

    public function __construct($config = array())
    {
        parent::__construct($config);
      
        // it should be 32 characters
        $this->_key = 'satsversion2023Sj7qVxfo6Jft264uU';
        $this->_cipher = 'aes-256';
        $this->_mode = 'cbc';
        
        $this->initialize([
            'cipher' => $this->_cipher,
            'mode'  => $this->_mode,
            'key' => $this->_key
        ]);
        
        log_message('Info', 'MY_Encryption extends to CI_Encryption Class Initialized');
    }
}