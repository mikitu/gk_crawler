<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Hannaford extends Source
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
        $pattern = '/var t = new Object\(\); t.id = "(?<id>[^"]+)"; t.name = "(?<name>[^"]+)"; t.number = "(?<number>[^"]+)"; t.address1 = "(?<address1>[^"]+)"; t.address2 = "(?<address2>[^"]+)?"; t.city = "(?<city>[^"]+)"; t.state = "(?<state>[^"]+)"; t.zip = "(?<zip>[^"]+)"; t.phone = "(?<phone>[^"]+)"; t.lat = (?<lat>[^;]+); t.lng =(?<lng>[^;]+); t.heading = "[^"]+"; t.pitch = "[^"]+"; t.zoom = "[^"]+"; storeArray/is';
        preg_match_all($pattern, $body, $matches, PREG_SET_ORDER);
        $items  = [];
        foreach ($matches as $match) {
            $details = $this->getDetails('http://www.hannaford.com/custserv/store_detail.jsp?viewStoreId=' . $match['id']);
            $items[] = [
                'name' => $match['name'],
                'address' => $match['address1'] . ' ' . $match['address2'],
                'city' => $match['city'],
                'zipcode' => $match['zip'],
                'lat' => $match['lat'],
                'lng' => $match['lng'],
                'type' => '',
                'openinghours' => $details['hours'],
                'phone' => $match['phone'],
            ];
        }

        return $items;
    }
    private function getDetails($url)
    {
        $res = $this->client->request("GET", $url);
        $body = $this->clean($res->getBody());
        $pattern ='/><div class="hoursDisplay store fl"><p class="sectionHeader">Store Hours<\/p><p>Sunday: <span>(?<su>[^<]+)<\/span><\/p><p>Monday: <span>(?<mo>[^<]+)<\/span><\/p><p>Tuesday: <span>(?<tu>[^<]+)<\/span><\/p><p>Wednesday: <span>(?<we>[^<]+)<\/span><\/p><p>Thursday: <span>(?<th>[^<]+)<\/span><\/p><p>Friday: <span>(?<fr>[^<]+)<\/span><\/p><p>Saturday: <span>(?<sa>[^<]+)<\/span><\/div>/is';
        preg_match($pattern, $body, $matches);
        $hours = [];
        if (! empty($matches)) {
            $hours[] = 'Mo: ' . str_replace('-', ' - ', $matches['mo']);
            $hours[] = 'Tu: ' . str_replace('-', ' - ', $matches['tu']);
            $hours[] = 'We: ' . str_replace('-', ' - ', $matches['we']);
            $hours[] = 'Th: ' . str_replace('-', ' - ', $matches['th']);
            $hours[] = 'Fr: ' . str_replace('-', ' - ', $matches['fr']);
            $hours[] = 'Sa: ' . str_replace('-', ' - ', $matches['sa']);
            $hours[] = 'Su: ' . str_replace('-', ' - ', $matches['su']);
        }
        $openinghours = implode('; ', $hours);
        return ['hours' => $openinghours];
    }
}