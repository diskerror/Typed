<?php


namespace Diskerror\Typed;


/**
 * Defines behavior for getting and setting bitwise values.
 *
 * This looks like it could implement AtomicInterface but is more restricted and "unset" behaves differently.
 */
class BitWise
{
    /**
     * Number of bits is system dependent.
     * Everything is likely 64-bit by now.
     * @var int
     */
    private int $_bits;

    /**
     * @param int $bits
     */
    public function __construct(int $bits = 0)
    {
        $this->_bits = $bits;
    }

    /**
     * Returns integer of current options.
     * @return int
     */
    public function get(): int
    {
        return $this->_bits;
    }

    /**
     * Sets options to new value clearing all previous options.
     * @param int $bits
     */
    public function set(int $bits): void
    {
        $this->_bits = $bits;
    }

    /**
     * Checks if option or combination of options is set.
     * @param int $opt
     * @return bool
     */
    public function isset(int $opt): bool
    {
        return ($this->_bits & $opt);
    }

    /**
     * Unset individual or several bits.
     * Defaults to unset all bits.
     * @param int $bits
     */
    public function unset(int $bits = -1): void
    {
        $this->_bits &= ~$bits;
    }

    /**
     * Add option to current options.
     * @param int $bits
     */
    public function add(int $bits)
    {
        $this->_bits |= $bits;
    }
}
