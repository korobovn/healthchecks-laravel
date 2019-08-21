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
        $failed = false;

        foreach ($health_checks->classes() as $check_class) {
            $this->info(sprintf('Execute %s', $check_class));
            $result = $health_checks->execute($check_class);

            if ($result->passed()) {
                $this->info(sprintf('%s  PASS', $check_class));
            } else {
                $this->comment(sprintf('%s  FAIL [%s]', $check_class, $result->getErrorMessage()));
                $failed = true;
            }
        }

        return (int) $failed;
    }
}
