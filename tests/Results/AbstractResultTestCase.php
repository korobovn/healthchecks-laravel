<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Tests\Results;

use AvtoDev\HealthChecks\Tests\AbstractTestCase;
use AvtoDev\HealthChecks\Results\ResultInterface;

abstract class AbstractResultTestCase extends AbstractTestCase
{
    /**
     * @var ResultInterface
     */
    protected $result_instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->result_instance = $this->makeInstance();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        unset($this->result_instance);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testImplements(): void
    {
        $this->assertInstanceOf(ResultInterface::class, $this->result_instance);
    }

    /**
     * Returns testing instance.
     *
     * @return ResultInterface
     */
    abstract protected function makeInstance(): ResultInterface;
}
