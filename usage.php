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
