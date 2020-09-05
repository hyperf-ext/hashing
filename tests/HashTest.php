<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-ext/hashing.
 *
 * @link     https://github.com/hyperf-ext/hashing
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/hashing/blob/master/LICENSE
 */
namespace HyperfTest;

use HyperfExt\Hashing\Driver\Argon2IdDriver;
use HyperfExt\Hashing\Driver\Argon2IDriver;
use HyperfExt\Hashing\Driver\BcryptDriver;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @internal
 * @coversNothing
 */
class HashTest extends TestCase
{
    public function testBasicBcryptHashing()
    {
        $hasher = new BcryptDriver();
        $value = $hasher->make('password');
        $this->assertNotSame('password', $value);
        $this->assertTrue($hasher->check('password', $value));
        $this->assertFalse($hasher->needsRehash($value));
        $this->assertTrue($hasher->needsRehash($value, ['rounds' => 1]));
        $this->assertSame('bcrypt', password_get_info($value)['algoName']);
    }

    public function testBasicArgon2iHashing()
    {
        if (! defined('PASSWORD_ARGON2I')) {
            $this->markTestSkipped('PHP not compiled with Argon2i hashing support.');
        }

        $hasher = new Argon2IDriver();
        $value = $hasher->make('password');
        $this->assertNotSame('password', $value);
        $this->assertTrue($hasher->check('password', $value));
        $this->assertFalse($hasher->needsRehash($value));
        $this->assertTrue($hasher->needsRehash($value, ['threads' => 1]));
        $this->assertSame('argon2i', password_get_info($value)['algoName']);
    }

    public function testBasicArgon2idHashing()
    {
        if (! defined('PASSWORD_ARGON2ID')) {
            $this->markTestSkipped('PHP not compiled with Argon2id hashing support.');
        }

        $hasher = new Argon2IdDriver();
        $value = $hasher->make('password');
        $this->assertNotSame('password', $value);
        $this->assertTrue($hasher->check('password', $value));
        $this->assertFalse($hasher->needsRehash($value));
        $this->assertTrue($hasher->needsRehash($value, ['threads' => 1]));
        $this->assertSame('argon2id', password_get_info($value)['algoName']);
    }

    /**
     * @depends testBasicBcryptHashing
     */
    public function testBasicBcryptVerification()
    {
        $this->expectException(RuntimeException::class);

        if (! defined('PASSWORD_ARGON2I')) {
            $this->markTestSkipped('PHP not compiled with Argon2i hashing support.');
        }

        $argonHasher = new Argon2IDriver(['verify' => true]);
        $argonHashed = $argonHasher->make('password');
        (new BcryptDriver(['verify' => true]))->check('password', $argonHashed);
    }

    /**
     * @depends testBasicArgon2iHashing
     */
    public function testBasicArgon2iVerification()
    {
        $this->expectException(RuntimeException::class);

        $bcryptHasher = new BcryptDriver(['verify' => true]);
        $bcryptHashed = $bcryptHasher->make('password');
        (new Argon2IDriver(['verify' => true]))->check('password', $bcryptHashed);
    }

    /**
     * @depends testBasicArgon2idHashing
     */
    public function testBasicArgon2idVerification()
    {
        $this->expectException(RuntimeException::class);

        $bcryptHasher = new BcryptDriver(['verify' => true]);
        $bcryptHashed = $bcryptHasher->make('password');
        (new Argon2IdDriver(['verify' => true]))->check('password', $bcryptHashed);
    }
}
