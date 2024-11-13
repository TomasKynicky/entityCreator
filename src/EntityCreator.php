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
		$useStatements = "use App\\Repository\\{$className}Repository;\nuse Doctrine\\ORM\\Mapping as ORM;\n";
		$annotations = "#[ORM\\Entity(repositoryClass: {$className}Repository::class)]\n#[ORM\\Table(name: '`" . strtolower($entityName) . "`')]\n";
		$properties = '';
		$methods = '';

		foreach ($entityDTO->getFields() as $field) {
			$nullable = $field->getNullable() ? '?' : '';
			$defaultValue = $field->getNullable() ? ' = null' : '';
			$type = $field->getType()->value;
			$ormColumn = "";

			if ($field->getRelation() !== null) {
				$relation = $field->getRelation()->value;
				$ormColumn = "#[ORM\\{$relation}(inversedBy: '{$entityName}')]\n    #[ORM\\JoinColumn(name: '{$field->getName()}_id', referencedColumnName: 'id', nullable: " . ($field->getNullable() ? 'true' : 'false') . ")]";
			} else {
				if ($field->getType()->value === "string") {
					$ormColumn = "#[ORM\\Column(length: 255)]";
				} elseif ($field->getNullable() === true) {
					$ormColumn = "#[ORM\\Column(length: 255, nullable: true)]";
				} elseif ($field->getNullable() === false) {
					$ormColumn = "#[ORM\\Column(length: 255, nullable: false)]";
				}

				if ($field->getType()->value === "ID") {
					$ormColumn = "#[ORM\Id]\n       #[ORM\GeneratedValue]\n       #[ORM\Column]";
				}
			}

			$properties .= "    $ormColumn\n    private $nullable$type \${$field->getName()}$defaultValue;\n\n";
			$methods .= "    public function get" . ucfirst($field->getName()) . "(): $nullable$type\n    {\n        return \$this->{$field->getName()};\n    }\n\n";
			$methods .= "    public function set" . ucfirst($field->getName()) . "($nullable$type \${$field->getName()}): static\n    {\n        \$this->{$field->getName()} = \${$field->getName()};\n\n        return \$this;\n    }\n\n";
		}

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
