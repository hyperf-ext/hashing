<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
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
                    'description' => 'The config for HyperfExt\\Hashing.',
                    'source' => __DIR__ . '/../publish/ext-hashing.php',
                    'destination' => BASE_PATH . '/config/autoload/ext-hashing.php',
                ],
            ],
        ];
    }
}
