<?php

namespace Tomaskynicky\EntityCreator\Enum;


use JetBrains\PhpStorm\Immutable;

enum DataType: string
{
	case STRING = 'string';
	case INTEGER = 'integer';
	case FLOAT = 'float';
	case BOOLEAN = 'bool';
	case DATETIME = 'datetime';
	case DATE = 'date';
	case TIME = 'time';
	case TEXT = 'text';
	case JSON = 'json';
	case ID = 'int';
	case RELATION = 'relation';

}
