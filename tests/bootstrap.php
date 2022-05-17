<?php

/**
 * Set handler for uncaught exceptions.
 */
set_exception_handler(
	function(Throwable $t) {
		fwrite(STDERR, $t->getMessage() . PHP_EOL);
		fwrite(STDERR, $t . PHP_EOL);
		exit($t->getCode());
	}
);


function tprint($in)
{
	if (is_scalar($in)) {
		fwrite(STDERR, $in . PHP_EOL);
	}
	else {
		fwrite(STDERR, json_encode($in, JSON_PRETTY_PRINT) . PHP_EOL);
//		fwrite(STDERR, var_export($in, true) . PHP_EOL);
	}
}

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit', 128 * 1024 * 1024);
date_default_timezone_set('UTC');
