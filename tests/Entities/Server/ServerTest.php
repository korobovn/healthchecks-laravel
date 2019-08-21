<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Tests\Entities\Server;

use AvtoDev\HealthChecks\Entities\Server\Server;
use AvtoDev\HealthChecks\Entities\Server\ServerInterface;
use AvtoDev\HealthChecks\Tests\AbstractTestCase;

/**
 * Class ServerTest.
 *
 * @covers \AvtoDev\HealthChecks\Entities\Server\Server<extended>
 */
class ServerTest extends AbstractTestCase
{
    /**
     * @var ServerInterface
     */
    protected $server_instance;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->server_instance = $this->makeInstance('http://google.com');
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        unset($this->server_instance);

        parent::tearDown();
    }

    public function testImplements(): void
    {
        $this->assertInstanceOf(ServerInterface::class, $this->server_instance);
    }

    public function testConstants(): void
    {
        $this->assertSame(5, Server::DEFAULT_TIMEOUT);
        $this->assertSame(80, Server::DEFAULT_PORT);
    }

    /**
     * @return void
     */
    public function testConstructor(): void
    {
        $args = [
            ['http://goole.com', null, null],
            ['http://goole.com', 8080, null],
            ['http://goole.com', 8080, 20]
        ];

        foreach ($args as [$host, $port, $timeout]) {
            $mock = $this->getMockBuilder(Server::class)
                ->disableOriginalConstructor()
                ->getMock();

            $mock->expects($this->once())
                ->method('setHost')
                ->with($this->equalTo($host));

            $mock->expects($this->once())
                ->method('setPort')
                ->with($this->equalTo($port ?? ServerInterface::DEFAULT_PORT));

            $mock->expects($this->once())
                ->method('setTimeout')
                ->with($this->equalTo($timeout ?? ServerInterface::DEFAULT_TIMEOUT));

            (new \ReflectionClass(Server::class))
                ->getConstructor()
                ->invokeArgs($mock, [$host, $port, $timeout]);
        }
    }

    public function testGetSetHost(): void
    {
        $this->assertSame('http://google.com', $this->server_instance->getHost());
        $this->server_instance->setHost($host = 'https://www.some-host.com');
        $this->assertSame($host, $this->server_instance->getHost());
    }

    /**
     * @return void
     */
    public function testSetHostWithException(): void
    {
        $invalid_host = 'bla-bla.com';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Passed invalid server host [%s]', $invalid_host));

        $this->server_instance->setHost($invalid_host);
    }

    public function testGetSetPort(): void
    {
        $this->assertSame(ServerInterface::DEFAULT_PORT, $this->server_instance->getPort());
        $this->server_instance->setPort($port = 8080);
        $this->assertSame($port, $this->server_instance->getPort());
    }

    /**
     * @return void
     */
    public function testSetPortWithException(): void
    {
        $invalid_port = 99999;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Passed invalid port number [%d]', $invalid_port));

        $this->server_instance->setPort($invalid_port);
    }

    public function testGetSetTimeout(): void
    {
        $this->assertSame(ServerInterface::DEFAULT_TIMEOUT, $this->server_instance->getTimeout());
        $this->server_instance->setTimeout($timeout = 20);
        $this->assertSame($timeout, $this->server_instance->getTimeout());
    }

    /**
     * @return void
     */
    public function testSetTimeoutWithException(): void
    {
        $invalid_timeout = -1;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Passed invalid timeout [%d]', $invalid_timeout));

        $this->server_instance->setTimeout($invalid_timeout);
    }

    /**
     * Makes server instance
     *
     * @param string   $host
     * @param int|null $port
     * @param int|null $timeout
     *
     * @return ServerInterface
     */
    protected function makeInstance(string $host, ?int $port = null, ?int $timeout = null): ServerInterface
    {
        return new Server($host, $port, $timeout);
    }
}
