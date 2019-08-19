<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Entities\Server;

/**
 * Interface ServerInterface.
 */
interface ServerInterface
{
    /**
     * Default timeout value (in seconds).
     *
     * @var int
     */
    public const DEFAULT_TIMEOUT = 5;

    /**
     * Default port value.
     *
     * @var int
     */
    public const DEFAULT_PORT = 80;

    /**
     * Set server host.
     *
     * @param string $host
     *
     * @return mixed
     */
    public function setHost(string $host): self;

    /**
     * Return server host.
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Set server port.
     *
     * @param int $port
     *
     * @return mixed
     */
    public function setPort(int $port): self;

    /**
     * Return server port.
     *
     * @return int|null
     */
    public function getPort(): ?int;

    /**
     * Set timeout (in seconds).
     *
     * @param int $timeout
     *
     * @return mixed
     */
    public function setTimeout(int $timeout): self;

    /**
     * Return timout (in seconds).
     *
     * @return int|null
     */
    public function getTimeout(): ?int;
}