<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\Result;
use AvtoDev\HealthChecks\Results\ResultInterface;

abstract class AbstractCheck implements CheckInterface
{
    /**
     * {@inheritdoc}
     */
    abstract public function execute(array $options = []): ResultInterface;

    /**
     * Returns result for successful check.
     *
     * @return ResultInterface
     */
    protected function success(): ResultInterface
    {
        return new Result;
    }

    /**
     * Returns result for failed check.
     *
     * @param \Exception $e
     *
     * @return ResultInterface
     */
    protected function fail(\Exception $e): ResultInterface
    {
        return new Result($e);
    }
}
