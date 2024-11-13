# 🌟 entityCreator

**Array to Entity Creator** is a powerful Composer package that transforms Array data into PHP entities with ease. With
just a few steps, it parses Array inputs and auto-generates entity objects, simplifying data handling for applications
that work with structured Array data. Streamline your workflow and keep your code clean and efficient with *
*entityCreator**.

---

## 🎉 Features

- **Automated Entity Creation**: Converts Array data directly into PHP entity classes.
- **Seamless Array Parsing**: Easily parses Array inputs, saving time on data transformation.
- **Enhanced Data Handling**: Ideal for applications relying on structured Array data models.

## 📦 Installation

Install via Composer:

```bash
composer require tomaskynicky/entity-creator:dev-main
```

## 📄 License

This package is open-source software licensed under the [MIT license]

## 🖥️ Input

```php

		$arrayData = [
			new EntityDTO(
				name: 'User',
				fields: [
					new FieldsDTO(
						name: 'name',
						type: DataType::STRING,
						length: '255',
						nullable: true
					)
				]
			),
		];
