<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class AuchanPoland extends Source
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
        $body = $this->parse($res->getBody());
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
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['lat'],
            'longitude'     => $item['long'],
            'openinghours'  => $this->getOpeningHours($item['openingtimes']),
        ]);
    }

    private function parse($body)
    {
        $body = $this->clean($body);
        preg_match('/var markers = (\[\[.*\]\]);/iUs', $body, $match);
        $result = json_decode($match[1], true);
        print_r($result);die;
        $items = [];
        foreach($result as $row) {
            if ($row[4] == 2 || $row[4] == 3) continue;
            $item = [];
            $item['latitude'] = $row[0];
            $item['longitude'] = $row[1];
            
            preg_match('/\/' . $row['sl'] . '">[^<]+<\/a><br \/>(\d+) (.*)<\/p>/iUs', $body, $matches);
            $row['zipcode'] = $matches[1];
            $row['address'] = $matches[2];
            preg_match('/<div class="mapInfo shopInfoBox" id="' . $row['id'] . '">.*,<br>([^>]+)<\/p>.*strong><br><p>(.*)<\/p><a.*<\/div><\/div>/iUs', $body, $matches);
            $row['city'] = $matches[1];
            $row['openingtimes'] = $matches[2];
            $row['phone'] = '';
            $items[] = $row;
        }
        return $items;
    }
    private function getOpeningHours($opening)
    {
        $pattern = '/([^:]+): ([\d]+[^\d]+[\d]+[^\d]+[\d]+[^\d]+[\d]+)\s?<br \/>([^:]+): ([\d]+[^\d]+[\d]+[^\d]+[\d]+[^\d]+[\d]+)/is';
        preg_match($pattern, $opening, $matches );
        if (! isset($matches[2])) {
            $pattern = '/([^:]+): ([\d]+[^\d]+[\d]+[^\d]+[\d]+[^\d]+[\d]+)<\/p><p>([^:]+): ([\d]+[^\d]+[\d]+[^\d]+[\d]+[^\d]+[\d]+)/is';
            preg_match($pattern, $opening, $matches );
        }
        if (! isset($matches[2])) {
            $pattern = '/([^:]+): ([\d]+[^\d]+[\d]+[^\d]+[\d]+[^\d]+[\d]+) <br \/>([^:]+): ([\d]+[^\d]+[\d]+[^\d]+[\d]+[^\d]+[\d]+)/is';
            preg_match($pattern, $opening, $matches );
        }
        if (! isset($matches[2])) {
            print_r($opening);
        }
        return 'Mo - Sa: ' . $matches[2] . '; Su: ' . $matches[4];
    }
}