<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Tests\Results;

use AvtoDev\HealthChecks\Results\Result;
use AvtoDev\HealthChecks\Results\ResultInterface;

/**
 * Class ResultTest.
 *
 * @covers \AvtoDev\HealthChecks\Results\Result<extended>
 */
class ResultTest extends AbstractResultTestCase
{
    /**
     * @inheritdoc
     */
    protected function makeInstance(...$args): ResultInterface
    {
        return new Result(...$args);
    }

    public function testConstructor(): void
    {
        $result = $this->makeInstance();
        $this->assertTrue($result->passed());
        $this->assertNull($result->getErrorMessage());
        $this->assertNull($result->getException());

        $result = $this->makeInstance($exception = new \Exception('test'));
        $this->assertFalse($result->passed());
        $this->assertSame('[Exception] test', $result->getErrorMessage());
        $this->assertSame($exception, $result->getException());
    }
}
