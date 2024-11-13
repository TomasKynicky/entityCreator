<?php declare(strict_types = 1);

namespace Tomaskynicky\EntityCreator;

use Tomaskynicky\EntityCreator\DTO\EntityDTO;

final class IndexEntityCreator
{

	/**
	 * @param EntityDTO[] $entities
	 */
	public function index(array $entities): void
	{
		foreach ($entities as $entity) {
			EntityCreator::entityCreator($entity);
		}
	}
}
