<?php
    
use Hashids\Hashids;

class HashEncryption {

	private static $hashIds;
	private static $salt;
	private static $minLength = 9;
	private static $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    private static function init() {
	    self::$salt = config_item('hash_salt');

		self::$hashIds = new Hashids(self::$salt, self::$minLength, self::$alphabet);
        log_message('Info', 'HashEncryption Class Initialized');
    }
    
    /**
     * Encode String
     * @param string $string EG: $job_id will get turned into a random hash for url
     * @return string
     */
    public static function encodeString($string): string {
		self::init();

        return self::$hashIds->encode($string);
    }
    
    /**
     * Decode String
     * @param $hash
     * @return string
     */
    public static function decodeString($hash): string {
	    self::init();
        $decoded = self::$hashIds->decode($hash);
        return $decoded ? $decoded[0] : '';
    }
}
