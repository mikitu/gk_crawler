<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourRomania extends Source
{
    protected $client;
    protected $types = [
        2 => "Carrefour Market",
        1 => "Carrefour Hypermarket",
        3 => "Carrefour Express",
    ];

    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        $body = $this->clean($res->getBody());
        preg_match('/<script> var coordonate_magazine = (.*); <\/script>/iUs', $body, $match);
        $body = json_decode($match[1], true);
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
        preg_match('/<a href="([^"]+)">([^<]+)<\/a>.*<p>([^<]+)<\/p>/iUs', $item[0], $matches);
        $item['lat'] = $item[1];
        $item['lng'] = $item[2];
        $item['url'] = $matches[1];
        $item['name'] = $matches[2];
        $address = explode(', ', $item[3]);
        array_pop($address); // county
        $city = array_pop($address);
        $item['address'] = implode(', ', $address);
        $item['city'] = $city;

        if (!isset($item['city'])) {
            echo $item['url'] . PHP_EOL;

            return false;
        }
        $details = $this->grabDetails('http://www.carrefour.ro/' . $item['url']);
        $item['contact_phone'] = '';
        $item['open'] = '';
        if ($details) {
            $item['contact_phone'] = $details['phone'];
            $item['open'] = $details['open'];
        }
        return array_map('trim', [
            'country_code' => $this->sourceData['country_code'],
            'city' => $item['city'],
            'name' => $item['name'],
            'address' => $item['address'],
            'phone' => $item['contact_phone'],
            'zipcode' => '',
            'latitude' => $item['lat'],
            'longitude' => $item['lng'],
            'openinghours' => $item['open'],
        ]);
    }

    private function grabDetails($url)
    {

        $res = $this->client->request('GET', $url);

        if ($res->getStatusCode() != 200) {
            return false;
        }
        $body = $res->getBody();
        $body = $this->clean($body);
        $phone = $this->parsePhone($body);
        if (! $phone) {
            echo "No phone found on: " . $url . PHP_EOL;
        }
        $openinghours = $this->parseOpeninghours($body);

        return [
            'phone' => $phone,
            'open' => $openinghours,
        ];
    }

    private function parseOpeninghours($body)
    {
        $pattern = '/<p class="t2"><b>([^<]+)<\/b> ([^<]+)/s';
        preg_match_all($pattern, $body, $matches, PREG_SET_ORDER);
        $ret = [];
        foreach ($matches as $row) {
            $row[1] = trim($row[1]);
            if (! in_array($row[1], ['Telefon:', 'Fax:', 'Email:'])) {
                $ret[] = trim($row[1]) . ' ' . trim($row[2]);
            }
        }
        return implode('; ', $ret);
    }

    private function parsePhone($body)
    {
        $pattern = '/<b>Telefon:<\/b>([^<]+)<\/p>/iUs';
        preg_match($pattern, $body, $matches);

        if (!isset($matches[1])) {
            return '';
        }
        return trim($matches[1]);
    }

}