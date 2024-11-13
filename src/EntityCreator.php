<?php declare(strict_types = 1);

namespace Tomaskynicky\EntityCreator;

use Tomaskynicky\EntityCreator\DTO\EntityDTO;
use Tomaskynicky\EntityCreator\Enum\DataType;
use Tomaskynicky\EntityCreator\Enum\RelationType;

final class EntityCreator implements EntityCreatorInterface
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

		foreach ($entityDTO->getFields() as $fieldName => $attributes) {
			$nullable = $attributes['nullable'] ? '?' : '';
			$defaultValue = $attributes['nullable'] ? ' = null' : '';
			$type = DataType::from($attributes['type'])->value;
			$ormColumn = "";

			if (isset($attributes['relation'])) {
				$relation = RelationType::from($attributes['relation'])->value;
				$ormColumn = "#[ORM\\{$relation}(inversedBy: '{$entityName}')]\n    #[ORM\\JoinColumn(name: '{$fieldName}_id', referencedColumnName: 'id', nullable: " . ($attributes['nullable'] ? 'true' : 'false') . ")]";
			} else {
				if ($attributes['type'] === "string") {
					$ormColumn = "#[ORM\\Column(length: 255)]";
				} elseif ($attributes['nullable'] === true) {
					$ormColumn = "#[ORM\\Column(length: 255, nullable: true)]";
				} elseif ($attributes['nullable'] === false) {
					$ormColumn = "#[ORM\\Column(length: 255, nullable: false)]";
				}

				if ($attributes['type'] === "ID") {
					$ormColumn = "#[ORM\Id]\n       #[ORM\GeneratedValue]\n       #[ORM\Column]";
				}
			}

			$properties .= "    $ormColumn\n    private $nullable$type \$$fieldName$defaultValue;\n\n";
			$methods .= "    public function get" . ucfirst($fieldName) . "(): $nullable$type\n    {\n        return \$this->$fieldName;\n    }\n\n";
			$methods .= "    public function set" . ucfirst($fieldName) . "($nullable$type \$$fieldName): static\n    {\n        \$this->$fieldName = \$$fieldName;\n\n        return \$this;\n    }\n\n";
		}

		$entityCode = "<?php declare(strict_types = 1);\n\nnamespace $namespace;\n\n$useStatements\n$annotations\nclass $className\n{\n$properties$methods}\n";

		return self::createFile($entityCode, $className);
	}

	private static function createFile(string $entityCode, string $className): bool
	{
		$changesMade = false;
		$filePath = "src/Entity/$className.php";
		if (file_exists($filePath)) {
			$existingCode = file_get_contents($filePath);
			if ($existingCode !== $entityCode) {
				file_put_contents($filePath, $entityCode);
				$changesMade = true;
			}
		} else {
			file_put_contents($filePath, $entityCode);
			$changesMade = true;
		}

		return $changesMade;
	}

}
