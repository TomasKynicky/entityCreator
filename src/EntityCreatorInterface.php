<?php

namespace Tomaskynicky\EntityCreator;

use Tomaskynicky\EntityCreator\DTO\EntityDTO;

interface EntityCreatorInterface
{

	public static function entityCreator(EntityDTO $entityDTO): void;

}
