<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class PennyGermany extends Source
{
    protected $client;
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $cities = $this->getCities();
        $full_body = [];
        foreach($cities as $city) {
            $url = $this->sourceData['url'] . $city;
            $res = $client->request($this->sourceData['method'], $url);
            $body = $res->getBody();
            $body = json_decode($body, true);
            $str = $city;
            if (!empty($body['markets'])) {
                $str .= ': ' . count($body['markets']);
                $full_body = array_merge($full_body, $body['markets']);
            }
            echo $str.PHP_EOL;
        }
        return [
            "status_code" => 200,
            "body" => $full_body,
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
            'type'          => '',
            'address'       => $item['address'],
            'phone'         => '',
            'zipcode'       => $item['zip'],
            'latitude'      => $item['lat'],
            'longitude'     => $item['lng'],
            'openinghours'  => $item['openingTime'],
        ]);
    }

    private function getCities()
    {
        $res = file_get_contents('https://raw.githubusercontent.com/David-Haim/CountriesToCitiesJSON/master/countriesToCities.json');
        $cities = json_decode($res, true);
        return $cities['Germany'];
    }

}