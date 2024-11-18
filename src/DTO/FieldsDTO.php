<?php declare(strict_types = 1);

namespace Tomaskynicky\EntityCreator\DTO;

use Tomaskynicky\EntityCreator\Enum\DataType;
use Tomaskynicky\EntityCreator\Enum\RelationType;

final readonly class FieldsDTO
{

	public function __construct(
		private string $name,
		private ?DataType $type = null,
		private ?string $length = "255",
		private ?string $relationTo = null,
		private ?RelationType $relation = null,
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

	public function getType(): ?DataType
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

	public function getRelation(): ?RelationType
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

	/**
	 * @return string|null
	 */
	public function getRelationTo(): ?string
	{
		return $this->relationTo;
	}




}
