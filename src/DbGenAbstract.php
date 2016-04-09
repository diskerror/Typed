<?php
/**
 * SQL statement generator.
 * @name		SqlStatement
 * @copyright	Copyright (c) 2015 Reid Woodbury Jr.
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use InvalidArgumentException;

/**
 * Abstract class for converting associative arrays and objects into
 *   something more useful for a database.
 */
abstract class DbGenAbstract
{
    /**
	 * Holds the subject as an associative array for building queries.
	 * @var array
	 */
	protected $_input;

	/**
	 * Constructor.
	 * Accepts an object or an associative array.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	public function __construct($in = null)
	{
	    if ( null !== $in ) {
	        $this->setInput($in);
	    }
	}

	/**
	 * Disallow clone.
	 */
	private function __clone()
	{
	}


	/**
	 * Accepts an object or an associative array.
	 *
	 * @param mixed $in
	 * @throws InvalidArgumentException
	 */
	abstract public function setInput($in);

}
