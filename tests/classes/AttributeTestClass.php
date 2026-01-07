<?php

namespace TestClasses;

use Diskerror\Typed\AtMap;
use Diskerror\Typed\TypedClass;

class AttributeTestClass extends TypedClass
{
    #[AtMap('user_id')]
    protected int $userId;

    #[AtMap('user_name')]
    protected string $userName;

    // Mixed usage: Defined in array, but should be mergeable or coexist if logic allows
    protected array $_map = [
        'user_email' => 'email'
    ];

    protected string $email;
}
