<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class SparItaly extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $urls = explode("*", $this->sourceData['url']);
        $items = [];
        foreach($urls as $k => $url) {
            $items = array_merge($items, $this->makeRequest($client, $url));
        }
        $fullItems = [];
        foreach ($items as $item) {
            $details = $this->parseDetails($client, $item);
            if (! empty($details)) {
                $fullItems[] = array_merge($item, $details);
            }
        }
        return [
            "status_code" => 200,
            "body" => $fullItems,
        ];
    }

    public function parseDetails($client, $item)
    {
        $res = $client->request($this->sourceData['method'], $item['url']);
        $body = $this->clean($res->getBody());
        $pattern = '/<p class="telefono"><i class="fa fa-phone"><\/i>([^<]+)<\/p>.*<td>Orari di apertura<\/td><td>([^<]+)<\/td>.*createGMap\(\'pdv-map\', .*, ([\d.]+), ([\d.]+)\); }\);/iUs';
        preg_match($pattern, $body, $matches);
        if (empty($matches)) {
            return [];
        }
        array_shift($matches);
        return [
            'phone' => $matches[0],
            'openinghours' => $matches[1],
            'latitude' => $matches[2],
            'longitude' => $matches[3],
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
            'type'          => $item['type'],
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => '',
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'openinghours'  => $item['openinghours'],
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $all = $openinghours['all'];
        $op = [];
        foreach ($all as $val) {
            $op[] = $val['title'] . ' : ' . $val['hours'];
        }
        return implode('; ', $op);
    }
    public function makeRequest(Client $client, $url, $page = 1)
    {
        $call_url = $url;
        if ($page) {
            $call_url = str_replace('[[page]]', $page, $url);
        }
        $res = $client->request($this->sourceData['method'], $call_url);
        $body = $this->clean($res->getBody());
        return $this->parseStoreUrls($client, $url, $body, $page);
    }

    public function parseStoreUrls(Client $client, $url, $body, $page) {
        $pattern = '/<li class="pdv"><a href="(\/punto-vendita[^"]+)".*<h4>([^<]+)<\/h4><h5>([^<]+)<\/h5><span class="indirizzo"><i class="fa fa-map-marker"><\/i> ([^<]+)<\/span><span class="insegna" style="background-image:url\(\'\/Img\/logo-[^.]+\.png\'\);">([^<]+)<\/span>/iUs';
        preg_match_all($pattern, $body, $items, PREG_SET_ORDER);
        if (empty($items)) {
            return [];
        }
        array_walk($items, function(&$value) {
            array_shift($value);
            $arr = [
                'url' => 'http://www.mydespar.it' . $value[0],
                'city' => $value[1],
                'name' => $value[2],
                'address' => $value[3],
                'type' => $value[4]
             ];
            $value =  $arr;
        });
        $page++;
        return array_merge($items, $this->makeRequest($client, $url, $page));
    }
}