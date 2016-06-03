<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourTurkey extends Source
{
    protected $client;
    protected $types = [
        27 => "Carrefour Market",
        26 => "Carrefour Hypermarket",
        28 => "Carrefour Express",
        29 => "Carrefour Express Convenience"
    ];
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $reqBody = unserialize($this->sourceData['data']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ["body" => $reqBody]);
        $body = json_decode($res->getBody(), true)['results'];
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
        echo " --- NU merge ---" .PHP_EOL; return false;
        if (! isset($item['Province'])) {
            print_r($item);die;
            return false;
        }
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item[''],
            'name'          => $item['TableName'],
            'address'       => $item['Address'],
            'phone'         => $item['PhoneNumber'],
            'zipcode'       => '',
            'latitude'      => $item['Latitude'],
            'longitude'     => $item['Longitude'],
            'type'          => $item['SubCatName'],
            'openinghours'  => $this->getOpeningHours($item['OpenHours']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        if (! empty($openinghours)) {
            print_r($openinghours);die;
        }
        return $openinghours;
    }

    private function grabDetails($url)
    {
        $url = mb_strtolower($url);
        $url = str_replace('http://www.carrefour.pl/sklepy/carrefour-', '', $url);
        $url = 'http://www.carrefour.pl/sklepy/carrefour-' . urlencode($url);
        echo $url . PHP_EOL;

        $res = $this->client->request('GET', $url);

        if ($res->getStatusCode() != 200) {
            return false;
        }
        $body = $res->getBody();
        $body = $this->clean($body);
        $phone = $this->parsePhone($body);
        return [
            'phone'   => $phone
        ];
    }

    private function parsePhone($body)
    {
        $pattern = '/<span itemprop="telephone"> (\d+)/s';
        preg_match($pattern, $body, $matches );
        if (!isset($matches[1])) {
            return '';
        }
        return trim($matches[1]);
    }

    /**
     * @param $body
     * @return mixed
     */
    private function clean($body)
    {
        $body = preg_replace("/\n\r/", "", $body);
        $body = preg_replace("/\n/", "", $body);
        $body = preg_replace("/\t/", " ", $body);
        $body = preg_replace("/\s+/", " ", $body);
        $body = preg_replace("/> </", "><", $body);
        return $body;
    }

}