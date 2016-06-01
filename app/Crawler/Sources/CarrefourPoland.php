<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourPoland extends Source
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
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        $body = json_decode($res->getBody(), true)['shops'];
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
        if (! isset($item['address']['city'])) {
            return false;
        }
        $details = $this->grabDetails('http://www.carrefour.pl' . $item['url']);
        $item['contact_phone'] = '';
        if ($details) {
            $item['contact_phone'] = $details['phone'];
        }
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['address']['city'],
            'name'          => $item['name'],
            'address'       => $item['address']['street'],
            'phone'         => $item['contact_phone'],
            'zipcode'       => $item['address']['code'],
            'latitude'      => $item['lat'],
            'longitude'     => $item['long'],
            'openinghours'  => $this->getOpeningHours($item['open']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $days = [1 => 'Su', 2 => 'Mo', 3 => 'Tu', 4 => 'We', 5 => 'Th', 6 => 'Fr', 7 => 'Sa'];
        $open = [];
        foreach ($openinghours as $key => $val) {
            $open[] = $days[$key] . ': ' . str_replace('.', ':', $val);
        }
        $openinghours = implode('; ', $open);
        $openinghours = str_replace('-', ' - ', $openinghours);
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