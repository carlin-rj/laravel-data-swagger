<?php



namespace Carlin\LaravelDataSwagger\Attributes\Additional;

use Attribute;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use Carlin\LaravelDataSwagger\Attributes\Property;

#[Attribute(Attribute::TARGET_METHOD)]
class PageResource extends JsonContent
{
    public function __construct(string $dtoClass, ?string $documentation = 'default')
    {
        $properties = BaseResource::getBaseProperties($documentation);
		$dataField = config("laravel-data-swagger.documentations.{$documentation}.response_format.data_field", 'data');
		$properties[] = new Property(property: $dataField, properties: [
            new Property(property: 'list', title: '列表', type: 'array', items: new Items(ref: $dtoClass)),
            new Property(property: 'total', description: '总数量', type: 'integer'),
        ], type: 'object');
        parent::__construct(properties: $properties);
    }
}
