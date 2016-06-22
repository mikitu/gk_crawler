<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LidlGermany extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $urls = explode('*', $this->sourceData['url']);
        $results = [];
        foreach($urls as $dsUrl) {
            $url = $dsUrl;
            echo $url . PHP_EOL;
            list($body, $status_code) = $this->makeRequest($client, $url);
            $this->appendResults($results, $body);
        }
        return [
            "status_code" => $status_code,
            "body" => $results,
        ];
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
//        print_r($item);die;
        if(empty($item['cityname'])) {
            $item['cityname'] = $item['CITY'];
        }
        echo $item['cityname'] . ' => ' . $item['name'] . PHP_EOL;
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['cityname'],
            'name'          => $item['name'],
            'address'       => $item['STREET'],
            'phone'         => '',
            'zipcode'       => $item['ZIPCODE'],
            'latitude'      => $item['X'],
            'longitude'     => $item['Y'],
            'openinghours'  => $this->getOpeningHours($item['openingTimesString']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        return str_replace('<br>', '; ', $openinghours);
    }

    public  function parse($body)
    {
        $body = $this->clean($body);
        preg_match('/salePoints = eval\((.*\])\);/is', $body, $matches);
        return json_decode($matches[1], true);
    }

    public function makeRequest(Client $client, $url)
    {
        $res = $client->request($this->sourceData['method'], $url);
        $body = $this->parse($res->getBody());
        return array($body, $res->getStatusCode());
    }

}