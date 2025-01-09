<?php



namespace Carlin\LaravelDataSwagger\Attributes\Additional;

use Attribute;
use OpenApi\Attributes\Attachable;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\XmlContent;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class SuccessResponse extends Response
{
    public function __construct(
        string|object $ref = null,
        array $headers = null,
        MediaType|JsonContent|XmlContent|Attachable|array $content = null,
        array $links = null,
        // annotation
        array $x = null,
        array $attachables = null
    ) {
        parent::__construct(
            ref: $ref,
            response: 200,
            description: 'success',
            headers: $headers,
            content: $content,
            links: $links,
            x: $x,
            attachables: $attachables
        );
    }
}
