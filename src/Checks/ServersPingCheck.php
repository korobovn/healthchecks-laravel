<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Entities\Server\Server;
use AvtoDev\HealthChecks\Results\ResultInterface;
use AvtoDev\HealthChecks\Entities\Server\ServerInterface;

class ServersPingCheck extends AbstractCheck
{
    /**
     * @var string
     */
    public const HOST_OPTION_KEY = 'host';

    /**
     * @var string
     */
    public const PORT_OPTION_KEY = 'port';

    /**
     * @var string
     */
    public const TIMEOUT_OPTION_KEY = 'timeout';

    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        if ($servers_array = ($this->options['servers'] ?? null)) {
            try {
                $servers = $this->makeServers($servers_array);
                foreach ($servers as $server) {
                    $this->pingServer($server);
                }

                return $this->success();
            } catch (\Exception $e) {
                return $this->fail($e);
            }
        } else {
            $this->fail(new \Exception('No one server item are provided. Check configuration.'));
        }
    }

    /**
     * Makes servers instances.
     *
     * @param array $servers_array
     *
     * @throws \InvalidArgumentException
     *
     * @return array|ServerInterface[]
     */
    protected function makeServers(array $servers_array): array
    {
        $servers = [];

        foreach ($servers_array as $server_config) {
            if (\is_string($server_config)) {
                $servers[] = new Server($server_config);
            } elseif (\is_array($server_config)) {
                $servers[] = new Server(
                    $server_config[static::HOST_OPTION_KEY] ?? '',
                    $server_config[static::PORT_OPTION_KEY] ?? null,
                    $server_config[static::TIMEOUT_OPTION_KEY] ?? ServerInterface::DEFAULT_TIMEOUT
                );
            } else {
                throw new \InvalidArgumentException('Passed wrong server config. Each server config must be string or array');
            }
        }

        return $servers;
    }

    /**
     * Ping server and returns response time in seconds.
     *
     * @param ServerInterface $server
     *
     * @throws \RuntimeException
     *
     * @return float
     */
    protected function pingServer(ServerInterface $server): float
    {
        $starttime = microtime(true);
        $socket    = fsockopen($server->getHost(), $server->getPort(), $errno, $errstr, $server->getTimeout());
        $stoptime  = microtime(true);

        if (! $socket) {
            throw new \RuntimeException(
                sprintf('It\'s seems server [%s:%d] is down', $server->getHost(), $server->getPort())
            );
        }

        fclose($socket);
        if ($errno !== null) {
            throw new \RuntimeException(
                sprintf('Ping server [%s:%d] failed [%s]', $server->getHost(), $server->getPort(), $errstr),
                $errno
            );
        }

        return floor(($stoptime - $starttime) * 1000);
    }
}
