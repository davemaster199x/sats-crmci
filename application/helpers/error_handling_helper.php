<?php


if (!function_exists('image_check')) {
    /**
     * Helper function to check if image exists and return a placeholder if not
     * @param $filepath
     * @param $placeholder_path
     * @return false|mixed|string
     */
    function image_check($filepath, $placeholder_path=null){
        if(empty($filepath) || !is_string($filepath)){
            return false;
        }

        if (file_exists($filepath) && @getimagesize($filepath) !== false) {
            return $filepath;
        }

        //Return placeholder path if provided, otherwise return an empty string
        return $placeholder_path !== null ? $placeholder_path : '';
    }
}