<?php

namespace AvtoDev\HealthChecks;

use InvalidArgumentException;
use AvtoDev\HealthChecks\Results\ResultInterface;

interface HealthChecksInterface
{
    /**
     * @return string[]
     */
    public function classes(): array;

    /**
     * @param string $check_class
     *
     * @return bool
     */
    public function exists(string $check_class): bool;

    /**
     * @param string $check_class
     *
     * @throws InvalidArgumentException If passed unregistered checker class
     *
     * @return ResultInterface
     */
    public function execute(string $check_class): ResultInterface;

    /**
     * @throws InvalidArgumentException If passed unregistered checker class
     *
     * @return ResultInterface[]
     */
    public function executeAll(): array;
}
