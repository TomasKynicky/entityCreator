<?php declare(strict_types = 1);

namespace Tomaskynicky\EntityCreator\DTO;

final readonly class EntityDTO
{

	public function __construct(
		private string $name,
		/**
		 * @var FieldsDTO[]
		 */
		private array $fields,
	)
	{
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return FieldsDTO[]
	 */
	public function getFields(): array
	{
		return $this->fields;
	}



}
