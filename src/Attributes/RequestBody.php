<?php



namespace Carlin\LaravelDataSwagger\Attributes;

use Attribute;
use OpenApi\Attributes\Attachable;
use OpenApi\Attributes\JsonContent;
use Carlin\LaravelDataSwagger\PropertyScan;

#[Attribute(Attribute::TARGET_METHOD)]
class RequestBody extends \OpenApi\Attributes\RequestBody
{
    /**
     * @param null|object|string       $ref
     * @param null|array<string,mixed> $x
     * @param null|Attachable[]        $attachables
     */
    public function __construct(
        string $dtoClass,
        string|object $ref = null,
        string $request = null,
        string $description = null,
        bool $required = null,
        // annotation
        array $x = null,
        array $attachables = null,
    ) {
        $content = new class($dtoClass) extends JsonContent
        {
            public function __construct(string $dtoClass)
            {
                $propertyScan = new PropertyScan($dtoClass);
                parent::__construct(required: $propertyScan->getRequiredProperties(), properties: $propertyScan->getProperties());
            }
        };
        parent::__construct(
            ref: $ref,
            request: $request,
            description: $description,
            required: $required,
            content: $content,
            x: $x,
            attachables: $attachables
        );
    }
}
