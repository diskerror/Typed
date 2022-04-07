#!/opt/local/bin/php
<?php
/**
 * Scratch space for testing.
 */

use TestClasses\Tweet;
include "tests/bootstrap.php";

$tweet = new Tweet();

$serialized = serialize($tweet);

$unserialized = unserialize($serialized);

jsonPrint($serialized);
jsonPrint($unserialized);
