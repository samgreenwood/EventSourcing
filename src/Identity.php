<?php

namespace SamGreenwood\EventSourcing;

use Ramsey\Uuid\Uuid;

abstract class Identity
{
    /**
     * @var string
     */
    private $id;

    /**
     * ProductId constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @param $string
     *
     * @return Identity
     */
    public static function fromString(string $string) : Identity
    {
        return new static($string);
    }

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->id;
    }

    /**
     * Compares the object to another Identity object. Returns true if both have the same type and value.
     *
     * @param $other
     *
     * @return bool
     */
    public function equals(Identity $other)
    {
        return $other instanceof self && $other->id == $this->id;
    }

    /**
     * Generate a new ID.
     *
     * @return IdentifiesAggregate
     */
    public static function generate()
    {
        return new static(Uuid::uuid4()->toString());
    }
}
