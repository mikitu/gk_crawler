<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourBrasil extends Carrefour
{
    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        if (! isset($item['cidade'])) {
            return false;
        }
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['cidade'],
            'name'          => $item['nome'],
            'address'       => $item['logradouro'],
            'phone'         => $item['telefone'],
            'zipcode'       => $item['cep'],
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'type'          => $item['tipo'],
            'openinghours'  => '',
        ]);
    }

}