<?php

declare(strict_types=1);

namespace Carlin\LaravelDataSwagger;

use Carlin\LaravelDict\Attributes\EnumClass;
use Carlin\LaravelDict\Attributes\EnumProperty;
use ReflectionClass;
use ReflectionException;

class EnumPropertyCollect
{
    private array $map;

    private string $enumName = '';

    /**
     * @throws ReflectionException
     */
    public static function collect(string $class): EnumPropertyCollect
    {

        $map = [];
        $reflectionClass = new ReflectionClass($class);
		foreach ($reflectionClass->getReflectionConstants() as $constant) {
			$attributes = $constant->getAttributes(EnumProperty::class);
			foreach ($attributes as $attribute) {
				//可能是枚举
				$value = $constant->getValue()?->name ?? $constant->getValue();
				/** @var EnumProperty $enumAttribute */
				$enumAttribute = $attribute->newInstance();
				$map[$value] = $enumAttribute->description;
			}
		}

		empty($map) && self::setMapByDescriptions($reflectionClass, $map);

        $instance = new self();
        $instance->map = $map;
        $instance->collectClassAttribute($reflectionClass);

        return $instance;
    }

	private static function setMapByDescriptions($reflectionClass, array &$map): void
	{
		if($reflectionClass->hasMethod('descriptions') && $reflectionClass->getMethod('descriptions')->isStatic()) {
			$method = $reflectionClass->getMethod('descriptions');
			$result = $method->invoke($reflectionClass);  // 执行方法
			foreach ($result as $key => $value) {
				$map[$key] = $value;
			}
		}
	}


    public function getEnumName(): string
    {
        return $this->enumName;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function getValues(): array
    {
        return array_keys($this->map);
    }

    public function getDescriptions(): string
    {
        $result = [];
        foreach ($this->map as $value => $description) {
            $result[] = $value . ':' . $description;
        }
        if ($this->enumName) {
            return $this->enumName . '(' . implode(';', $result) . ')';
        }

        return implode(';', $result);
    }

    private function collectClassAttribute(ReflectionClass $reflectionClass): void
    {
        $attributes = $reflectionClass->getAttributes(EnumClass::class);
        if (0 !== count($attributes)) {
			/** @var EnumClass $instance */
			$instance = $attributes[0]->newInstance();
			$this->enumName = $instance->name;
        } else {
			$this->enumName = $reflectionClass->getShortName();
		}

    }
}
