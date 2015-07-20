<?php

namespace Typed;

/**
 * Common methods for both TypedAbstract and TypedArray.
 *
 * @copyright  Copyright (c) 2015 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
trait TypedTrait
{
	final protected static function _isIndexedArray(array &$in)
	{
		return (array_values($in) === $in);
	}

	//	json_decode fails silently and an empty array is returned.
	final protected static function _jsonDecode(&$in)
	{
		$output = json_decode( $in, true );
		if ( !is_array($output) ) {
			return [];
		}
		return $output;
	}

	//	Empty array or object (no members) is false. Any property or index then true. (Like PHP 4)
	final protected static function _convertToBoolean(&$in)
	{
		switch (gettype($in)) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (double) $in->toArray();
			}
			return (boolean) (array) $in;

			case 'null':
			case 'NULL':
			return null;

			default:
			return (boolean) $in;
		}
	}

	//	Empty array or object (no members) is 0. Any property or index then 1 (Like PHP 4).
	final protected static function _convertToInteger(&$in)
	{
		switch ( gettype($in) ) {
			case 'string':
			return intval($in, 0);

			case 'object':
			return (integer) (array) $in;

			case 'null':
			case 'NULL':
			return null;

			default:
			return (integer) $in;
		}
	}

	//	Empty array or object (no members) is 0.0. Any property or index then 1.0. (Like PHP 4)
	final protected static function _convertToDouble(&$in)
	{
		switch ( gettype($in) ) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (double) $in->toArray();
			}
			return (double) (array) $in;

			case 'null':
			case 'NULL':
			return null;

			default:
			return (double) $in;
		}
	}

	//	Empty array or object (no members) is "". Any property or index then "1". (Like PHP 4)
	final protected static function _convertToString(&$in)
	{
		switch (gettype($in)) {
			case 'object':
			if ( method_exists($in, '__toString') ) {
				return $in->__toString();
			}
			elseif ( method_exists($in, 'format') ) {
				return $in->format('c');
			}
			elseif ( method_exists($in, 'toArray') ) {
				$in = $in->toArray();
			}
			//	fall through with new $in
			case 'array':
			return json_encode($in);

			case 'null':
			case 'NULL':
			return null;

			default:
			return (string) $in;
		}
	}

	final protected static function _convertToArray(&$in)
	{
		switch ( gettype($in) ) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return $in->toArray();
			}
			return (array) $in;

			default:
			return (array) $in;
		}
	}


	/**
	 * Returns JSON string representing the object.
	 * Optionally retruns a pretty-print string.
	 *
	 * @param bool $pretty -OPTIONAL
	 * @return string
	 */
	final public function toJson($pretty = false)
	{
		if ( !function_exists('json_encode') ) {
			throw new BadMethodCallException('json_encode must be available');
		}

		$j = json_encode( $this->toArray() );

		if ( !$pretty ) {
			return $j;
		}

		//	Pretty print from Zend/Json/Json.php v2.4.2.
        $tokens = preg_split('|([\{\}\]\[,])|', $j, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = "";
        $indent = 0;

        $inLiteral = false;
        foreach ($tokens as $token) {
            $token = trim($token);
            if ($token == "") {
                continue;
            }

            if (preg_match('/^("(?:.*)"):[ ]?(.*)$/', $token, $matches)) {
                $token = $matches[1] . ': ' . $matches[2];
            }

            $prefix = str_repeat("\t", $indent);
            if (!$inLiteral && ($token == "{" || $token == "[")) {
                $indent++;
                if ($result != "" && $result[strlen($result)-1] == "\n") {
                    $result .= $prefix;
                }
                $result .= "$token\n";
            }
            elseif (!$inLiteral && ($token == "}" || $token == "]")) {
                $indent--;
                $prefix = str_repeat("\t", $indent);
                $result .= "\n$prefix$token";
            }
            elseif (!$inLiteral && $token == ",") {
                $result .= "$token\n";
            }
            else {
                $result .= ($inLiteral ?  '' : $prefix) . $token;

                //remove escaped backslash sequences causing false positives in next check
                $token = str_replace('\\', '', $token);
                // Count # of unescaped double-quotes in token, subtract # of
                // escaped double-quotes and if the result is odd then we are
                // inside a string literal
                if ((substr_count($token, '"')-substr_count($token, '\\"')) % 2 != 0) {
                    $inLiteral = !$inLiteral;
                }
            }
        }
		return $result . "\n";
	}

}
