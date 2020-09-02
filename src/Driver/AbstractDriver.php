<?php

declare(strict_types=1);

namespace HyperfExt\Hashing\Driver;

abstract class AbstractDriver
{
    /**
     * Get information about the given hashed value.
     *
     * @param string $hashedValue
     *
     * @return array
     */
    public function info(string $hashedValue): array
    {
        return password_get_info($hashedValue);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param string $value
     * @param string $hashedValue
     * @param array  $options
     *
     * @return bool
     */
    public function check(string $value, string $hashedValue, array $options = []): bool
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }
}
