<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class FoodLion extends Source
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
            "body" => json_decode($res->getBody(), true),
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
            'name'          => $item['title'],
            'address'       => $item['address'],
            'phone'         => $item['phoneNumber'] ?? '',
            'zipcode'       => $item['zip'] ?? '',
            'latitude'      => $item['lat'] ?? '',
            'longitude'     => $item['lng'] ?? '',
            'openinghours'  => implode('; ', $item['hours']),
            'type'          => ''
        ]);
    }

    private function getDetails($item, $url)
    {
        $res = $this->client->request("GET", $url);
        $body = $this->clean($res->getBody());
        preg_match('/<h1[^>]+>(?<name>[^<]+)<\/h1>/is', $body, $match);
        if (! empty($match['name'])) {
            $item['name'] = $match['name'];
        }
        $translated = [
            'Maandag' => 'Mo',
            'Dinsdag' => 'Tu',
            'Woensdag' => 'We',
            'Donderdag' => 'Th',
            'Vrijdag' => 'Fr',
            'Zaterdag' => 'Sa',
            'Zondag' => 'Su',
        ];
        $oh = [];
        preg_match_all('/itemprop="openingHours" content="[^"]+"><td class="[^"]+">([^<]+)<\/td><td class="[^"]+">([^<]+)<\/td><td>[^<]+<\/td><\/tr>/iUs', $body, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $oh[] = ($translated[$match[1]] ?? '') . ': ' . str_replace('&ndash;', '-', $match[2]);
        }
        $item['openinghours'] = implode(', ', $oh);
        return $item;
    }


}