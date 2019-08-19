<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Entities\Server;

class Server implements ServerInterface
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * Server constructor.
     *
     * @param string   $host
     * @param int|null $port
     * @param int|null $timeout
     */
    public function __construct(string $host, ?int $port = null, ?int $timeout = null)
    {
        $this->setHost($host);
        $this->setPort($port ?? self::DEFAULT_PORT);
        $this->setTimeout($timeout ?? self::DEFAULT_PORT);
    }

    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException
     */
    public function setHost(string $host): ServerInterface
    {
        if (\preg_match('~^https?://~i', $host) !== 1) {
            throw new \InvalidArgumentException(sprintf('Passed invalid server host [%s]', $host));
        }

        $this->host = $host;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException
     */
    public function setPort(int $port): ServerInterface
    {
        if ($port < 1 || $port > 65535) {
            throw new \InvalidArgumentException(sprintf('Passed invalid port number [%d]', $port));
        }

        $this->port = $port;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException
     */
    public function setTimeout(int $timeout): ServerInterface
    {
        if ($timeout < 0) {
            throw new \InvalidArgumentException(sprintf('Passed invalid timeout [%d]', $timeout));
        }

        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
