<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks;

use InvalidArgumentException;
use Illuminate\Contracts\Container\Container;
use AvtoDev\HealthChecks\Checks\CheckInterface;
use AvtoDev\HealthChecks\Results\ResultInterface;

class HealthChecks implements HealthChecksInterface
{
    /**
     * @var callable[]
     */
    protected $fabrics = [];

    /**
     * Create a new HealthChecks instance.
     *
     * @param string[]|array[] $checks
     * @param Container        $container
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $checks, Container $container)
    {
        foreach ($checks as $key => $value) {
            $class = \is_string($key)
                ? $key
                : $value;

            $options = \is_array($value)
                ? $value
                : [];

            if (\is_string($class) && \class_exists($class)) {
                $this->fabrics[$class] = static function () use ($container, $class, $options): CheckInterface {
                    return $container->make($class, ['options' => $options]);
                };
            } else {
                throw new InvalidArgumentException(sprintf('Passed invalid check classname'));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function classes(): array
    {
        return \array_keys($this->fabrics);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $check_class): bool
    {
        return \in_array($check_class, $this->classes(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $check_class): ResultInterface
    {
        return $this->make($check_class)->execute();
    }

    /**
     * @return array|ResultInterface[]
     */
    public function executeAll(): array
    {

    }

    /**
     * @param string $check_class
     *
     * @throws InvalidArgumentException
     *
     * @return CheckInterface
     */
    protected function make(string $check_class): CheckInterface
    {
        if (! $this->exists($check_class)) {
            throw new InvalidArgumentException("Check [$check_class] was not found");
        }

        return $this->fabrics[$check_class]();
    }
}
