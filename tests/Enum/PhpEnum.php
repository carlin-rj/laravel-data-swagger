<?php

namespace Tests\Enum;

enum PhpEnum: int
{
	case A = 1;

	case B = 2;

	public static function descriptions(): array
	{
		return [
				'a'=>'test',
				'c'=>'test2',
		];
	}
}
