# 🌟 entityCreator

**JSON to Entity Creator** is a powerful Composer package that transforms JSON data into PHP entities with ease. With just a few steps, it parses JSON inputs and auto-generates entity objects, simplifying data handling for applications that work with structured JSON data. Streamline your workflow and keep your code clean and efficient with **entityCreator**.

---

## 🎉 Features

- **Automated Entity Creation**: Converts JSON data directly into PHP entity classes.
- **Seamless JSON Parsing**: Easily parses JSON inputs, saving time on data transformation.
- **Enhanced Data Handling**: Ideal for applications relying on structured JSON data models.

## 📦 Installation

Install via Composer:

```bash
composer require TomasKynicky/entitycreator
```

## 📄 License

This package is open-source software licensed under the [MIT license]


## 🖥️ Usage


```php
use App\EntityCreator;

$jsonData = '{
    "name": "John Doe",
    "email": "john@example.com",
    "age": 30
}';

$entity = EntityCreator::fromJson($jsonData);
echo $entity->getName(); // Outputs: John Doe
