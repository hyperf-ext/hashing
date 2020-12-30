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

use HyperfExt\Hashing\Contract\HashInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                HashInterface::class => HashManager::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for hyperf-ext/hashing.',
                    'source' => __DIR__ . '/../publish/hashing.php',
                    'destination' => BASE_PATH . '/config/autoload/hashing.php',
                ],
            ],
        ];
    }
}
