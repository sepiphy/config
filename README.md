# Parsing configuration from PHP or Yaml files

![Tests](https://img.shields.io/github/workflow/status/sepiphy/config/tests?label=tests)
![Packagist](https://img.shields.io/packagist/dt/sepiphy/config.svg)
![Packagist Version](https://img.shields.io/packagist/v/sepiphy/config.svg?label=version)
![GitHub](https://img.shields.io/github/license/sepiphy/config.svg)

## Installation

Install this package via composer:

    $ composer require sepiphy/config

## Usage

Create a directory called `config` that contains a few php or yml files (each php file must return an array).

```php
// config/app.php
return [
    'name' => 'sepiphy/config',
    'version' => 'v1.0.0',
];
```

```yaml
# config/database.yml
default: sqlite
connections:
    sqlite:
        driver: 'sqlite'
        database: ':memory:'
```

Load files from the config directory.

```php
<?php

require '/path/to/vendor/autoload.php';

$config = Sepiphy\Config\Config::withDir('/path/to/config')

$config->get('app.name'); // 'sepiphy/config'
$config->get('app.version'); // 'v1.0.0'
$config->get('database.default'); // 'sqlite'
$config->get('database.connections.sqlite'); // ['driver' => 'sqlite', 'database' => ':memory:']
```
