<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Carrefour extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        $body = json_decode($res->getBody(), true);
        if (isset($body['results'])) {
            $body = $body['results'];
        }
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
        if (! isset($item['city'])) {
            return false;
        }
        if (isset($item['gps_latitude'])) {
            $item['position']['lat'] = $item['gps_latitude'];
        }
        if (isset($item['gps_longitude'])) {
            $item['position']['lng'] = $item['gps_longitude'];
        }
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['contact_phone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['position']['lat'],
            'longitude'     => $item['position']['lng'],
            'openinghours'  => $this->getOpeningHours($item['opening']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $openinghours = str_replace(':', ': ', $openinghours);
        $openinghours = str_replace(';', '; ', $openinghours);
        $openinghours = str_replace('-', ' - ', $openinghours);
        $openinghours = str_replace('H', ':', $openinghours);
        return $openinghours;
    }

}