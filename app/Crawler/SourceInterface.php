<?php

namespace GkCrawler\Crawler;
use GuzzleHttp\Client;
interface SourceInterface
{
    public function run(Client $client);
    public function fetchData(Client $client);
    public function save(array $data);
}
