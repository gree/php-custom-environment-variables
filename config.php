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