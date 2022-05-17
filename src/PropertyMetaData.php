<?php

namespace Diskerror\Typed;

use ErrorException;

/**
 * class PropertyMetaData
 *
 * @property string $type
 * @property bool $isObject
 * @property bool $isNullable
 * @property mixed $initValue
 */
final class PropertyMetaData
{
	private string $type;
	private bool   $isObject;
	private bool   $isNullable;
	private        $initValue;

	public function __construct(string $type, bool $isObject, bool $isNullable, $initialValue)
	{
		$this->type       = $type;
		$this->isObject   = $isObject;
		$this->isNullable = $isNullable;
		$this->initValue  = $initialValue;
	}

	/**
	 * The following methods are overridden with non-public properties so that they are read-only.
	 *
	 * @param $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->$name;
	}

	public function __set($name, $value)
	{
		throw new ErrorException('Cannot set property after it is created.');
	}

	public function __isset($name)
	{
		return isset($this->$name);
	}

	public function __unset($name)
	{
		throw new ErrorException('Cannot unset property after it is created.');
	}
}
