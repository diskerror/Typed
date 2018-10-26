<?php

/**
 * Convert all errors into ErrorExceptions
 */
set_error_handler(
	function($severity, $errstr, $errfile, $errline) {
		throw new ErrorException($errstr, 1, $severity, $errfile, $errline);
	},
	E_USER_ERROR
);

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
	//	Using STDOUT no longer works with PHPUnit.
	fwrite(STDERR, json_encode($in, JSON_PRETTY_PRINT) . "\n");
}

require_once __DIR__ . '/../vendor/autoload.php';
