<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Tests\Checks;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use AvtoDev\HealthChecks\Checks\CheckInterface;
use AvtoDev\HealthChecks\Checks\DatabaseAccessCheck;
use InvalidArgumentException;
use Mockery\Mock;
use PDO;

/**
 * Class DatabaseAccessCheckTest.
 *
 * @covers \AvtoDev\HealthChecks\Checks\DatabaseAccessCheck<extended>
 */
class DatabaseAccessCheckTest extends AbstractCheckTestCase
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        $pdo = $this->getPdoMock();

        $connection = $this->getConnectionMock($pdo);

        $this->getDatabaseManagerMock($connection);

        // Test default connection
        $this->check_instance = $this->makeInstance();
        $result = $this->check_instance->execute();
        $this->assertTrue($result->passed());

        // Test configured connection
        $this->check_instance = $this->makeInstance(['connections' => ['valid-connection']]);
        $result = $this->check_instance->execute();
        $this->assertTrue($result->passed());

        // Test not configured connection
        $this->check_instance = $this->makeInstance(['connections' => ['invalid-connection']]);
        $result = $this->check_instance->execute();
        $this->assertFalse($result->passed());
        $this->assertInstanceOf(InvalidArgumentException::class, $result->getException());
        $this->assertSame(
            '[InvalidArgumentException] Database [invalid-connection] not configured.',
            $result->getErrorMessage()
        );
    }

    /**
     * @return void
     */
    public function testExecuteWithInvalidOptions(): void
    {
        $this->check_instance = $this->makeInstance(['connections' => 'foo']);

        $result = $this->check_instance->execute();

        $this->assertFalse($result->passed());
        $this->assertInstanceOf(InvalidArgumentException::class, $result->getException());
        $this->assertSame(
            '[InvalidArgumentException] Connections option must be array, got string',
            $result->getErrorMessage()
        );
    }

    /**
     * @inheritdoc
     */
    protected function makeInstance(array $options = []): CheckInterface
    {
        return $this->app->make(DatabaseAccessCheck::class, ['options' => $options]);
    }

    /**
     * Mocks PDO object and swap it in DI container
     *
     * @return PDO
     */
    protected function getPdoMock(): PDO
    {
        return $this->mock(PDO::class, function (PDO $pdo): void {
            /** @var Mock $pdo */
            $pdo->makePartial()
                ->expects('exec')
                ->twice()
                ->withArgs(['SELECT 1;'])
                ->andReturn(1);
        });
    }

    /**
     * Mocks Connection object and swap it in DI container
     *
     * @param PDO $pdo
     *
     * @return Connection
     */
    protected function getConnectionMock(PDO $pdo): Connection
    {
        return $this->mock(
            Connection::class,
            function (Connection $connection) use ($pdo): void {
                /** @var Mock $connection */
                $connection->makePartial()
                    ->expects('getPdo')
                    ->twice()
                    ->andReturn($pdo);
            });
    }

    /**
     * Mocks DatabaseManager object and swap it in DI container
     *
     * @param Connection $connection
     *
     * @return DatabaseManager
     */
    protected function getDatabaseManagerMock(Connection $connection): DatabaseManager
    {
        return $this->mock(
            DatabaseManager::class,
            function (DatabaseManager $manager) use ($connection): void {
                /** @var Mock $manager */
                $manager->makePartial()
                    ->expects('getDefaultConnection')
                    ->andReturn('default-connection')
                    ->getMock()
                    // Mock connection() method for "default-connection"
                    ->expects('connection')
                    ->withArgs(['default-connection'])
                    ->andReturn($connection)
                    ->getMock()
                    // Mock connection() method for "valid-connection"
                    ->expects('connection')
                    ->withArgs(['valid-connection'])
                    ->andReturn($connection)
                    ->getMock()
                    // Mock connection() method for "invalid-connection"
                    ->expects('connection')
                    ->withArgs(['invalid-connection'])
                    ->andThrow(new InvalidArgumentException('Database [invalid-connection] not configured.'));
            });
    }
}
