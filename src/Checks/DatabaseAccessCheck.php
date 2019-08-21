<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Checks;

use Illuminate\Database\DatabaseManager;
use AvtoDev\HealthChecks\Results\ResultInterface;

class DatabaseAccessCheck extends AbstractCheck
{
    /**
     * @var DatabaseManager
     */
    protected $database_manager;

    /**
     * DatabaseAccessCheck constructor.
     *
     * @param DatabaseManager $database_manager
     * @param array           $options
     */
    public function __construct(DatabaseManager $database_manager, array $options = [])
    {
        $this->database_manager = $database_manager;

        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        try {
            $connections = $this->options['connections'] ?? [$this->database_manager->getDefaultConnection()];

            if (! \is_array($connections)) {
                throw new \InvalidArgumentException(
                    sprintf('Connections option must be array, got %s', \gettype($connections))
                );
            }

            foreach ($connections as $connection) {
                $this->database_manager->connection($connection)->getPdo()->exec('SELECT 1;');
            }

            return $this->success();
        } catch (\Exception $e) {
            return $this->fail($e);
        }
    }
}
