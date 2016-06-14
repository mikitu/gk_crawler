<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Crawler\Crawler;
use GkCrawler\Crawler\OutputInterface;
use GkCrawler\Crawler\SourceCollection;
use GkCrawler\Crawler\SourceFactory;
use GkCrawler\Crawler\Validation\LatLongValidator;
use GkCrawler\Model\Source;
use GkCrawler\Model\SourceData;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GkCrawler\Model\Source as Model;

class ValidateLatLongCommand extends Command implements OutputInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:lat-long';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate latitude and longitude';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $collection = SourceData::all()->toArray();
        $errors = 0;
        foreach($collection as $row) {
            $validator = new LatLongValidator();
            if(! $validator->isValid($row)) {
                $errors++;
                echo str_repeat('=', 20) . PHP_EOL;
                print_r($row);
                echo str_repeat('=', 20) . PHP_EOL;
            }
        }
        if($errors) {
            $this->error($errors . " fount");
        }
    }
}
