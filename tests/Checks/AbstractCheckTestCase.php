<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Tests\Checks;

use AvtoDev\HealthChecks\Checks\AbstractCheck;
use AvtoDev\HealthChecks\Checks\CheckInterface;
use AvtoDev\HealthChecks\Results\ResultInterface;
use AvtoDev\HealthChecks\Tests\AbstractTestCase;

abstract class AbstractCheckTestCase extends AbstractTestCase
{
    /**
     * @var CheckInterface
     */
    protected $check_instance;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->check_instance = $this->makeInstance();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        unset($this->check_instance);

        parent::tearDown();
    }

    /**
     * @return void
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(CheckInterface::class, $this->check_instance);
        $this->assertInstanceOf(AbstractCheck::class, $this->check_instance);
    }

    /**
     * @return void
     */
    public function testSuccessMethod(): void
    {
        $this->assertInstanceOf(ResultInterface::class, $result = $this->check_instance->success());
        $this->assertTrue($result->passed());
    }

    /**
     * @return void
     */
    public function testFailMethod(): void
    {
        $this->assertInstanceOf(ResultInterface::class, $result = $this->check_instance->fail(new \Exception));
        $this->assertFalse($result->passed());
    }

    /**
     * @return void
     */
    abstract public function testExecute(): void;

    /**
     * Returns check instance
     *
     * @param array $options
     *
     * @return CheckInterface
     */
    abstract protected function makeInstance(array $options = []): CheckInterface;
}
