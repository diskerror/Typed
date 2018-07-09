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

require_once __DIR__ . '/../vendor/autoload.php';
