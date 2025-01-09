<?php

namespace Carlin\LaravelDataSwagger\Attributes\Additional;

use Attribute;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Generator;
use Carlin\LaravelDataSwagger\Attributes\Property;

#[Attribute(Attribute::TARGET_METHOD)]
class BaseResource extends JsonContent
{
	public function __construct(
		string|object|null $dtoClass = null,
		?string $type = null,
		Items|null $items = null,
		?string $description = '数据',
		mixed $example = Generator::UNDEFINED,
		array $dataProperties = null,
		?string $documentation = 'default'
	) {
		$properties = static::getBaseProperties($documentation);

		// 从配置获取数据字段名
		$dataField = config("laravel-data-swagger.documentations.{$documentation}.response_format.data_field", 'data');

		$properties[] = new Property(
			property: $dataField,
			ref: $dtoClass,
			description: $description,
			properties: $dataProperties,
			type: $type,
			items: $items,
			example: $example
		);

		parent::__construct(properties: $properties);
	}

	public static function getBaseProperties(string $documentation = 'default'): array
	{
		$properties = [];
		$baseProps = config("laravel-data-swagger.documentations.{$documentation}.response_format.base_properties", []);

		foreach ($baseProps as $prop) {
			$properties[] = new Property(
				property: $prop['field'], description: $prop['description'], type: $prop['type'], enum: $prop['enum'] ?? null, example: $prop['example']
			);
		}

		return $properties;
	}
}
