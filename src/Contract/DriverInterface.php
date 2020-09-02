<?php

declare(strict_types=1);

namespace HyperfExt\Hashing\Contract;

interface DriverInterface
{
    /**
     * Get information about the given hashed value.
     *
     * @param string $hashedValue
     *
     * @return array
     */
    public function info(string $hashedValue): array;

    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    public function make(string $value, array $options = []): string;

    /**
     * Check the given plain value against a hash.
     *
     * @param string $value
     * @param string $hashedValue
     * @param array  $options
     *
     * @return bool
     */
    public function check(string $value, string $hashedValue, array $options = []): bool;

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param string $hashedValue
     * @param array  $options
     *
     * @return bool
     */
    public function needsRehash(string $hashedValue, array $options = []): bool;
}
