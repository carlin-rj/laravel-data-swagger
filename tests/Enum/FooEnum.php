<?php

declare(strict_types=1);

namespace Tests\Enum;



use Carlin\LaravelDict\Attributes\EnumClass;
use Carlin\LaravelDict\Attributes\EnumProperty;

#[EnumClass(name: 'foo-enum')]
class FooEnum
{
    #[EnumProperty(description: 'a-description')]
    public const A = 1;

    #[EnumProperty(description: 'b-description')]
    public const B = 2;


}
