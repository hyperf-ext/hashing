<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/hashing.
 *
 * @link     https://github.com/hyperf-ext/hashing
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/hashing/blob/master/LICENSE
 */
namespace HyperfExt\Hashing\Driver;

use HyperfExt\Hashing\Contract\DriverInterface;
use RuntimeException;

class Argon2IDriver extends AbstractDriver implements DriverInterface
{
    /**
     * The default memory cost factor.
     *
     * @var int
     */
    protected $memory = 1024;

    /**
     * The default time cost factor.
     *
     * @var int
     */
    protected $time = 2;

    /**
     * The default threads factor.
     *
     * @var int
     */
    protected $threads = 2;

    /**
     * Indicates whether to perform an algorithm check.
     *
     * @var bool
     */
    protected $verifyAlgorithm = false;

    /**
     * Create a new driver instance.
     */
    public function __construct(array $options = [])
    {
        $this->time = $options['time'] ?? $this->time;
        $this->memory = $options['memory'] ?? $this->memory;
        $this->threads = $options['threads'] ?? $this->threads;
        $this->verifyAlgorithm = $options['verify'] ?? $this->verifyAlgorithm;
    }

    /**
     * Hash the given value.
     *
     * @throws \RuntimeException
     */
    public function make(string $value, array $options = []): string
    {
        $hash = password_hash($value, $this->algorithm(), [
            'memory_cost' => $this->memory($options),
            'time_cost' => $this->time($options),
            'threads' => $this->threads($options),
        ]);

        if ($hash === false) {
            throw new RuntimeException('Argon2 hashing not supported.');
        }

        return $hash;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @throws \RuntimeException
     */
    public function check(string $value, string $hashedValue, array $options = []): bool
    {
        if ($this->verifyAlgorithm && $this->info($hashedValue)['algoName'] !== 'argon2i') {
            throw new RuntimeException('This password does not use the Argon2i algorithm.');
        }

        return parent::check($value, $hashedValue, $options);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     */
    public function needsRehash(string $hashedValue, array $options = []): bool
    {
        return password_needs_rehash($hashedValue, $this->algorithm(), [
            'memory_cost' => $this->memory($options),
            'time_cost' => $this->time($options),
            'threads' => $this->threads($options),
        ]);
    }

    /**
     * Set the default password memory factor.
     *
     * @return $this
     */
    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    /**
     * Set the default password timing factor.
     *
     * @return $this
     */
    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Set the default password threads factor.
     *
     * @return $this
     */
    public function setThreads(int $threads): self
    {
        $this->threads = $threads;

        return $this;
    }

    /**
     * Get the algorithm that should be used for hashing.
     *
     * @return int
     */
    protected function algorithm()
    {
        return PASSWORD_ARGON2I;
    }

    /**
     * Extract the memory cost value from the options array.
     */
    protected function memory(array $options): int
    {
        return $options['memory'] ?? $this->memory;
    }

    /**
     * Extract the time cost value from the options array.
     */
    protected function time(array $options): int
    {
        return $options['time'] ?? $this->time;
    }

    /**
     * Extract the threads value from the options array.
     */
    protected function threads(array $options): int
    {
        return $options['threads'] ?? $this->threads;
    }
}
