# Hyperf 哈希组件

该组件为存储用户密码提供了安全的 Bcrypt 和 Argon2 哈希加密方式。

> 移植自 [illuminate/hashing](https://github.com/illuminate/hashing )。

## 安装

```shell script
composer require hyperf-ext/hashing
```

## 发布配置

```shell script
php bin/hyperf.php vendor:publish hyperf-ext/hashing
```

> 配置文件位于 `config/autoload/hashing.php`。

## 默认配置

```php
<?php

declare(strict_types=1);

return [
    'default' => 'bcrypt',
    'driver' => [
        'bcrypt' => [
            'class' => \HyperfExt\Hashing\Driver\BcryptDriver::class,
            'rounds' => env('BCRYPT_ROUNDS', 10),
        ],
        'argon' => [
            'class' => \HyperfExt\Hashing\Driver\Argon2IDriver::class,
            'memory' => 1024,
            'threads' => 2,
            'time' => 2,
        ],
        'argon2id' => [
            'class' => \HyperfExt\Hashing\Driver\Argon2IdDriver::class,
            'memory' => 1024,
            'threads' => 2,
            'time' => 2,
        ],
    ],
];
```

你可以在 `config/autoload/hashing.php` 配置文件中配置默认哈希驱动程序。目前支持三种驱动程序： Bcrypt 和 Argon2（Argon2i 和 Argon2id variants）。

> 注意：Argon2i 驱动程序需要 PHP 7.2.0 或更高版本，而 Argon2id 驱动程序则需要 PHP 7.3.0 或更高版本。

## 使用

你可以通过 `\HyperfExt\Hashing\Hash` 类来加密你的密码：

```php
<?php

declare(strict_types=1);

namespace App\Http\Controller;

use Hyperf\HttpServer\Request;
use HyperfExt\Hashing\Hash;

class UpdatePasswordController
{
    public function update(Request $request)
    {
        // ……

        $user->fill([
            'password' => Hash::make($request->input('new_password'))
        ])->save();
    }
}
```

### 调整 Bcrypt 加密系数

如果使用 Bcrypt 算法，你可以在 `make` 方法中使用 `rounds` 选项来配置该算法的加密系数。然而，对大多数应用程序来说，默认值就足够了：

```php
$hashed = Hash::make('password', [
    'rounds' => 12
]);
```

### 调整 Argon2 加密系数

如果使用 Argon2 算法，你可以在 `make` 方法中使用 `memory`，`time` 和 `threads` 选项来配置该算法的加密系数。然后，对大多数应用程序来说，默认值就足够了：

```php
$hashed = Hash::make('password', [
    'memory' => 1024,
    'time' => 2,
    'threads' => 2,
]);
```

> 有关这些选项的更多信息，请查阅 [PHP 官方文档](https://secure.php.net/manual/en/function.password-hash.php )。

### 密码哈希验证

`check` 方法能为您验证一段给定的未加密字符串与给定的哈希值是否一致：

```php
if (Hash::check('plain-text', $hashedPassword)) {
    // 密码匹配…
}
```

### 检查密码是否需要重新哈希

`needsRehash` 方法可以为您检查当哈希的加密系数改变时，您的密码是否被新的加密系数重新加密过：

```php
if (Hash::needsRehash($hashed)) {
    $hashed = Hash::make('plain-text');
}
```

### 使用指定驱动

```php
$hasher = Hash::getDriver('argon2i');
$hasher->make('plain-text');
```

### 使用自定义哈希类

实现 `\HyperfExt\Hashing\Contract\DriverInterface` 接口，并参照配置文件中的其他算法进行配置。
