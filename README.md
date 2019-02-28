CustomEnvironmentVariables
----

CustomEnvironmentVariables is inspired by [node-config](https://github.com/lorenwest/node-config)'s
[Custom Environment Variables](https://github.com/lorenwest/node-config/wiki/Environment-Variables#custom-environment-variables) feature.

## Requirements

PHP 7.1+

## Installation

```bash
$ composer require wfs/custom-environment-variables
```

## Usage

Example code `usage.php` is:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Wfs\CustomEnvironmentVariables\Customizer;

$customizer = new Customizer([
    'employee' => [
        'name' => 'NAME',
        'age' => 'AGE',
    ]
]);

$target = [
    'employee' => [
        'name' => 'alice',
        'age' => '10',
    ]
];

$customized = $customizer->customize($target);

echo("name: {$customized['employee']['name']}" . PHP_EOL);
echo("age: {$customized['employee']['age']}" . PHP_EOL);
```

If you does not set any environment variables, `usage.php` result is:

```bash
$ php usage.php
name: alice
age: 10
```

If you set `NAME`, `usage.php` result is:

```bash
$ export NAME=bob
$ php usage.php
name: bob
age: 10
```

If you set `NAME` and `AGE`, `usage.php` result is:

```bash
$ export NAME=bob
$ export AGE=20
$ php usage.php
name: bob
age: 20
```

You will use `CustomEnvironmentVariables` with [Config](https://github.com/hassankhan/config).

Example code `config.php` is:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Wfs\CustomEnvironmentVariables\Customizer;
use Noodlehaus\Config;
use Noodlehaus\Parser\Yaml;

$customizer = new Customizer([
    'employee' => [
        'name' => 'NAME',
        'age' => 'AGE',
    ]
]);

$target = new Config(<<<CONF
employee:
  name: alice
  age: '10' # must be string
CONF
, new Yaml, true);

echo("name: {$target['employee']['name']}" . PHP_EOL);
echo("age: {$target['employee']['age']}" . PHP_EOL);

$customized = $customizer->customize($target);

echo("name: {$customized['employee']['name']}" . PHP_EOL);
echo("age: {$customized['employee']['age']}" . PHP_EOL);
```

result is:

```bash
$ export NAME=bob
$ export AGE=20
$ php config.php
name: alice
age: 10
name: bob
age: 20
```