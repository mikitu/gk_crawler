<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Lidl extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $urls = explode('*', $this->sourceData['url']);
        foreach($urls as $dsUrl) {
            $url = $dsUrl . '&$skip=0';
            echo $url . PHP_EOL;

            list($body, $status_code) = $this->makeRequest($client, $url);
            $have_results = true;
            $offset = 0;

            while ($have_results) {
                $offset += 50;
                $url = $dsUrl . '&$skip=' . $offset;
                echo $url . PHP_EOL;
                list($body1,) = $this->makeRequest($client, $url);
                if ($body1) {
                    $this->appendResults($body, $body1);
                } else {
                    $have_results = false;
                }
            }
        }
        return [
            "status_code" => $status_code,
            "body" => $body,
        ];
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        $city = preg_replace('/\([^\)]+\)$/is', '', $item['Locality']);
        $name = ! empty($item['ShownStoreName']) ? $item['ShownStoreName'] : "Lidl " . $city;
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $city,
            'name'          => $name,
            'address'       => $item['AddressLine'],
            'phone'         => '',
            'zipcode'       => $item['PostalCode'],
            'latitude'      => $item['Latitude'],
            'longitude'     => $item['Longitude'],
            'openinghours'  => $this->getOpeningHours($item['OpeningTimes']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        return str_replace('<br>', '; ', $openinghours);
    }
    public function makeRequest(Client $client, $url)
    {
        $res = $client->request($this->sourceData['method'], $url);
        $body = json_decode($res->getBody(), true);
        $body = $body['d']['results'];
        return array($body, $res->getStatusCode());
    }

}