<?php

namespace GkCrawler\Crawler\Sources;


use Faker\Provider\zh_TW\DateTime;
use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class EnaFood extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $res = $client->request($this->sourceData['method'], $this->sourceData['url']);
        return [
            "status_code" => $res->getStatusCode(),
            "body" => json_decode($res->getBody(), true),
        ];
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        $item = $this->getDetails($item, 'http://www.enafood.gr/storelocator/stores?ids=' . $item['id']);
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['phone'] ?? '',
            'zipcode'       => $item['zip'] ?? '',
            'latitude'      => $item['lat'] ?? '',
            'longitude'     => $item['lng'] ?? '',
            'openinghours'  => $item['openinghours'],
            'type'          => $item['type']
        ]);
    }

    private function getDetails($item, $url)
    {
        $res = $this->client->request("GET", $url);
        $body = json_decode($res->getBody(), true)[0];
        $item['lat'] = $body['geoPoint']['latitude'];
        $item['lng'] = $body['geoPoint']['longitude'];
        $item['city'] = $body['address']['town'];
        $item['name'] = $body['name'];
        $item['address'] = $body['address']['line1'] . ' ' . $body['address']['line2'];
        $item['phone'] = $body['address']['phone'];
        $item['zip'] = $body['address']['postalCode'];
        $item['openinghours'] = $this->getOpeningTimes($body['openingHours']['groceryOpeningList']);
        $item['type'] = $body['storeType'];
        return $item;
    }

    private function getOpeningTimes($groceryOpeningList)
    {
        $hours = [];
        foreach ($groceryOpeningList as $key => $day) {
            if($key > 6) {
                break;
            }
            $d= (new \DateTime())->setTimestamp($day['openingTime'] / 1000);
            if($day['closed'] == true) {
                $hours[] = substr($d->format('D'), 0, 2) . ": closed";
            } else {
                $de = (new \DateTime())->setTimestamp($day['closingTime'] / 1000);
                $hours[] = substr($d->format('D'), 0, 2) . ": " . $d->format("H:i") . ' - ' .  $de->format("H:i");
            }
        }
        return implode('; ', $hours);
    }


}