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
	private bool   $isPublic;
	private bool   $isaAtomicInterface;
	private bool   $isaTypedAbstract;
	private bool   $isaDateTimeInterface;
	private bool   $hasToArray;
	private bool   $hasJsonSerialize;
	private bool   $hasToString;

	public function __construct(string $type,
								bool   $isObject,
								bool   $isNullable,
								bool   $isPublic,
								bool   $isaAtomicInterface,
								bool   $isaTypedAbstract,
								bool   $isaDateTimeInterface,
								bool   $hasToArray,
								bool   $hasJsonSerialize,
								bool   $hasToString)
	{
		$this->type                 = $type;
		$this->isObject             = $isObject;
		$this->isNullable           = $isNullable;
		$this->isPublic             = $isPublic;
		$this->isaAtomicInterface   = $isaAtomicInterface;
		$this->isaTypedAbstract     = $isaTypedAbstract;
		$this->isaDateTimeInterface = $isaDateTimeInterface;
		$this->hasToArray           = $hasToArray;
		$this->hasJsonSerialize     = $hasJsonSerialize;
		$this->hasToString          = $hasToString;
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
