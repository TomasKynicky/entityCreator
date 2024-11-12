PROJECT_NAME := entityCreator

analyse:
	vendor/bin/phpstan analyse src --level=7

cs:
	vendor/bin/phpcs --standard=PSR12 src