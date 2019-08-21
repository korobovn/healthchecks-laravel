<?php

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\ResultInterface;

interface CheckInterface
{
    /**
     * Execute the check.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface;

    /**
     * Returns success check result.
     *
     * @return ResultInterface
     */
    public function success(): ResultInterface;

    /**
     * Returns failed check result.
     *
     * @param \Exception $exception
     *
     * @return ResultInterface
     */
    public function fail(\Exception $exception): ResultInterface;
}
