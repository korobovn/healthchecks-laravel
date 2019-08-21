<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Checks;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Redis\RedisManager;
use AvtoDev\HealthChecks\Results\ResultInterface;

class RedisAccessCheck extends AbstractCheck
{
    /**
     * @var RedisManager
     */
    protected $redis_manager;

    /**
     * RedisCheck constructor.
     *
     * @param RedisManager $redis_manager
     */
    public function __construct(RedisManager $redis_manager, array $options = [])
    {
        $this->redis_manager = $redis_manager;

        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        try {
            $connections = $this->options['connections'] ?? ['default'];

            if (! \is_array($connections)) {
                throw new \InvalidArgumentException(
                    sprintf('Connections option must be array, got %s', \gettype($connections))
                );
            }

            foreach ($connections as $connection) {
                $redis_client    = $this->redis_manager->connection($connection)->client();
                $key             = sprintf('%s:%s', $connection, $value = Str::random());
                $redis_client->setex($key, 3, $value);
                if ($redis_client->get($key) !== $value) {
                    return $this->fail(
                        new Exception(sprintf('[%s] Value received from redis are not same that was written!',
                            $connection))
                    );
                }
            }

            unset($redis_client);

            return $this->success();
        } catch (Exception $e) {
            return $this->fail($e);
        }
    }
}
