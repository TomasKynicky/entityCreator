<?php

namespace Tomaskynicky\EntityCreator\Enum;


use JetBrains\PhpStorm\Immutable;

enum DataType: string
{
	case STRING = 'string';
	case INTEGER = 'integer';
	case FLOAT = 'float';
	case BOOLEAN = 'boolean';
	case DATETIME = 'datetime';
	case DATE = 'date';
	case TIME = 'time';
	case TEXT = 'text';
	case JSON = 'json';

}
