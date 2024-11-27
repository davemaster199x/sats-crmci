<?php

// DO NOT UNDER ANY CIRCUMSTANCES ALLOW PASSTHRU OF WHAT AN ATTACKER CAN TYPE IN THE URL
// If you value your job, you will not make light of this file

	function getHtmlTemplate($output){
		return '<!doctype html>
<html class="no-js" lang="">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SATS/SAS Dev Tools</title>
<style>
.console {
background-color:#000;
color: #FFF;
font-family: monospace;
}
</style>
</head>
<body>
<h1>SATS/SAS Dev Tools</h1>
<div class="console">
' . $output . '
</div>
</body>
</html>';
	}

	function getOutput($commands){
		if(strpos($commands[0], 'git')){
			$newline = PHP_EOL;
		} else {
			$newline = '<br>';
		}

		$output = '';
		foreach($commands as $key => $command){
			$exitcode = 0;
			$result = [];
			$output .= '[user@server public_html]$ ' . $command . $newline;

			exec($command . ' 2>&1', $result,$exitcode);
			$output .= "Exit Code: " . $exitcode . $newline;
			if($newline != PHP_EOL){
				$output .= "<pre>";
			}

			foreach ($result as $line) {
				$output .= $line . $newline;
			}

		if($newline != PHP_EOL){
			$output .= "</pre>";
		}

			// wait 3 seconds between each command
			if(!empty($commands[$key+1])){
				sleep(1);
			}
		}

		saveToLog($output);

		// if html then use html template
		if($newline != PHP_EOL){
			$output = getHtmlTemplate($output);
		}




		return $output;
	}

	function saveToLog($msg){

		$path = $_SERVER['DOCUMENT_ROOT'] . '/application/logs/log-' . date('Y-m-d') . '.php';
		$log_prefix = 'ERROR - ' . date('Y-m-d H:i:s') . ' --> AUTO-PULL OUTPUT' . PHP_EOL;

		$log = $log_prefix . $msg . PHP_EOL . PHP_EOL . PHP_EOL;
		error_log($log, 3, $path);
	}

/////////////////////////////////////////////////////////////////////////////////
// Script starts below


// by default this url will do git reset and pull, or perform other commands sa per below
	$commands = [
		"git reset --hard HEAD",
		"git pull",
	];


// add ?composer=1 to run composer as it wont be always needed
	if(!empty($_GET['composer'])) {
		switch($_GET['composer']){
			case 'update':
				$composer = 'update';
				break;
			default:
				$composer = 'install';
				break;
		}
		$commands = [
			'/usr/local/bin/ea-php73 /opt/cpanel/composer/bin/composer ' . $composer
		];
	}

// overwrite if ?phinx= is set
	if(!empty($_GET['phinx'])) {
		switch($_GET['phinx']){
			case 'migrate':
				$phinx = 'migrate';
				break;
			default:
				$phinx = 'status';
				break;
		}
		$commands = [
			'vendor/bin/phinx --configuration="' . $_SERVER['DOCUMENT_ROOT'] . '/phinx.php" ' . $phinx
		];
	}

	echo getOutput($commands);