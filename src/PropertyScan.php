<?php

declare(strict_types=1);

namespace Carlin\LaravelDataSwagger;

use ReflectionClass;
use ReflectionException;
use Carlin\LaravelDataSwagger\Attributes\Property;


class PropertyScan
{
    private ReflectionClass $reflectionClass;

    /**
     * @throws ReflectionException
     */
    public function __construct(string $className)
    {
        $this->reflectionClass = new ReflectionClass($className);
    }

    /**
     * 获取所有必填属性
     *
     * @return array<string>
     */
	public function getRequiredProperties(): array
	{
		$properties = [];
		foreach ($this->reflectionClass->getProperties() as $property) {
			/** @var \ReflectionProperty $property */
			$reflectionAttributes = $property->getAttributes(Property::class);
			$isRequired = !$property->hasDefaultValue() && !($property->getType() && $property->getType()->allowsNull());
			!empty($reflectionAttributes) && $isRequired && $properties[] = $property->getName();
		}
		return $properties;
	}

    /**
     * 获取一个类的所有Property注解, 并返回Property对象数组
     *
     * @return array<int, Property>
     */
    public function getProperties(): array
    {
        $properties = [];
        foreach ($this->reflectionClass->getProperties() as $property) {
            $reflectionAttributes = $property->getAttributes(Property::class);
            foreach ($reflectionAttributes as $attribute) {
                $arguments = $attribute->getArguments();
                $arguments['property'] = $property->getName();
                $properties[] = new Property(...$arguments);
            }
        }
        return $properties;
    }
}
