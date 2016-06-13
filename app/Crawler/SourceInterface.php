<?php

namespace GkCrawler\Crawler;
use GkCrawler\validator\SourceValidatorInterface;
use GuzzleHttp\Client;
interface SourceInterface
{
    public function run(Client $client);
    public function fetchData(Client $client);
    public function addValidator(SourceValidatorInterface $validator);
    public function save($index, array $data);
}
