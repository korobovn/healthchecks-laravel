<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Results;

use Exception;

interface ResultInterface
{
    /**
     * Determine if check is passed.
     *
     * @return bool
     */
    public function passed(): bool;

    /**
     * Get check error message.
     *
     * @return string|null Null if check was passed
     */
    public function getErrorMessage(): ?string;

    /**
     * Get check exception.
     *
     * @return Exception|null Null if check was passed
     */
    public function getException(): ?Exception;
}
