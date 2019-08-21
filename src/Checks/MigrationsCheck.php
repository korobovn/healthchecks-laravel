<?php

declare(strict_types=1);

namespace AvtoDev\HealthChecks\Checks;

use AvtoDev\HealthChecks\Results\ResultInterface;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;

class MigrationsCheck extends AbstractCheck
{
    /**
     * Migrate command signature
     *
     * @var string
     */
    protected const MIGRATE_COMMAND = 'migrate';

    /**
     * Migrate command options
     *
     * @var array
     */
    protected const MIGRATE_COMMAND_OPTIONS = ['--pretend' => 'true', '--force' => 'true'];

    /**
     * @var ConsoleKernel
     */
    protected $console_kernel;

    /**
     * MigrationsCheck constructor.
     *
     * @param ConsoleKernel $console_kernel
     * @param array         $options
     */
    public function __construct(ConsoleKernel $console_kernel, array $options = [])
    {
        $this->console_kernel = $console_kernel;

        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): ResultInterface
    {
        try {
            $this->console_kernel->call(self::MIGRATE_COMMAND, self::MIGRATE_COMMAND_OPTIONS);

            if (\mb_strpos($this->console_kernel->output(), 'Nothing to migrate.') === false) {
                return $this->fail(new \Exception('Not all migrations are migrated'));
            }

            return $this->success();
        } catch (\Exception $e) {
            return $this->fail($e);
        }
    }
}
