<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\Result;
use AvtoDev\HealthChecks\Results\ResultInterface;

abstract class AbstractCheck implements CheckInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function execute(): ResultInterface;

    /**
     * {@inheritdoc}
     */
    public function success(): ResultInterface
    {
        return new Result;
    }

    /**
     * {@inheritdoc}
     */
    public function fail(\Exception $exception): ResultInterface
    {
        return new Result($exception);
    }
}
