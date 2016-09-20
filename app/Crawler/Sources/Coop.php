<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Coop extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $items = $this->makeRequest($client, $this->sourceData['url']);
        var_dump(count($items));
        $fullItems = [];
        foreach ($items as $item) {
            $details = $this->parseDetails($client, $item);
            if (! empty($details)) {
                $fullItems[] = array_merge($item, $details);
            }
        }
        return [
            "status_code" => 200,
            "body" => $fullItems,
        ];
    }

    /**
     * @param $client
     * @param $item
     * @return array
     */
    public function parseDetails($client, $item)
    {
        echo ".";
        $res = $client->request($this->sourceData['method'], $item['url']);
        $body = $this->clean($res->getBody());
        $pattern = '/class="store-map" data-lat="(?<lat>[^"]+)" data-lng="(?<lng>[^"]+)".*<ul class="store-info"><li><h1>[^<]+<\/h1><p>(?<address>[^>]+)<br \/>\s*(?<postcode>\d+),(?<city>[^>]+)<\/p><div class="columns">/i';
        preg_match($pattern, $body, $matches);
        if (empty($matches)) {
            echo PHP_EOL . " ******* FAILED URL " . $item['url'];die;
            return [];
        }
        return [
            'address' => trim($matches['address']),
            'postcode' => trim($matches['postcode']),
            'city' => trim($matches['city']),
            'latitude' => $matches['lat'],
            'longitude' => $matches['lng'],
        ];
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'type'          => $item['type'],
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => $item['postcode'],
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'openinghours'  => $item['hours'],
        ]);
    }

    /**
     * @param Client $client
     * @param $url
     * @param int $page
     * @return array
     */
    public function makeRequest(Client $client, $url, $page = 0)
    {
        $call_url = str_replace('[[page]]', $page, $url);
        $res = $client->request($this->sourceData['method'], $call_url);
        $body = $this->clean($res->getBody());
        return $this->parseStoreUrls($client, $url, $body, $page);
    }

    public function parseStoreUrls(Client $client, $url, $body, $page) {
        $body = json_decode($body);
        if (empty($body)) {
            return[];
        }
        $items = [];
        foreach ($body as $row) {
            $pattern = '/class="chain-icons (?<type>[^"]+)"><\/div><\/span><a href="(?<link>[^"]+)"[^>]*>\s*(?<title>[^<]+)<\/a>.*<a href="tel:(?<phone>[^"]+)">[^<]+<\/a><span>(?<hours>[^>]+)<\/span>/i';
            preg_match($pattern, $row, $matches);
            $items[] = [
                'url' => 'https://coop.no' . $matches['link'],
                'name' => $matches['title'],
                'phone' => $matches['phone'],
                'hours' => $matches['hours'],
                'type' => $matches['type']
            ];
        }
        if (empty($items)) {
            return [];
        }
        $page++;
        return array_merge($items, $this->makeRequest($client, $url, $page));
    }
}