<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Penny extends Source
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
        $body = $res->getBody();
        $body = trim($body, '(');
        $body = trim($body, ';');
        $body = trim($body, ')');
        $body = json_decode($body, true);
        if (isset($body['d'])) {
            $body = $body['d'];
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
        $jp = $item['jsonPayload'];
        $tel = '';
        $hours = '';
        $pt = explode('|', $jp[1]);
        foreach ($pt as $val) {
            $el = explode(':', $val);
            if ($el[0] == 'p') {
                $tel = trim($el[1], '/');
            }
            if ($el[0] == 't') {
                $hours = trim($el[1], '/');
            }
        }
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'type'          => '',
            'address'       => $item['address1'] . ' ' . $item['address2'],
            'phone'         => $tel,
            'zipcode'       => $item['zip'],
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'openinghours'  => $hours,
        ]);
    }

}