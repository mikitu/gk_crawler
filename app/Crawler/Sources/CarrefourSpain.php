<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourSpain extends Source
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
        $body = $this->parseBody($res->getBody());
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
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => $item['postal'],
            'latitude'      => $item['lat'],
            'longitude'     => $item['lng'],
            'type'          => $item['category'],
            'openinghours'  => $this->getOpeningHours($item['hours']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        return implode('; ', $openinghours);
    }

    private function parseBody($body)
    {
        $items = [];
        $pattern = '/(<marker.*\/>)/isU';
        preg_match_all($pattern, $body, $matches, PREG_PATTERN_ORDER );

        foreach ($matches[1] as $marker) {
            $pattern = '/([^ ]+)="([^"]+)"/isU';
            preg_match_all($pattern, $marker, $matches1, PREG_PATTERN_ORDER );
            $items[] = $this->getMarkerInfo($matches1);
        }
        return $items;
    }

    /**
     * @param $marker
     * @param $item
     */
    private function getMarkerInfo($marker)
    {
        $item = [];
        foreach ($marker[1] as $key => $val) {
            if (!isset($item['hours'])) {
                $item['hours'] = [];
            }
            if (strstr($val, 'hours')) {
                if ($val == "hours1") {
                    $item['hours'][] = "Mo - Su: " . $this->translateEs($marker[2][$key]);
                }
                if ($val == "hours2") {
                    $item['hours'][] = "Holidays: " . $this->translateEs($marker[2][$key]);
                }
            } else {
                $item[$val] = $marker[2][$key];
            }
        }
        return $item;
    }

    private function translateEs($key)
    {
        $key = str_replace('de ', '', $key);
        $key = str_replace(' a ', ' - ', $key);
        return $key;
    }

}