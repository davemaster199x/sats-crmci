<?php

class MY_Log extends CI_Log {


	public function write_log($level, $msg)
	{
		$backtrace = array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 2);

		if($level == 'error') {
			$divider =  PHP_EOL . '=====================================================' . PHP_EOL;

			$backtrace_string = 'STACK TRACE' . PHP_EOL;

			foreach ($backtrace as $bt) {
				$backtrace_string .= $bt['file'] . ':' . $bt['line'] . ' | ';
				if (!empty($bt['class'])) {
					$backtrace_string .= $bt['class'] . '->';
				}
				$backtrace_string .= $bt['function'] . PHP_EOL;
			}

			$msg = $msg . $divider . $backtrace_string . PHP_EOL . PHP_EOL;
		}

		parent::write_log($level, $msg);
	}
}