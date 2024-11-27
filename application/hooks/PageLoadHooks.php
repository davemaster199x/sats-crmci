<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Carbon\Carbon;

class PageLoadHooks {

	const TABLE = 'session_logs';

	const DATE_FORMAT = 'Y-m-d H:i:s.u';

    protected $CI;
    protected $id;
    protected $session_id;

	/** @var int $user_id */
    protected $user_id;
    protected $user_ip;
    protected $user_agent;
    protected $request_method;
    protected $domain;
    protected $url;
    protected $query_string;
    protected $referrer;

	/** @var boolean $ajax */
	protected $ajax;

	/** @var float $seconds */
	protected $seconds;

	/** @var Carbon $pre_system */
    protected $pre_system;

	/** @var Carbon $pre_controller */
    protected $pre_controller;

	/** @var Carbon $post_controller_constructor */
    protected $post_controller_constructor;

	/** @var Carbon $post_controller */
    protected $post_controller;

	/** @var Carbon $post_system */
    protected $post_system;


	public function __construct()
	{
		$this->user_ip = $_SERVER['REMOTE_ADDR'];
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];

		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->domain = $_SERVER['HTTP_HOST'];
		$this->url =  parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$this->query_string =  $_SERVER["QUERY_STRING"];
		$this->referrer = $_SERVER['HTTP_REFERER'];

		$this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']);

	}

	public function pre_system()
	{
		$this->pre_system = Carbon::now();
	}

	public function pre_controller()
	{
		$this->pre_controller = Carbon::now();
	}

	public function post_controller_constructor()
	{

		$this->post_controller_constructor = Carbon::now();
		$this->CI =& get_instance();
		$this->CI->load->database();

		$this->session_id = session_id();
		$this->user_id = $_SESSION['staff_id'];

		$data = [
			'session_id'                  => session_id(),
			'user_id'                     => $this->user_id,
			'user_ip'                     => $this->user_ip,
			'user_agent'                  => $this->user_agent,
			'request_method'              => $this->request_method,
			'domain'                      => $this->domain,
			'url'                         => $this->url,
			'query_string'                => $this->query_string,
			'referrer'                    => $this->referrer,
			'pre_system'                  => $this->pre_system->format(self::DATE_FORMAT),
			'pre_controller'              => $this->pre_controller->format(self::DATE_FORMAT),
			'post_controller_constructor' => $this->post_controller_constructor->format(self::DATE_FORMAT),
			'ajax'                        => $this->ajax,
		];

		$this->CI->db->insert(self::TABLE, $data);
		$this->id = $this->CI->db->insert_id();

	}

	public function post_controller()
	{
		$this->post_controller = Carbon::now();
	}

	public function display_override()
	{

	}

	public function cache_override()
	{

	}

	public function post_system()
	{
		$this->post_system = Carbon::now();
		$this->seconds = number_format($this->pre_system->diffInMicroseconds($this->post_system) / 1000000, 4);

		$data = [
			'seconds' => $this->seconds,
			'post_controller' => $this->post_controller->format(self::DATE_FORMAT),
			'post_system' => $this->post_system->format(self::DATE_FORMAT),
		];

		$this->CI->db->where('id', $this->id)->update(self::TABLE, $data);
	}
}