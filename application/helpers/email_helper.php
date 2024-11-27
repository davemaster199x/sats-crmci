<?php

if ( ! function_exists('make_email'))
{

    function make_email(string $role): string
    {
        $CI =& get_instance();

        $domain = $CI->config->item('base_domain');

        return $role . '@' . $domain;
    }
}