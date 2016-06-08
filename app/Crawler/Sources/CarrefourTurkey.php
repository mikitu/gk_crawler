<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourTurkey extends Source
{
    protected $client;
    protected $types = [
        27 => "Carrefour Market",
        26 => "Carrefour Hypermarket",
        28 => "Carrefour Express",
        29 => "Carrefour Express Convenience"
    ];
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $reqBody = unserialize($this->sourceData['data']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ["body" => $reqBody]);
        $body = json_decode($res->getBody(), true)['results'];
        return [
            "status_code" => $res->getStatusCode(),
            "body" => $body,
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
            'city'          => 'unknown_city',
            'name'          => $item['TableName'],
            'address'       => $item['Address'],
            'phone'         => $item['PhoneNumber'],
            'zipcode'       => '',
            'latitude'      => $item['Latitude'],
            'longitude'     => $item['Longitude'],
            'type'          => $item['SubCatName'],
            'openinghours'  => $item['OpenHours'],
        ]);
    }
    
}