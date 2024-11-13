<?php

namespace Tomaskynicky\EntityCreator\Enum;

enum RelationType: string
{
	// OneToOne
	case ONE_TO_ONE = 'OneToOne';
	// OneToMany
	case ONE_TO_MANY = 'OneToMany';
	// ManyToOne
	case MANY_TO_ONE = 'ManyToOne';
	// ManyToMany
	case MANY_TO_MANY = 'ManyToMany';

}
