<?php

declare(strict_types=1);

namespace HyperfExt\Hashing\Contract;

interface HashInterface extends DriverInterface
{
    /**
     * Get a driver instance.
     *
     * @param string|null $name
     *
     * @return \HyperfExt\Hashing\Contract\DriverInterface
     */
    public function getDriver(?string $name = null): DriverInterface;

}
