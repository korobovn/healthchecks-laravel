<?php

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\ResultInterface;

interface CheckInterface
{
    /**
     * Execute the check.
     *
     * @param array $options
     *
     * @return ResultInterface
     */
    public function execute(array $options = []): ResultInterface;
}
