<?php declare(strict_types = 1);

namespace Tomaskynicky\EntityCreator;

use Tomaskynicky\EntityCreator\DTO\EntityDTO;
use Tomaskynicky\EntityCreator\Enum\DataType;
use Tomaskynicky\EntityCreator\Enum\RelationType;

final class EntityCreator
{
	public static function entityCreator(EntityDTO $entityDTO): void
	{
		self::entityRenderer($entityDTO);
	}

	private static function entityRenderer(EntityDTO $entityDTO): bool
	{
		$className = ucfirst($entityDTO->getName());
		$entityName = $entityDTO->getName();
		$namespace = "App\\Entity";
		$useStatements = "use App\\Repository\\{$className}Repository;\nuse Doctrine\\ORM\\Mapping as ORM;\nuse DateTimeImmutable;\n";
		$annotations = "#[ORM\\Entity(repositoryClass: {$className}Repository::class)]\n#[ORM\\Table(name: '`" . strtolower($entityName) . "`')]\n#[ORM\\HasLifecycleCallbacks]\n";
		$properties = '';
		$methods = '';
		$hasCreatedAt = false;
		$hasUpdatedAt = false;

		foreach ($entityDTO->getFields() as $field) {
			if ($field->getName() === 'createdAt') {
				$hasCreatedAt = true;
			}
			if ($field->getName() === 'updatedAt') {
				$hasUpdatedAt = true;
			}

			$nullable = $field->getNullable() ? '?' : '';
			$defaultValue = $field->getNullable() ? ' = null' : '';
			$type = $field->getType()->value;
			$ormColumn = "";
			$length = $field->getLength();

			if ($field->getRelationTo() !== null) {
				$type = $field->getRelationTo();
				$relation = $field->getRelation()->value;
				$ormColumn = "#[ORM\\{$relation}(targetEntity: {$type}::class, inversedBy: '{$entityName}')]\n    #[ORM\\JoinColumn(name: '{$field->getName()}_id', referencedColumnName: 'id', nullable: " . ($field->getNullable() ? 'true' : 'false') . ")]";
			} else {
				switch ($field->getType()->value) {
					case DataType::STRING->value:
						$ormColumn = "#[ORM\\Column(type: 'string', length: {$length}, nullable: " . ($field->getNullable() ? 'true' : 'false') . ")]";
						$type = 'string';
						break;
					case DataType::INTEGER->value:
						$ormColumn = "#[ORM\\Column(type: 'integer')]";
						$type = 'int';
						break;
					case DataType::DATETIME->value:
						$ormColumn = "#[ORM\\Column(type: 'datetime_immutable')]";
						$type = 'DateTimeImmutable';
						break;
					case DataType::BOOLEAN->value:
						$ormColumn = "#[ORM\\Column(type: 'boolean')]";
						$type = 'bool';
						break;
					case DataType::TEXT->value:
						$ormColumn = "#[ORM\\Column(type: 'text', nullable: " . ($field->getNullable() ? 'true' : 'false') . ")]";
						$type = 'string';
						break;
					case DataType::ID->value:
						$ormColumn = "#[ORM\\Id]\n    #[ORM\\GeneratedValue]\n    #[ORM\\Column(type: 'integer')]";
						$type = 'int';
						break;
					default:
						throw new \InvalidArgumentException("Unsupported field type: {$field->getType()->value}");
				}
			}

			$properties .= "    $ormColumn\n    private $nullable$type \${$field->getName()}$defaultValue;\n\n";
			$methods .= "    public function get" . ucfirst($field->getName()) . "(): $nullable$type\n    {\n        return \$this->{$field->getName()};\n    }\n\n";
			$methods .= "    public function set" . ucfirst($field->getName()) . "($nullable$type \${$field->getName()}): static\n    {\n        \$this->{$field->getName()} = \${$field->getName()};\n\n        return \$this;\n    }\n\n";
		}

		if (!$hasCreatedAt) {
			$properties .= "    #[ORM\\Column(type: 'datetime_immutable')]\n    private DateTimeImmutable \$createdAt;\n\n";
			$methods .= "    public function getCreatedAt(): DateTimeImmutable\n    {\n        return \$this->createdAt;\n    }\n\n";
			$methods .= "    public function setCreatedAt(DateTimeImmutable \$createdAt): static\n    {\n        \$this->createdAt = \$createdAt;\n\n        return \$this;\n    }\n\n";
		}

		if (!$hasUpdatedAt) {
			$properties .= "    #[ORM\\Column(type: 'datetime_immutable', nullable: true)]\n    private ?DateTimeImmutable \$updatedAt = null;\n\n";
			$methods .= "    public function getUpdatedAt(): ?DateTimeImmutable\n    {\n        return \$this->updatedAt;\n    }\n\n";
			$methods .= "    public function setUpdatedAt(?DateTimeImmutable \$updatedAt): static\n    {\n        \$this->updatedAt = \$updatedAt;\n\n        return \$this;\n    }\n\n";
		}

		$methods .= "    #[ORM\\PrePersist]\n    public function setCreatedAtValue(): void\n    {\n        \$this->createdAt = new DateTimeImmutable();\n    }\n\n";
		$methods .= "    #[ORM\\PreUpdate]\n    public function setUpdatedAtValue(): void\n    {\n        \$this->updatedAt = new DateTimeImmutable();\n    }\n";

		$entityCode = "<?php declare(strict_types = 1);\n\nnamespace $namespace;\n\n$useStatements\n$annotations\nclass $className\n{\n$properties$methods}\n";
		$repositoryCode = "<?php declare(strict_types = 1);\n\nnamespace App\\Repository;\n\nuse App\\Entity\\$className;\nuse Doctrine\\Bundle\\DoctrineBundle\\Repository\\ServiceEntityRepository;\nuse Doctrine\\Persistence\\ManagerRegistry;\n\nclass {$className}Repository extends ServiceEntityRepository\n{\n    public function __construct(ManagerRegistry \$registry)\n    {\n        parent::__construct(\$registry, $className::class);\n    }\n}\n";

		$entityCreated = self::createFile($entityCode, $className);
		$repositoryCreated = self::createFile($repositoryCode, "{$className}Repository", "src/Repository");

		return $entityCreated || $repositoryCreated;
	}

	private static function createFile(string $code, string $className, string $directory = "src/Entity"): bool
	{
		$changesMade = false;
		$filePath = "$directory/$className.php";

		if (file_exists($filePath)) {
			$existingCode = file_get_contents($filePath);
			if ($existingCode !== $code) {
				file_put_contents($filePath, $code);
				$changesMade = true;
			}
		} else {
			file_put_contents($filePath, $code);
			$changesMade = true;
		}

		return $changesMade;
	}
}