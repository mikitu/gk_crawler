<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class BillaAustria extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        $body = trim($res->getBody(), '(');
        $body = trim($body, ');');
//        print_r(json_decode($body, true));die;
        return [
            "status_code" => $res->getStatusCode(),
            "body" => json_decode($body, true)['d'],
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
            'address'       => trim($item['address1'] . ' ' . $item['address2']),
            'phone'         => '',
            'zipcode'       => $item['zip'],
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'openinghours'  => $this->getOpeningHours($item['jsonPayload'][1]),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $openinghours = preg_replace('/(^.*t:)/is', '', $openinghours);
        $openinghours = str_replace('<br>', '; ', $openinghours);
        $openinghours = str_replace(' Uhr', '', $openinghours);

        return $openinghours;
    }

}