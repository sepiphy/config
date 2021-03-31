# sepiphy/config

![Tests](https://img.shields.io/github/workflow/status/sepiphy/config/tests?label=tests)
![Packagist](https://img.shields.io/packagist/dt/sepiphy/config.svg)
![Packagist Version](https://img.shields.io/packagist/v/sepiphy/config.svg?label=version)
![GitHub](https://img.shields.io/github/license/sepiphy/config.svg)

## Installation

Install `sepiphy/config` package via composer.

    $ composer require sepiphy/config

## Usage

Create a directory called `config` that contains some php files (each one must return an array).

```php
// config/app.php
return [
    'name' => 'Sepiphy',
    'version' => '0.6.0',
];
```

```php
// config/database.php
return [
    'default' => 'sqlite',
    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],
    ],
];
```

Load files from the config directory.

```php
<?php

use Sepiphy\Config\Config;

require '/path/to/vendor/autoload.php';

$config = new Config();

$config->load('/path/to/config');

$config->get('app.name'); // 'Sepiphy'
$config->get('app.version'); // '0.6.0'
$config->get('database.default'); // 'sqlite'
$config->get('database.connections.sqlite'); // ['driver' => 'sqlite', 'database' => ':memory:']
```

Use the different config loaders.

```php
<?php

use Sepiphy\Config\Config;
use Sepiphy\Config\Loaders\PhpLoader;
use Sepiphy\Config\Loaders\YamlLoader;

// php loader by default.
$config = new Config([], new PhpLoader());

// yaml loader.
$config = new Config([], new YamlLoader());
```
