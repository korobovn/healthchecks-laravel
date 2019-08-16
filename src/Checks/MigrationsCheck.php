<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\ResultInterface;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;

class MigrationsCheck extends AbstractCheck
{
    protected const COMMAND = 'migrate';
    protected const OPTIONS = ['--pretend' => 'true', '--force' => 'true'];

    /**
     * @var ConsoleKernel
     */
    protected $console_kernel;

    /**
     * MigrationsCheck constructor.
     *
     * @param ConsoleKernel $console_kernel
     */
    public function __construct(ConsoleKernel $console_kernel)
    {
        $this->console_kernel = $console_kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $options = []): ResultInterface
    {
        try {
            $this->console_kernel->call(self::COMMAND, self::OPTIONS);

            if (\mb_strpos($this->console_kernel->output(), 'Nothing to migrate.') === false) {
                return $this->fail(new \Exception('Not all migrations are migrated'));
            }

            return $this->success();
        } catch (\Exception $e) {
            return $this->fail($e);
        }
    }
}
