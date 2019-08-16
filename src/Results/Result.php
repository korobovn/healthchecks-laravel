<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Results;

use Exception;

class Result implements ResultInterface
{
    /**
     * @var bool
     */
    protected $passed;

    /**
     * @var Exception|null
     */
    protected $exception;

    /**
     * @var string|null
     */
    protected $error_message;

    /**
     * Result constructor.
     *
     * @param Exception|null $exception
     */
    public function __construct(?Exception $exception = null)
    {
        $this->exception = $exception;
        $this->passed = $this->exception !== null;
        $this->error_message = $this->exception
            ? sprintf('[%s] %s', \get_class($this->exception), $this->exception->getMessage())
            : null;
    }

    /**
     * Determine if check is passed.
     *
     * @return bool
     */
    public function passed(): bool
    {
        return $this->passed;
    }

    /**
     * Get check error message.
     *
     * @return string|null Null if check was passed
     */
    public function getErrorMessage(): ?string
    {
        return $this->exception;
    }

    /**
     * Get check exception.
     *
     * @return Exception|null Null if check was passed
     */
    public function getException(): ?Exception
    {
        return $this->exception;
    }
}
