<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Spar extends Source
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
            "body" => json_decode($this->parse($res->getBody()), true),
        ];
    }

    public function parse($data)
    {
        preg_match('/var storeDetailArr = new Array\((.*)\);/iUs', $data, $match);
        return '[' . $match[1] . ']';
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        list($lat,$lon) = explode(',', $item['geocode']);
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['town'],
            'name'          => html_entity_decode($item['name']),
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => '',
            'latitude'      => $lat,
            'longitude'     => $lon,
            'openinghours'  => ''//$this->getOpeningHours($item['openinghours']),
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