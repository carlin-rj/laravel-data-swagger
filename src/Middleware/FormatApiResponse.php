<?php

namespace Carlin\LaravelDataSwagger\Middleware;
use Closure;
use Illuminate\Http\Request;
use Carlin\LaravelDataSwagger\Helper\Arr;
use Carlin\LaravelDataSwagger\ResponseFactory;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;


class FormatApiResponse
{
    public function handle(Request $request, Closure $next, string $documentation = 'default')
    {
		//替换ResponseFactoryContract替换json方法并且设置驼峰还是下划线
		app()->singleton(ResponseFactoryContract::class, function ($app) use ($documentation) {
			return (new ResponseFactory($app[ViewFactoryContract::class], $app['redirect']))->setDocumentation($documentation);
		});

        // 格式化请求数据
        $requestData = $request->all();
		//如果object对象是驼峰格式并且配置文件中配置了请求对象下划线
		if (config("laravel-data-swagger.documentations.{$documentation}.object_is_camel") && !config("laravel-data-swagger.documentations.{$documentation}.is_camel")) {
			$request->replace(Arr::snakeToCamel($requestData));
		}

		//如果object对象是下划线并且配置文件中配置了请求对象驼峰
		if (!config("laravel-data-swagger.documentations.{$documentation}.object_is_camel") && config("laravel-data-swagger.documentations.{$documentation}.is_camel")) {
			$request->replace(Arr::camelToSnake($requestData));
		}
        return $next($request);
    }
}
