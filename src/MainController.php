<?php declare(strict_types = 1);

final class MainController
{
	public static function fromArray(array $arrayData): void
	{
		foreach ($arrayData as $entityName => $fields) {
			self::createEntity($entityName, $fields);
			self::createMigration($entityName, $fields);
		}
	}

	private static function createEntity(string $entityName, array $fields): void
	{
		$className = ucfirst($entityName);
		$properties = '';
		$methods = '';

		foreach ($fields as $fieldName => $attributes) {
			$type = self::mapType($attributes['type']);
			$properties .= "    private \$$fieldName;\n";
			$methods .= "    public function get" . ucfirst($fieldName) . "(): $type\n    {\n        return \$this->$fieldName;\n    }\n\n";
			$methods .= "    public function set" . ucfirst($fieldName) . "($type \$$fieldName): void\n    {\n        \$this->$fieldName = \$$fieldName;\n    }\n\n";
		}

		$entityCode = "<?php declare(strict_types = 1);\n\nclass $className\n{\n$properties\n$methods}\n";

		file_put_contents("src/Entity/$className.php", $entityCode);
	}

	private static function createMigration(string $entityName, array $fields): void
	{
		$tableName = strtolower($entityName);
		$migrationCode = "CREATE TABLE $tableName (\n";

		foreach ($fields as $fieldName => $attributes) {
			$type = self::mapDbType($attributes['type']);
			$nullable = $attributes['nullable'] ? 'NULL' : 'NOT NULL';
			$migrationCode .= "    $fieldName $type $nullable,\n";
		}

		$migrationCode = rtrim($migrationCode, ",\n") . "\n);";

		file_put_contents("migrations/create_${tableName}_table.sql", $migrationCode);
	}

	private static function mapType(string $type): string
	{
		$typeMap = [
			'ID' => 'int',
			'string' => 'string',
			'float' => 'float',
		];

		return $typeMap[$type] ?? 'mixed';
	}

	private static function mapDbType(string $type): string
	{
		$typeMap = [
			'ID' => 'INT AUTO_INCREMENT PRIMARY KEY',
			'string' => 'VARCHAR(255)',
			'float' => 'FLOAT',
		];

		return $typeMap[$type] ?? 'TEXT';
	}
}