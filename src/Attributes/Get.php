<?php



namespace Carlin\LaravelDataSwagger\Attributes;

use Attribute;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\RequestBody;
use Carlin\LaravelDataSwagger\Util;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Get extends \OpenApi\Attributes\Get
{
    public function __construct(string $controller, string $method, string $operationId = null, string $description = null, string $summary = null, array $security = null, array $servers = null, RequestBody $requestBody = null, array $tags = null, array $parameters = null, array $responses = null, array $callbacks = null, ExternalDocumentation $externalDocs = null, bool $deprecated = null, array $x = null, array $attachables = null)
    {
        $path = Util::getRouteByControllerAndMethod($controller, $method);
        parent::__construct($path, $operationId, $description, $summary, $security, $servers, $requestBody, $tags, $parameters, $responses, $callbacks, $externalDocs, $deprecated, $x, $attachables);
    }
}
