<?php

declare(strict_types=1);

namespace Tests\Feature;

use Carlin\LaravelDataSwagger\EnumPropertyCollect;
use ReflectionException;
use Tests\Enum\FooEnum;
use Tests\Enum\PhpEnum;
use Tests\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class EnumTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testGetDescription()
    {
        $result = EnumPropertyCollect::collect(FooEnum::class);
        $this->assertEquals('foo-enum(1:a-description;2:b-description)', $result->getDescriptions());
    }

	public function testPhpEnum()
	{
		$result = EnumPropertyCollect::collect(PhpEnum::class);
		$this->assertEquals('PhpEnum(a:test;c:test2)', $result->getDescriptions());
	}
}
