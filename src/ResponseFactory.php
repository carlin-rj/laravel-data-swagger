<?php

namespace Carlin\LaravelDataSwagger;
use Illuminate\Http\JsonResponse;
use \Illuminate\Routing\ResponseFactory as IlluminateResponseFactory;
use Carlin\LaravelDataSwagger\Helper\Arr;

class ResponseFactory extends IlluminateResponseFactory
{
	private string $documentation;
	public function json($data = [], $status = 200, array $headers = [], $options = 0): JsonResponse
	{
		$data = json_decode(json_encode($data, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
		//转换数据
		$isCamel = config("laravel-data-swagger.documentations.{$this->documentation}.is_camel");
		if ($isCamel) {
			$data = Arr::snakeToCamel($data);
		} else {
			$data = Arr::camelToSnake($data);
		}
		return new JsonResponse($data, $status, $headers, $options);
	}

	public function setDocumentation(string $documentation): self
	{
		$this->documentation = $documentation;
		return $this;
	}
}
