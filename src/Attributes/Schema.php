<?php declare(strict_types=1);
namespace  Carlin\LaravelDataSwagger\Attributes;

use Carlin\LaravelDataSwagger\PropertyScan;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Schema extends \OpenApi\Attributes\Schema
{
    public function __construct(
        string $dtoClass,
        ?string $title = null,
        ?string $description = null,

    ) {
		$required = (new PropertyScan($dtoClass))->getRequiredProperties();
		$schema = str_replace('\\', '', $dtoClass);
		parent::__construct(schema:$schema, title:$title, description:$description, required: $required);
    }
}
