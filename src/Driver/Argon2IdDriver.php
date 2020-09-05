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

use RuntimeException;

class Argon2IdDriver extends Argon2IDriver
{
    /**
     * Check the given plain value against a hash.
     *
     * @throws \RuntimeException
     */
    public function check(string $value, string $hashedValue, array $options = []): bool
    {
        if ($this->verifyAlgorithm && $this->info($hashedValue)['algoName'] !== 'argon2id') {
            throw new RuntimeException('This password does not use the Argon2id algorithm.');
        }

        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

    /**
     * Get the algorithm that should be used for hashing.
     *
     * @return int
     */
    protected function algorithm()
    {
        return PASSWORD_ARGON2ID;
    }
}
