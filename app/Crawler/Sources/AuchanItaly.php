<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class AuchanItaly extends Source
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
            'longitude'     => $item['lng'],
            'openinghours'  =>$item['opening'],
        ]);
    }

    private function parse($body)
    {
        $body = $this->clean($body);
        preg_match_all('/var posMarker = new google\.maps\.Marker\(\{(.*)google\.maps\.event\.addListener\(posMarker,/iUs', $body, $match);
        $items = [];
        foreach($match[1] as $row) {
            $items[] = $this->parseItem($row);
        }
        return $items;
    }

    private function parseItem($row)
    {
        $pattern = '/LatLng\(([\d.]+),([\d.]+)\).*href="([^"]+)">.*font-size:12px;">([^<]+)<br\/>([^<]+)(<br\/>)?([^<]+)?/is';
        preg_match($pattern, $row, $match);
        $lat = $match[1];
        $lng = $match[2];
        $url = $match[3];
        $address = $match[4];
        $city = explode(' ', $match[5]);
        $zipcode = array_shift($city);
        array_pop($city);
        $city = $city[0];
        $phone = $match[7];
        return [
            'name'  => 'Auchan di ' . $city,
            'lat'   => $lat,
            'lng'   => $lng,
            'address'   => $address,
            'city'   => $city,
            'zipcode'   => $zipcode,
            'phone'   => $phone,
            'url'   => $url,
            'opening'   => $this->getDetails($url),

        ];
    }

    private function getDetails($url)
    {
        $days = [
            'LUNEDI' => 'Mo',
            'MARTEDI' => 'Tw',
            'MERCOLEDI' => 'We',
            'GIOVEDI' => 'Th',
            'VENERDI' => 'Fr',
            'SABATO' => 'Sa',
            'DOMENICA' => 'Su',
        ];
        $res = $this->client->request('GET', 'http://www.auchan.it' . $url);
        $body = $this->clean($res->getBody());
        $pattern = '/(<div style="float:left;" class="black">([^<]+)<\/div>&nbsp;&nbsp;&nbsp;<div style="float:right; padding-right:10px;">([^<]+)<\/div>)+/is';
        preg_match_all($pattern, $body, $matches);
        $hours = [];
        foreach ($matches[2] as $key => $day) {
            $hours[] = $days[$day] . ': ' . $matches[3][$key];
        }
        return implode('; ', $hours);
    }
}