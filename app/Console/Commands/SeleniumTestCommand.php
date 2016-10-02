<?php

namespace GkCrawler\Console\Commands;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Console\Command;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use MongoDB\BSON\Javascript;

class SeleniumTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'selenium:testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing selenium webdriver';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = 'http://localhost:4444/wd/hub';
        $driver = RemoteWebDriver::create($host, DesiredCapabilities::firefox());
        $driver->get("https://www.tempocentar.com/lokator");
        $grad = $driver->findElement(WebDriverBy::id('grad'));
        $result = $driver->executeScript("return data");
        var_dump($result);
        $driver->quit();
    }
}
