<?php declare(strict_types=1);

namespace Wfs\CustomEnvironmentVariables;

use Noodlehaus\Config;

final class Customizer
{
    /**
     * @var Iterable
     */
    private $config;

    public static function load(string $filename): self
    {
        return new self(new Config($filename));
    }

    /**
     * Customizer constructor.
     * @param Iterable $config
     */
    public function __construct(Iterable $config)
    {
        $this->config = $config;
    }

    /**
     * @param Iterable $target
     * @param Iterable $config
     * @return Iterable
     */
    private function customizeInternal(Iterable $target, Iterable $config): Iterable
    {
        foreach ($config as $key => $value) {
            if (isset($target[$key])) {
                if (is_array($value) && is_array($target[$key])) {
                    $target[$key] = $this->customizeInternal($target[$key], $value);
                } elseif (is_string($target[$key]) && is_string($value)) {
                    $newValue = getenv($value);
                    if ($newValue) {
                        $target[$key] = $newValue;
                    }
                }
            }
        }
        return $target;
    }

    /**
     * @param Iterable $target
     * @return Iterable
     */
    public function customize(Iterable $target): Iterable
    {
        return $this->customizeInternal($target, $this->config);
    }
}
