# 🌟 entityCreator

**Array to Entity and Repository** is a powerful Composer package that transforms Array data into PHP entities with ease. With
just a few steps, it parses Array inputs and auto-generates entity objects, simplifying data handling for applications
that work with structured Array data. Streamline your workflow and keep your code clean and efficient with *
*entityCreator**.

---

## 🎉 Features

- **Time saved**: No more creating entities via console!
- **Automated Entity Creation**: Converts Array data directly into PHP entity classes.
- **Seamless Array Parsing**: Easily parses Array inputs, saving time on data transformation.

## 📦 Installation

Install via Composer:

```bash
composer require tomaskynicky/entity-creator:dev-main
```

## 📄 License

This package is open-source software licensed under the [MIT license]

## ⌨️️ Example

```php
<?php declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tomaskynicky\EntityCreator\DTO\EntityDTO;
use Tomaskynicky\EntityCreator\DTO\FieldsDTO;
use Tomaskynicky\EntityCreator\Enum\DataType;
use Tomaskynicky\EntityCreator\IndexEntityCreator;

#[AsCommand(name: 'app:create-entities')]
class CreateEntitiesCommand extends Command
{

	protected function configure(): void
	{
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$arrayData = [
			new EntityDTO(
				name: 'User',
				fields: [
					new FieldsDTO(
						name: 'id',
						type: DataType::ID,
					),
					new FieldsDTO(
						name: 'name',
						type: DataType::BOOLEAN,
						length: '255',
						nullable: true
					)
				]
			),
		];

		$indexEntityCreator = new IndexEntityCreator();
		$indexEntityCreator->index($arrayData);

		$output->writeln("Success!");

		return Command::SUCCESS;
		/* Write this in console php bin/console app:create-entities(create Entity and Repository) 
		and this php bin/console make:migration for create migration */
	}
}
```