<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class EtosNetherlands extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        $response = $res->getBody();
        $ValidJson = preg_replace("/(?<!\"|'|\w)([a-zA-Z0-9_]+?)(?!\"|'|\w)\s?:/", "\"$1\":", $response);
        return [
            "status_code" => $res->getStatusCode(),
            "body" => json_decode($ValidJson, true),
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
            'name'          => $item['format'] . ' ' . $item['street'],
            'address'       => $item['street'] . ', ' . $item['housenr'],
            'phone'         => $item['phone'] ?? '',
            'zipcode'       => $item['zip'] ?? '',
            'latitude'      => $item['lat'] ?? '',
            'longitude'     => $item['lng'] ?? '',
            'openinghours'  => $this->getOpeningHours($item['hours']),
            'type'          => $item['format']
        ]);
    }

    private function getOpeningHours($hours) {
        if(! is_array($hours)) {
            return '';
        }
        $days = [];
        foreach ($hours as $day) {
            if (! isset($day['U'])) {
                continue;
            }
            $d = new \DateTime($day['D']);
            $days[] = $d->format('D') . '.: ' .
                substr($day['F'], 0, 2) . ':' . substr($day['F'], 2, 2) .
                ' - ' .
                substr($day['U'], 0, 2) . ':' . substr($day['U'], 2, 2) ;
        }
        return implode('; ', $days);
    }

}