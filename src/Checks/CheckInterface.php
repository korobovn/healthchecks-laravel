<?php

namespace AvtoDev\HealthChecks\Checks;

interface CheckInterface
{
    /**
     * Execute the check.
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface;
}
