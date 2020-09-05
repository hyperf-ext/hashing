<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/hashing.
 *
 * @link     https://github.com/hyperf-ext/hashing
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/hashing/blob/master/LICENSE
 */
namespace HyperfExt\Hashing;

use Hyperf\Contract\ConfigInterface;
use HyperfExt\Hashing\Contract\DriverInterface;
use HyperfExt\Hashing\Contract\HashInterface;
use HyperfExt\Hashing\Driver\BcryptDriver;
use InvalidArgumentException;

class HashManager implements HashInterface
{
    /**
     * The config instance.
     *
     * @var \Hyperf\Contract\ConfigInterface
     */
    protected $config;

    /**
     * The array of created "drivers".
     *
     * @var \HyperfExt\Hashing\Contract\DriverInterface[]
     */
    protected $drivers = [];

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Get information about the given hashed value.
     */
    public function info(string $hashedValue): array
    {
        return $this->getDriver()->info($hashedValue);
    }

    /**
     * Hash the given value.
     */
    public function make(string $value, array $options = []): string
    {
        return $this->getDriver()->make($value, $options);
    }

    /**
     * Check the given plain value against a hash.
     */
    public function check(string $value, string $hashedValue, array $options = []): bool
    {
        return $this->getDriver()->check($value, $hashedValue, $options);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     */
    public function needsRehash(string $hashedValue, array $options = []): bool
    {
        return $this->getDriver()->needsRehash($hashedValue, $options);
    }

    /**
     * Get a driver instance.
     *
     * @throws \InvalidArgumentException
     */
    public function getDriver(?string $name = null): DriverInterface
    {
        if (isset($this->drivers[$name]) && $this->drivers[$name] instanceof DriverInterface) {
            return $this->drivers[$name];
        }

        $name = $name ?: $this->config->get('hashing.default', 'bcrypt');

        $config = $this->config->get("hashing.driver.{$name}");
        if (empty($config) or empty($config['class'])) {
            throw new InvalidArgumentException(sprintf('The hashing driver config %s is invalid.', $name));
        }

        $driverClass = $config['class'] ?? BcryptDriver::class;

        $driver = make($driverClass, ['options' => $config['options'] ?? []]);

        return $this->drivers[$name] = $driver;
    }
}
