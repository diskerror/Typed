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


function jsonPrint($in)
{
	fwrite(STDERR, json_encode($in, JSON_PRETTY_PRINT) . PHP_EOL);
}

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('UTC');
