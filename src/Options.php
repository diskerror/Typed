<?php


namespace Diskerror\Typed;


/**
 * Defines behavior for getting and setting bitwise values.
 */
class Options
{
	/**
	 * @var int
	 */
	private $_options;

	/**
	 * @param int $opts
	 */
	public function __construct(int $opts = 0)
	{
		$this->_options = $opts;
	}

	/**
	 * @return int
	 */
	public function get(): int
	{
		return $this->_options;
	}

	/**
	 * @param int $opts
	 */
	public function set(int $opts)
	{
		$this->_options = $opts;
	}

	/**
	 * @param int $opts
	 */
	public function add(int $opts)
	{
		$this->_options |= $opts;
	}

	/**
	 * @param int $opt
	 *
	 * @return bool
	 */
	public function has(int $opt): bool
	{
		return ($this->_options & $opt);
	}
}
