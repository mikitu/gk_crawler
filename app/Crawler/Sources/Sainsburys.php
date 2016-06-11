<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Sainsburys extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $url = $this->sourceData['url'] . '&offset=0';
        echo $url . PHP_EOL;

        list($body, $page_meta, $status_code) = $this->makeRequest($client, $url);
        $have_results = true;
        $offset = $page_meta['offset'];

        while ($have_results) {
            $url  =  $this->sourceData['url'] . "&offset=" . ($offset + 50);
            echo $url . PHP_EOL;
            list($body1, $page_meta1, ) = $this->makeRequest($client, $url);
            if ($body1) {
                $offset = $page_meta1['offset'];
                $this->appendResults($body, $body1);
            } else {
                $have_results = false;
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
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['contact']['city'],
            'name'          => $item['name'],
            'type'          => $item['store_type'],
            'address'       => $item['contact']['address1'],
            'phone'         => $item['contact']['telephone'],
            'zipcode'       => $item['contact']['post_code'],
            'latitude'      => $item['location']['lat'],
            'longitude'     => $item['location']['lon'],
            'openinghours'  => $this->getOpeningHours($item['opening_times']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $days = [6 => 'Su', 0 => 'Mo', 1 => 'Tu', 2 => 'We', 3 => 'Th', 4 => 'Fr', 5 => 'Sa'];
        $open = [];
        foreach ($openinghours as $key => $val) {
            $open[] = $days[$key] . ': ' . $val['start_time'] . ' - ' . $val['end_time'];
        }
        return implode('; ', $open);
    }

    /**
     * @param Client $client
     * @return array
     */
    private function makeRequest(Client $client, $url)
    {
        $res = $client->request($this->sourceData['method'], $url);
        $body = json_decode($res->getBody(), true);
        $page_meta = $body['page_meta'];
        $body = $body['results'];
        return array($body, $page_meta, $res->getStatusCode());
    }

    private function appendResults(&$body, $body1)
    {
        foreach ($body1 as $result) {
            $body[] = $result;
        }
    }

}