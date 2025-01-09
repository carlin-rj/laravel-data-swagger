<?php



namespace Carlin\LaravelDataSwagger\Attributes;


use Attribute;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Attachable;
use OpenApi\Attributes\Discriminator;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Xml;
use OpenApi\Generator;
use UnitEnum;
use Carlin\LaravelDataSwagger\EnumPropertyCollect;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
class Property extends \OpenApi\Attributes\Property
{
    /**
     * @param \OpenApi\Attributes\Property[]                 $properties
     * @param null                                           $maximum
     * @param null                                           $minimum
     * @param class-string|float[]|int[]|string[]|UnitEnum[] $enum
     * @param array<\OpenApi\Annotations\Schema|Schema>      $allOf
     * @param array<\OpenApi\Annotations\Schema|Schema>      $anyOf
     * @param array<\OpenApi\Annotations\Schema|Schema>      $oneOf
     * @param null|array<string,mixed>                       $x
     * @param null|Attachable[]                              $attachables
     *
     */
    public function __construct(
        string $property = null,
        // schema
        string|object $ref = null,
        string $schema = null,
        string $title = null,
        string $description = null,
        int $maxProperties = null,
        int $minProperties = null,
        array $properties = null,
        string $type = null,
        string $format = null,
        Items $items = null,
        string $collectionFormat = null,
        mixed $default = Generator::UNDEFINED,
        $maximum = null,
        bool $exclusiveMaximum = null,
        $minimum = null,
        bool $exclusiveMinimum = null,
        int $maxLength = null,
        int $minLength = null,
        int $maxItems = null,
        int $minItems = null,
        bool $uniqueItems = null,
        string $pattern = null,
        array|string $enum = null,
        Discriminator $discriminator = null,
        bool $readOnly = null,
        bool $writeOnly = null,
        Xml $xml = null,
        ExternalDocumentation $externalDocs = null,
        mixed $example = Generator::UNDEFINED,
        bool $nullable = null,
        bool $deprecated = null,
        array $allOf = null,
        array $anyOf = null,
        array $oneOf = null,
        AdditionalProperties|bool $additionalProperties = null,
        // annotation
        array $x = null,
        array $attachables = null,
		string $enumClass = null,
    ) {
		if ($enumClass) {
			$enumCollect = EnumPropertyCollect::collect($enumClass);
			$description = $enumCollect->getDescriptions() . '。' . $description;
			$enum = $enumCollect->getValues();
		}
        parent::__construct(
            property: $property,
            ref: $ref,
            schema: $schema,
            title: $title,
            description: $description,
            maxProperties: $maxProperties,
            minProperties: $minProperties,
            required: [],
            properties: $properties,
            type: $type,
            format: $format,
            items: $items,
            collectionFormat: $collectionFormat,
            default: $default,
            maximum: $maximum,
            exclusiveMaximum: $exclusiveMaximum,
            minimum: $minimum,
            exclusiveMinimum: $exclusiveMinimum,
            maxLength: $maxLength,
            minLength: $minLength,
            maxItems: $maxItems,
            minItems: $minItems,
            uniqueItems: $uniqueItems,
            pattern: $pattern,
            enum: $enum,
            discriminator: $discriminator,
            readOnly: $readOnly,
            writeOnly: $writeOnly,
            xml: $xml,
            externalDocs: $externalDocs,
            example: $example,
            nullable: $nullable,
            deprecated: $deprecated,
            allOf: $allOf,
            anyOf: $anyOf,
            oneOf: $oneOf,
            additionalProperties: $additionalProperties,
            x: $x,
            attachables: $attachables);
    }
}
