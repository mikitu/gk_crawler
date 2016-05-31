<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Metro extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        return [
            "status_code" => $res->getStatusCode(),
            "body" => json_decode($res->getBody(), true)['stores'],
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
            'address'       => $item['street'] . ', ' . $item['hnumber'],
            'phone'         => $item['telnumber'],
            'zipcode'       => $item['zip'],
            'latitude'      => $item['lat'],
            'longitude'     => $item['lon'],
            'openinghours'  => $this->getOpeningHours($item['openinghours']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $all = $openinghours['all'];
        $op = [];
        foreach ($all as $val) {
            $op[] = $val['title'] . ' : ' . $val['hours'];
        }
        return implode('; ', $op);
    }

}