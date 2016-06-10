<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Crawler\Crawler;
use GkCrawler\Crawler\OutputInterface;
use GkCrawler\Crawler\SourceCollection;
use GkCrawler\Crawler\SourceFactory;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GkCrawler\Model\Source as Model;

class CrawlerCommand extends Command implements OutputInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:run';

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

        $dbSources = Model::All()->where('parsed', 0);

        foreach($dbSources as $dbSource) {
            $source = SourceFactory::create($dbSource->name, $dbSource);
            $collection->add($source);
        }
        (new Crawler($collection))->run(new Client(['allow_redirects' => true,]), $this);
    }
}
