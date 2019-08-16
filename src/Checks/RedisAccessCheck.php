<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\ResultInterface;
use Exception;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Str;

class RedisAccessCheck extends AbstractCheck
{
    /**
     * @var RedisManager
     */
    protected $redis_manager;

    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * RedisCheck constructor.
     *
     * @param RedisManager     $redis_manager
     * @param ConfigRepository $config
     */
    public function __construct(RedisManager $redis_manager, ConfigRepository $config)
    {
        $this->redis_manager = $redis_manager;
        $this->config        = $config;
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
                $connection_name = $connection ?? 'default-connection';
                $redis_client = $this->redis_manager->connection($connection)->client();
                $key = sprintf('%s:%s', $connection_name, $value = Str::random());
                $redis_client->setex($key, 3, $value);
                if ($redis_client->get($key) !== $value) {
                    return $this->fail(
                        new Exception(sprintf('[%s] Value from redis are not same!', $connection_name))
                    );
                }
            }

            unset ($redis_client);

            return $this->success();
        } catch (Exception $e) {
            return $this->fail($e);
        }
    }
}
