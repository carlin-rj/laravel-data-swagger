<?php



namespace Carlin\LaravelDataSwagger\Attributes\Additional;

use Attribute;
use Carlin\LaravelDataSwagger\Attributes\Property;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;

#[Attribute(Attribute::TARGET_METHOD)]
class ArrayObjectResource extends JsonContent
{
    public function __construct(string $dtoClass, ?string $documentation = 'default')
    {
		$properties = BaseResource::getBaseProperties($documentation);
		// 从配置获取数据字段名
		$dataField = config("laravel-data-swagger.documentations.{$documentation}.response_format.data_field", 'data');
		$properties[] = new Property(
			property: $dataField,
			ref: $dtoClass,
			type: 'array',
			items: new Items(ref:$dtoClass),
		);
		parent::__construct(properties: $properties);
	}
}
