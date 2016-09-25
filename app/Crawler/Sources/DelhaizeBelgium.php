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
        $address = explode(',', $item['address']);
        $city = trim(array_pop($address));
        $zipcode = trim(array_pop($address));
        $address = implode(',', $address);
        $latlong = explode(';', str_replace(',', ';', $item['latlong']));
        if (strstr($latlong[0],'Lat')) {
            $lat = trim(str_replace('Lat', '', $latlong[0]));
            $long = trim(str_replace('Long', '', $latlong[1]));
        } else {
            $lat = trim(str_replace('Lat', '', $latlong[1]));
            $long = trim(str_replace('Long', '', $latlong[0]));
        }
//        $lat = str_replace('. ', '', $lat);
        $lat = str_replace('.:', '', $lat);
        $lat = preg_replace('/[^\d.]+/', '', $lat);
//        $long = str_replace('. ', '', $long);
        $long = str_replace('.:', '', $long);
        $long = preg_replace('/[^\d.]+/', '', $long);
        if (! $city) {
            return false;
        }

        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $city,
            'name'          => $item['name'],
            'address'       => $address,
            'phone'         => $item['phones'],
            'zipcode'       => $zipcode,
            'latitude'      => trim($lat),
            'longitude'     => trim($long),
            'openinghours'  =>$item['hours'],
        ]);
    }

    private function parse($body)
    {
        $body = $this->clean($body);
        $pattern = '/<script type="text\/javascript">[^<]+name=\'(?<id>[^\']+)\';[^<]+descr=\'(?<name>[^\']+)\';[^<]+addr1="(?<addr1>[^\"]+)";[^<]+addr2="(?<addr2>[^\"]+)";[^<]+town=\'(?<city>[^\']+)\';[^<]+post=\'(?<postcode>[^\']+)\';[^<]+lat = (?<lat>\d+\.\d+);[^<]+lon = (?<lng>\d+\.\d+);[^<]+storeType=\'(?<type>[^\']+)\';[^<]+storeType\); <\/script>/';
        preg_match_all($pattern, $body, $matches, PREG_SET_ORDER);


        print_r($matches);die;
        return json_decode($match[1], true);
    }
}