<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourTunisia extends Carrefour
{
    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        if (! isset($item['ville'])) {
            return false;
        }
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['ville'],
            'name'          => $item['magasin'],
            'address'       => $item['adresse'],
            'phone'         => $item['tel'],
            'zipcode'       => '',
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'type'          => '',
            'openinghours'  => $item['info'],
        ]);
    }

}