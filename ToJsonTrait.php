<?php

namespace Typed;

/**
 * Common toJson method for both TypedAbstract and TypedArray.
 *
 * @copyright  Copyright (c) 2015 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
trait ToJsonTrait
{

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
