<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class SevenEleven extends Source
{
    protected $client;
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        $body = $this->parse($res->getBody());
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

        $openingHours = $this->parseOpeningHours($item['hours']);
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => $item['postalcode'],
            'latitude'      => $item['lat'],
            'longitude'     => $item['lng'],
            'openinghours'  => $openingHours,
        ]);
    }

    private function parse($body)
    {
        $body = $this->clean($body);
        preg_match('/var location_data =(\[.*\]);/iUs', $body, $match);
        return json_decode($match[1], true);
    }

    private function parseOpeningHours($hours)
    {
        // {Mandag|00:00-24:00}{Tirsdag|00:00-24:00}{Onsdag|00:00-24:00}{Torsdag|00:00-24:00}{Fredag|00:00-24:00}{Lørdag|00:00-24:00}{Søndag|00:00-24:00}
        $hours = str_replace('}{', ';', $hours);
        $hours = str_replace('|', ': ', $hours);
        $hours = trim($hours, '{');
        $hours = trim($hours, '}');
        $hours = str_replace('Mandag', 'Mo.', $hours);
        $hours = str_replace('Tirsdag', 'Tu.', $hours);
        $hours = str_replace('Onsdag', 'We.', $hours);
        $hours = str_replace('Torsdag', 'Th.', $hours);
        $hours = str_replace('Fredag', 'Fr.', $hours);
        $hours = str_replace('Lørdag', 'Sa.', $hours);
        $hours = str_replace('Søndag', 'Su.', $hours);
        return $hours;
    }
}