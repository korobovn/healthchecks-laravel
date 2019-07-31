<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Commands;

use AvtoDev\HealthChecks\HealthChecksInterface;

class HealthCheckCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'health:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks application health';

    /**
     * Execute the console command.
     *
     * @param HealthChecksInterface $health_checks
     *
     * @return int
     */
    public function handle(HealthChecksInterface $health_checks): int
    {
        return 0;
    }
}
