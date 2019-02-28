<?php declare(strict_types=1);

namespace Wfs\CunstomEnvironmentVariales\Tests;

use Dotenv\Dotenv;
use Noodlehaus\Config;
use Noodlehaus\Parser\Yaml;
use PHPUnit\Framework\TestCase;
use Wfs\CustomEnvironmentVariables\Customizer;

final class CustomizerTest extends TestCase
{
    private $config;

    protected function setUp(): void
    {
        parent::setUp();

        Dotenv::create(__DIR__ . '/fixtures')->load();

        $this->config = [
            'employee' => [
                'name' => 'NAME',
                'age' => 'AGE',
            ]
        ];
    }

    public function testCustomize()
    {
        $customizer = new Customizer($this->config);

        $target = [
            'employee' => [
                'name' => 'alice',
                'age' => '20',
            ]
        ];

        $actual = $customizer->customize($target);

        $this->assertSame([
            'employee' => [
                'name' => 'bob',
                'age' => '10',
            ]
        ], $actual);
    }

    public function testCustomizeWithIntegerValue()
    {
        $customizer = new Customizer($this->config);

        $target = [
            'employee' => [
                'name' => 'alice',
                'age' => 20,
            ]
        ];

        $actual = $customizer->customize($target);

        $this->assertSame([
            'employee' => [
                'name' => 'bob',
                'age' => 20,
            ]
        ], $actual);
    }

    public function testCustomizeWithArrayValue()
    {
        $customizer = new Customizer($this->config);

        $target = [
            'employee' => [
                'name' => 'alice',
                'age' => [
                    'value' => '20',
                ],
            ]
        ];

        $actual = $customizer->customize($target);

        $this->assertSame([
            'employee' => [
                'name' => 'bob',
                'age' => [
                    'value' => '20'
                ],
            ]
        ], $actual);
    }

    public function testCustomizeWithoutKeys()
    {
        $customizer = new Customizer($this->config);

        $target = [
            'employee' => [
            ]
        ];

        $actual = $customizer->customize($target);

        $this->assertSame([
            'employee' => [
            ]
        ], $actual);
    }

    public function testCustomizeWithFile()
    {
        $customizer = Customizer::load(__DIR__ . '/fixtures/custom-environment-variables.json');

        $target = [
            'employee' => [
                'name' => 'alice',
                'age' => '20',
            ]
        ];

        $actual = $customizer->customize($target);

        $this->assertSame([
            'employee' => [
                'name' => 'bob',
                'age' => '10',
            ]
        ], $actual);
    }

    public function testCustomizeConfig()
    {
        $customizer = new Customizer($this->config);

        $target = new Config(<<<CONF
employee:
  name: alice
  age: '20' # must be string
CONF
        , new Yaml, true);

        $this->assertSame('alice', $target['employee']['name']);
        $this->assertSame('20', $target['employee']['age']);

        $actual = $customizer->customize($target);

        // return ArrayAccess object
        $this->assertSame('bob', $actual['employee']['name']);
        $this->assertSame('10', $actual['employee']['age']);
    }
}
