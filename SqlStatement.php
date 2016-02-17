<?php

namespace Diskerror\Typed;

use InvalidArgumentException;

/**
 * Converts associative arrays and objects into partial SQL statements.
 *
 * @copyright  Copyright (c) 2015 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
class SqlStatement
{
    /**
	 * Holds the subject as an associative array for building queries.
	 * @type array
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
	public function setInput($in)
	{
	    if ( is_object($in) ) {
	        $this->_input =
				method_exists($in, 'toArray') ?
					$in->toArray() :
					(array) $in;
	    }
	    //	If is an array but not an indexed array...
	    elseif ( is_array($in) && array_values($in) !== $in ) {
	        $this->_input = $in;
	    }
	    else {
	        throw new InvalidArgumentException('input must be an associative array or an object');
	    }
	}

	/**
	 * Returns a string formatted for an SQL insert or update.
	 *
	 * Accepts an array where the values are the names of the desired properties.
	 * An empty array means to use all.
	 *
	 * @param array $include
	 * @return string
	 */
	public function getSqlInsert(array $include = [])
	{
	    if ( count($include) ) {
	        $arr = [];
	        foreach ( $include as $i ) {
	            if ( array_key_exists($i, $this->_input) ) {
	                $arr[$i] &= $this->_input[$i];
	            }
	        }
	    }
	    else {
	        $arr = &$this->_input;
	    }

	    $sqlStrs = [];
	    foreach ($arr as $k => &$v) {
	        $kEq = '`' . $k . '` = ';
	        switch ( gettype($v) ) {
				case 'bool':
				case 'boolean':
				$sqlStrs[] = $kEq . ( $v ? '1' : '0' );
				break;

				case 'int':
				case 'integer':
				case 'float':
				case 'double':
				$sqlStrs[] = $kEq . $v;
				break;

				case 'string':
				if ( $v === 'NULL' ) {
				    $sqlStrs[] = $kEq . 'NULL';
				}
				elseif ( $v === '' ) {
				    $sqlStrs[] = $kEq . '""';
				}
				else {
				    // 					$sqlStrs[] = $kEq . '"' . preg_replace('/([\x00\n\r\\\\\'"\x1a])/u', '\\\\$1', $v); . '"';
// 					$sqlStrs[] = $kEq . '"' . addslashes($v) . '"';
					$sqlStrs[] = $kEq . '0x' . bin2hex($v);
				}
				break;

				case 'null':
				case 'NULL':
				$sqlStrs[] = $kEq . 'NULL';
				break;

				case 'array':
				case 'object':
				$sqlStrs[] = $kEq . '0x' . bin2hex(json_encode($v));
				break;

				//	resource, just ignore these
				default:
				break;
			}
	    }

	    return implode(",\n", $sqlStrs);
	}

	/**
	 * Returns a string formatted for an SQL
	 * "ON DUPLICATE KEY UPDATE" statement.
	 *
	 * Accepts an array where the values are the names of the desired properties.
	 * An empty array means to use all.
	 *
	 * @param array $include
	 * @return string
	 */
	public function getSqlValues(array $include = [])
	{
	    $sqlStrs = [];

	    if ( count($include) ) {
	        foreach ($include as $i) {
	            if ( array_key_exists($i, $this->_input) ) {
	                $sqlStrs[] = '`' . $i . '` = VALUES(`' . $i . '`)';
	            }
	        }
	    }
	    else {
	        foreach ($this->_input as $k => &$v) {
	            $sqlStrs[] = '`' . $k . '` = VALUES(`' . $k . '`)';
	        }
	    }

	    return implode(",\n", $sqlStrs);
	}
}
