# Laravel Data Swagger

Laravel对象注解生成swagger API 文档。依赖于l5-swagger拓展配置, 本项目只是简化生成文档的对象属性配置。

## 功能特性

- 支持自定义 API 响应格式
- 支持多文档配置
- 支持驼峰/下划线文档生成

## 安装

通过 Composer 安装:

```bash
composer require carlin/laravel-data-swagger
```

发布配置文件:

```bash
php artisan vendor:publish --provider="Carlin\LaravelDataSwagger\LaravelDataSwaggerServiceProvider"
```

## 配置

配置文件位于 `config/laravel-data-swagger.php`:

```php
return [
    'documentations' => [
        'default' => [
            // 某些场景后端是下划线命名，接收方是驼峰命名，需要定义了这个配置来兼容转换
            // 文档，响应格式和请求格式(true:驼峰/false:下划线)
            'is_camel' => true,
            
            // 后端对象属性格式(true:驼峰/false:下划线)
            'object_is_camel' => false,
            
            // 响应格式配置
            'response_format' => [
                // 基础响应字段
                'base_properties' => [
                    'state' => [
                        'field' => 'state',
                        'type' => 'string',
                        'description' => 'response code',
                        'example' => '000001'
                    ],
                    'msg' => [
                        'field' => 'msg',
                        'type' => 'string',
                        'description' => 'response message',
                        'example' => 'success'
                    ],
                    // 更多自定义字段...
                ],
                // 数据字段名
                'data_field' => 'data',
            ],
        ],
        // 可配置多个文档...
        // 'api_v2' => [
        //   
        //],
    ],
];
```

## 使用方法

### 文档属性
```php
<?php
use Carlin\LaravelDataSwagger\Attributes\Property;
use Carlin\LaravelDataSwagger\Attributes\Schema;
use OpenApi\Attributes\Items;

#[Schema(dtoClass: __CLASS__, title: '翻译文本', description: '')]
class LanguageResource
{
    #[Property(title: '翻译驱动', description: '翻译服务提供商', type: 'string', example: 'baidu', enumClass: Enums::class)]
    public string $drive;

    #[Property(title: '待翻译文本', type: 'string', example: '你好世界')]
    public string $query;

    #[Property(title: '目标语言', description: '目标语言代码', type: 'string', example: 'en')]
    public string $to;

    #[Property(title: '源语言', description: '默认auto自动检测', type: 'string', example: 'auto')]
    public string $from;

}


#[Schema(dtoClass: __CLASS__, title: '创建请求', description: '')]
class LanguageCreateRequest
{
    #[Property(title: '翻译驱动', description: '翻译服务提供商', type: 'string', example: 'baidu', enumClass: Enums::class)]
    public string $drive;

    #[Property(title: '待翻译文本', type: 'string', example: '你好世界')]
    public string $query;
    
    #[Property(title: '我是integer', description: '我是integer', type: 'integer', example: 1)]
    public string $integer;
    
    #[Property(title: '我是integer', description: '默认auto自动检测', type: 'boolean', example: 1)]
    public bool $boolean;
    
    #[Property(title: '我是number', description: '默认auto自动检测', type: 'number', example: 1)]
    public float $number;
    
    //请求是字符串数组
    #[Property(title: 'array_string', type: 'array', items: new Items(type: 'string'))]
    public ?array $array_string = [];
    
    //请求是integer数组
    #[Property(title: 'array_int', type: 'array', items: new Items(type: 'integer'))]
    public ?array $array_int = [];

    //请求是对象数组
    #[Property(title: 'actions', type: 'array', items: new Items(ref: FooActions::class))]
    public array $actions = [];

    //请求对象
    #[Property(ref: FooActions::class, title: 'action', type: 'object')]
    public FooActions $action;
}
```

### 基础响应

```php
use Carlin\LaravelDataSwagger\Attributes\Additional\BaseResource;
use Carlin\LaravelDataSwagger\Attributes\Additional\SuccessResponse;
use Carlin\LaravelDataSwagger\Attributes\Post;

#[Tag(self::TAG, description: '语言管理')]
class LanguageController
{
    public const TAG = '语言管理';

    #[Post(controller: self::class, method: __FUNCTION__, summary: '创建语言', tags: [self::TAG])]
    #[RequestBody(dtoClass: LanguageCreateRequest::class)]
    #[SuccessResponse(content: new BaseResource(LanguageResource::class))]
    public function create(UserRequest $request)
    {
        // ...
    }
}
```
```json
{
    "state": "000001",
    "msg": "success",
    "data": {
        "drive": "baidu",
        "query": "你好世界",
        "to": "en",
        "from": "auto"
    }
}
```

### 分页响应

```php
use Carlin\LaravelDataSwagger\Attributes\Additional\BaseResource;
use Carlin\LaravelDataSwagger\Attributes\Additional\PageResource;
use Carlin\LaravelDataSwagger\Attributes\Additional\SuccessResponse;
use Carlin\LaravelDataSwagger\Attributes\Post;

#[Tag(self::TAG, description: '语言管理')]
class LanguageController
{
    public const TAG = '语言管理';

    #[Post(controller: self::class, method: __FUNCTION__, summary: '获取语言列表', tags: [self::TAG])]
    #[RequestBody(dtoClass: LanguageListRequest::class)]
    #[SuccessResponse(content: new PageResource(LanguageResource::class))]
    public function list(LanguageListRequest $request)
    {
        // ...
    }
}
```
```json
{
    "state": "000001",
    "msg": "success",
    "data": {
        "list": [
            {
              "drive": "baidu",
              "query": "你好世界",
              "to": "en",
              "from": "auto"
            }
        ],
        "total": 100
    }
}
```

### 数组对象响应

```php
use Carlin\LaravelDataSwagger\Attributes\Additional\ArrayObjectResource;
use Carlin\LaravelDataSwagger\Attributes\Additional\SuccessResponse;
use Carlin\LaravelDataSwagger\Attributes\Post;

#[Tag(self::TAG, description: '语言管理')]
class LanguageController
{
    public const TAG = '语言管理';

    #[Post(controller: self::class, method: __FUNCTION__, summary: '获取语言列表', tags: [self::TAG])]
    #[RequestBody(dtoClass: LanguageListRequest::class)]
    #[SuccessResponse(content: new ArrayObjectResource(LanguageResource::class))]
    public function list(UserRequest $request)
    {
        // ...
    }
}
```
```json
{
    "state": "000001",
    "msg": "success",
    "data": [
        {
          "drive": "baidu",
          "query": "你好世界",
          "to": "en",
          "from": "auto"
        }
    ]
}
```

### 自定义响应
请阅读l5-swagger文档后，结合内置的ArrayObjectResource,BaseResource已提供的类，来自定义文档响应对象。

## 中间件
```php

添加中间件以支持响应格式化:
由于某些场景后端是下划线命名，前端是驼峰命名，所以需要在中间件中修改请求和响应的数据格式。
```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'api' => [
        \Carlin\LaravelDataSwagger\Middleware\FormatApiResponse::class,
    ],
];
```

## 自定义文档配置

可以为不同的 API 版本或模块配置不同的文档:

```php
use Carlin\LaravelDataSwagger\Attributes\Additional\BaseResource;

#[SuccessResponse(content: new BaseResource(LanguageResource::class, documentation: 'api_v2'))]
```

## 文档生成
- 生成所有文档
```
php artisan laravel-data-swagger:generate --all
```
- 生成指定文档
```
php artisan laravel-data-swagger:generate webapi
```


## 最佳实践项目请参考

## 贡献

欢迎提交 Issue 和 Pull Request。

## 许可证

本项目采用 MIT 许可证，详情请见 [LICENSE](LICENSE) 文件。
```
