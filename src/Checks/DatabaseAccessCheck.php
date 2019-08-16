<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Checks;

use Illuminate\Database\DatabaseManager;
use AvtoDev\HealthChecks\Results\ResultInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class DatabaseAccessCheck extends AbstractCheck
{
    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var DatabaseManager
     */
    protected $database_manager;

    /**
     * DatabaseAccessCheck constructor.
     *
     * @param DatabaseManager  $database_manager
     * @param ConfigRepository $config
     */
    public function __construct(DatabaseManager $database_manager, ConfigRepository $config)
    {
        $this->database_manager = $database_manager;
        $this->config           = $config;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $options = []): ResultInterface
    {
        $connections = \array_filter($options['connections']);

        if (\count($connections) === 0) {
            $connections = [null];
        }

        try {
            foreach ($connections as $connection) {
                $this->database_manager->connection($connection)->getPdo()->exec('SELECT 1;');
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->fail($e);
        }
    }
}
