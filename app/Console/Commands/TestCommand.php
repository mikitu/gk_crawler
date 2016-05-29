<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Crawler\Crawler;
use GkCrawler\Crawler\SourceCollection;
use GkCrawler\Crawler\SourceFactory;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GkCrawler\Model\Source as Model;
use GkCrawler\Crawler\Sources\Kauffland as Source;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $collection = new SourceCollection;

        $dbSources = Model::All()->toArray();

        foreach($dbSources as $dbSource) {
            $source = SourceFactory::create($dbSource['name'], $dbSource );
            $collection->add($source);
        }

        (new Crawler($collection))->run(new Client);
    }
}
