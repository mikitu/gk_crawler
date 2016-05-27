<?php

namespace GkCrawler\Crawler;
use GkCrawler\Crawler\SourceCollection;
use GuzzleHttp\Client;
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
     */
    public function run(Client $client)
    {
        foreach ($this->sources as $source) {
            $source->run($client);
        }
    }

    
}
