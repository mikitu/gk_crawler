<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CbaPoland extends Source
{
    protected $client;
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $headers = unserialize($this->sourceData['headers']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ['headers' => $headers]);
        $body = json_decode($res->getBody(), true);
        $items = [];
        foreach($body['towns'] as $town) {
            if(empty($town['value'])) continue;
            $town['value'] = trim($town['value']);
            $town['value'] = urlencode($town['value']);
            echo $this->sourceData['url'] . 'town=' . $town['value'] . PHP_EOL;
            try {
                $res = $client->request($this->sourceData['method'], $this->sourceData['url'] . 'town=' . $town['value'], ['headers' => $headers]);
                if ($res->getStatusCode() == 200) {
                    $shops = json_decode($res->getBody(), true)['shops'];
                    foreach ($shops as $shop) {
                        if(empty($shop['geo_lat']) || empty($shop['geo_lng'])) continue;
                        print_r($shop);
                        $items[] = $shop;
                    }
                }
            } catch (\Exception $e) {}
        }
        return [
            "status_code" => 200,
            "body" => $items,
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
            'city'          => $item['town'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => '',
            'zipcode'       => $item['postcode'],
            'latitude'      => $item['geo_lat'],
            'longitude'     => $item['geo_lng'],
            'openinghours'  => '',
        ]);
    }

}