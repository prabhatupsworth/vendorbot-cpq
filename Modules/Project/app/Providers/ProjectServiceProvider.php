<?php

namespace Modules\Project\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

use Modules\Project\Repositories\SmtpRepository;
use Modules\Project\Interfaces\SmtpRepositoryInterface;


use Modules\Project\Repositories\GeoFilterRepository;
use Modules\Project\Interfaces\GeoFilterRepositoryInterface;

use Modules\Project\Repositories\ProjectRepository;
use Modules\Project\Interfaces\ProjectRepositoryInterface;

class ProjectServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Project';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'project';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
    public function register(): void
    {
        parent::register();

        $this->app->bind(

            ProjectRepositoryInterface::class,

            ProjectRepository::class

        );

        /**
         * SMTP Repository Binding
         */
        $this->app->bind(
            SmtpRepositoryInterface::class,
            SmtpRepository::class
        );

        /**
         * Geo Filter Repository Binding
         */
        $this->app->bind(

            GeoFilterRepositoryInterface::class,

            GeoFilterRepository::class

        );
    }
    /**
     * Define module schedules.
     *
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
