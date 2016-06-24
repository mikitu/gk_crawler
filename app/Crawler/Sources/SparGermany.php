<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class SparGermany extends Source
{
    protected $client;
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $reqBody = unserialize($this->sourceData['data']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ['form_params' => $reqBody]);
        $body = json_decode($res->getBody(), true);
        $body = $body['response']['docs'];
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
            'city'          => $item['ort_tlc'],
            'name'          => $item['marktname_tlc'],
            'address'       => $item['strasse_tlc'],
            'phone'         => $item['telefon_s'],
            'zipcode'       => $item['plz_s'],
            'latitude'      => $item['lat_d'],
            'longitude'     => $item['lng_d'],
            'type'          => $item['art_s'],
            'openinghours'  => '',
        ]);
    }

}