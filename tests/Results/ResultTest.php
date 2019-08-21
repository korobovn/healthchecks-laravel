<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Tests\Results;

use AvtoDev\HealthChecks\Results\Result;
use AvtoDev\HealthChecks\Results\ResultInterface;
use AvtoDev\HealthChecks\Tests\AbstractTestCase;
use Exception;

/**
 * Class ResultTest.
 *
 * @covers \AvtoDev\HealthChecks\Results\Result<extended>
 */
class ResultTest extends AbstractTestCase
{
    /**
     * @var ResultInterface
     */
    protected $result_instance;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->result_instance = $this->makeInstance();
    }

    /**
     * @inheritdoc
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
     * @return void
     */
    public function testPassed(): void
    {
        $this->assertTrue($this->result_instance->passed());

        $result_with_exception = $this->makeInstance(new \Exception);
        $this->assertFalse($result_with_exception->passed());

        unset($result_with_exception);
    }

    /**
     * @return void
     */
    public function testGetException(): void
    {
        $this->assertNull($this->result_instance->getException());

        $result_with_exception = $this->makeInstance($exception = new \Exception);
        $this->assertSame($exception, $result_with_exception->getException());

        unset($result_with_exception);
    }

    /**
     * @return void
     */
    public function testGetErrorMessage(): void
    {
        $this->assertNull($this->result_instance->getErrorMessage());

        $data_sets = [
            [new \Exception, '[Exception] '],
            [new \RuntimeException, '[RuntimeException] '],
            [new \Exception('test'), '[Exception] test'],
        ];

        /** @var Exception $exception */
        /** @var string $error_message */
        foreach ($data_sets as [$exception, $error_message]) {
            $result_with_exception = $this->makeInstance($exception);
            $this->assertSame($error_message, $result_with_exception->getErrorMessage());
        }

        unset($result_with_exception);
    }

    /**
     * Make result instance
     *
     * @param Exception|null $exception
     *
     * @return ResultInterface
     */
    protected function makeInstance(?Exception $exception = null): ResultInterface
    {
        return new Result($exception);
    }
}
