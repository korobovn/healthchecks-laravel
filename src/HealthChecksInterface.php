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
     * @return string[]
     */
    public function groups(): array;

    /**
     * @param string $group_name
     *
     * @return bool
     */
    public function groupExists(string $group_name): bool;

    /**
     * @param string $check_class
     *
     * @throws InvalidArgumentException If passed unregistered checker class
     *
     * @return ResultInterface
     */
    public function execute(string $check_class): ResultInterface;

    /**
     * @param string $group_name
     *
     * @throws InvalidArgumentException If passed unregistered group name
     *
     * @return ResultInterface[]
     */
    public function groupExecute(string $group_name): array;
}
