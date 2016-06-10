<?php

namespace GkCrawler\Crawler;
use GkCrawler\Crawler\OutputInterface;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Crawler
{
    /**
     * @var SourceCollection
     */
    private $sources;

    /**
     * Crawler constructor.
     * @param \GkCrawler\Crawler\SourceCollection $sources
     */
    public function __construct(SourceCollection $sources)
    {
        $this->sources = $sources;
    }

    /**
     * @param Client $client
     * @param OutputInterface $output
     */
    public function run(Client $client, OutputInterface $output)
    {
        foreach ($this->sources as $source) {
            $output->info("Start: " . $source->getName() . ' ' . $source->getCountryCode());
            $source->run($client);
            $output->info("Done");
            $source->done();
        }
    }

    
}
