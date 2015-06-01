#!/usr/bin/php
<?php

set_error_handler( function ($errno, $errstr, $errfile, $errline) {
	throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
} );

//	Use this for uncaught exceptions so we can see all the useful details.
set_exception_handler( function (Exception $e) {
	while ( ob_get_level() ) {
		ob_end_flush();
	}
	
	fwrite(STDERR, "\n" . $e . "\n");
	exit($e->getCode());
} );


/**
* Write to command line "standard output" channel.
* Requires at least one argument.
*/
function cprintf()
{
	$args = func_get_args();
	if ( count($args) < 2 ) {
		fwrite(STDOUT, $args[0]);
	}
	else {
		vfprintf(STDOUT, array_shift($args), $args);
	}
}

/**
 * Wrapper for print_r to direct output to standard output channel.
 */
function pr($d)
{
	fwrite(STDOUT, print_r( $d, true ));
}

////////////////////////////////////////////////////////////////////////////////////////////////
//	This file is shamefully empty.
