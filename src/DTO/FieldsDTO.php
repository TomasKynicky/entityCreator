<?php declare(strict_types = 1);

namespace Tomaskynicky\EntityCreator\DTO;

use Tomaskynicky\EntityCreator\Enum\DataType;
use Tomaskynicky\EntityCreator\Enum\RelationType;

final readonly class FieldsDTO
{

	public function __construct(
		private string $name,
		/**
		 * @var DataType[]
		 */
		private array $type,
		private ?string $length = null,
		/**
		 * @var RelationType[]
		 */
		private ?array $relation = null,
		private ?bool $nullable = false,
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
	 * @return DataType[]
	 */
	public function getType(): array
	{
		return $this->type;
	}

	/**
	 * @return string|null
	 */
	public function getLength(): ?string
	{
		return $this->length;
	}

	/**
	 * @return RelationType[] | null
	 */
	public function getRelation(): ?array
	{
		return $this->relation;
	}

	/**
	 * @return bool|null
	 */
	public function getNullable(): ?bool
	{
		return $this->nullable;
	}


}
