<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = [
    'protocol' 	=> $_ENV['EMAIL_PROTOCOL'] ?? 'mail',
    'smtp_host' => $_ENV['SMTP_HOST'] ?? 'localhost',
    'smtp_port' => $_ENV['SMTP_PORT'] ?? '25',
    'smtp_user' => $_ENV['SMTP_USER'] ?? '',
    'smtp_pass' => $_ENV['SMTP_PASS'] ?? '',
    'mailtype'  => 'html',
    'charset' => 'utf-8',
    'crlf' => "\r\n",
    'newline' => "\r\n"
];