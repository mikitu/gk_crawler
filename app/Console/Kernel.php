<?php

namespace GkCrawler\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
         Commands\CrawlerCommand::class,
         Commands\CrawlHospitalsCommand::class,
         Commands\CrawlProcessHospitalsCommand::class,
         Commands\CrawlProcessHospitalDetailsCommand::class,
         Commands\ValidateLatLongCommand::class,
         Commands\CrawlHospitalsGermanyCommand::class,
         Commands\CrawlEmbassyCommand::class,
         Commands\CrawlProcessEmbassyCommand::class,
         Commands\SeleniumTestCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
