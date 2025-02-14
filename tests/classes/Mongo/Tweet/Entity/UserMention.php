<?php

namespace TestClasses\Mongo\Tweet\Entity;

use Diskerror\Typed\BSON\{TypedArray, TypedClass};
use Diskerror\Typed\Scalar\TInteger;


class UserMention extends TypedClass
{
    public ?string       $id          = '';
    public string        $screen_name = '';
    public string        $name        = '';
    protected TypedArray $indices;

    public function __construct($in = null)
    {
        $this->indices = new TypedArray(TInteger::class);
        parent::__construct($in);
    }
}
