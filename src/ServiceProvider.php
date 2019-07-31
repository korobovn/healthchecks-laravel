<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks;

use Illuminate\Routing\Router;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Get config root key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName(): string
    {
        return \basename(static::getConfigPath(), '.php');
    }

    /**
     * Returns path to the configuration file.
     *
     * @return string
     */
    public static function getConfigPath(): string
    {
        return __DIR__ . '/../config/healthchecks.php';
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->initializeConfigs();
        $this->registerService();

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    /**
     * @param ConfigRepository $config
     * @param Router           $router
     *
     * @return void
     */
    public function boot(ConfigRepository $config, Router $router): void
    {
        $route = $config->get(static::getConfigRootKeyName() . '.route');

        if (\is_string($route) && $route !== '') {
            $router->get($route, [
                'uses' => Controllers\HealthChecksController::class . '@check',
                'as'   => 'healthcheck',
            ]);
        }
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs(): void
    {
        $this->mergeConfigFrom(static::getConfigPath(), static::getConfigRootKeyName());

        $this->publishes([
            \realpath(static::getConfigPath()) => config_path(\basename(static::getConfigPath())),
        ], 'config');
    }

    /**
     * @return void
     */
    protected function registerService(): void
    {
        $this->app->bind(HealthChecksInterface::class, static function (Container $container): HealthChecksInterface {
            /** @var ConfigRepository $config */
            $config = $container->make(ConfigRepository::class);

            $root = static::getConfigRootKeyName();

            return new HealthChecks(
                (array) $config->get("{$root}.checks"),
                (array) $config->get("{$root}.groups"),
                $container
            );
        });
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->app->singleton('command.health.check', static function (Container $container): Command {
            return $container->make(Commands\HealthCheckCommand::class);
        });

        $this->commands('command.health.check');
    }
}
