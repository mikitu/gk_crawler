<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class DelhaizeBelgium extends Source
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
            'openinghours'  =>$item['openinghours'],
            'type'  =>$item['type'],
        ]);
    }

    private function parse($body)
    {
        $body = $this->clean($body);
        $pattern = '/<script type="text\/javascript">[^<]+name=\'(?<id>[^\']+)\';[^<]+descr=\'(?<name>[^\']+)\';[^<]+addr1="(?<addr1>[^\"]+)";[^<]+addr2="(?<addr2>[^\"]+)";[^<]+town=\'(?<city>[^\']+)\';[^<]+post=\'(?<postcode>[^\']+)\';[^<]+lat = (?<lat>\d+\.\d+);[^<]+lon = (?<lng>\d+\.\d+);[^<]+storeType=\'(?<type>[^\']+)\';[^<]+storeType\); <\/script>/';
        preg_match_all($pattern, $body, $matches, PREG_SET_ORDER);
        $items  = [];
        foreach ($matches as $match) {
            $details = $this->getDetails('http://shop.delhaize.be/en-be/storelocator/viewStoreDetail?poiName=' . $match['id']);
            $items[] = [
                'name' => $match['name'],
                'address' => $match['addr1'] . ' ' . $match['addr2'],
                'city' => $match['city'],
                'zipcode' => $match['postcode'],
                'lat' => $match['lat'],
                'lng' => $match['lng'],
                'type' => $match['type'],
                'openinghours' => $details['hours'],
                'phone' => $details['phone'],
            ];
        }

        return $items;
    }
    private function getDetails($url)
    {
        $res = $this->client->request("GET", $url);
        $body = $this->clean($res->getBody());
        $pattern ='/<span class="checkMonth" ><span class="checkDay"[^>]+>(?<day>[^<]+)<\/span>[^<]+<\/span>(<div class="[^"]+">)?<span( class="storeClosed")?>(?<hours>[^<]+)/is';
        preg_match_all($pattern, $body, $matches, PREG_SET_ORDER);
        $hours = [];
        foreach ($matches as $match) {
            $hours[] = substr($match['day'], 0, 2) . '.: ' . str_replace('-', ' - ', $match['hours']);
        }
        $su = array_shift($hours);
        $hours[] = $su;
        $openinghours = implode('; ', $hours);
        $phone = '';
        preg_match('/<strong>Telephone<\/strong><\/td><td>([^<]+)<\/td>/iUs', $body, $match);
        if (! empty($match[1])) {
            $phone = $match[1];
        }

        return ['hours' => $openinghours, 'phone' => $phone];
    }
}