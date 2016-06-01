<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Carrefour extends Source
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
        $body = json_decode($res->getBody(), true);
        if (isset($body['results'])) {
            $body = $body['results'];
        }
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
        if ($this->sourceData['country_code'] == 'IT') {
            $item = $this->translateIT($item);
        }
        if (! isset($item['city'])) {
            return false;
        }
        if (isset($item['gps_latitude'])) {
            $item['position']['lat'] = $item['gps_latitude'];
        }
        if (isset($item['gps_longitude'])) {
            $item['position']['lng'] = $item['gps_longitude'];
        }

        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['contact_phone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['position']['lat'],
            'longitude'     => $item['position']['lng'],
            'openinghours'  => $this->getOpeningHours($item['opening']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $openinghours = str_replace(':', ': ', $openinghours);
        $openinghours = str_replace(';', '; ', $openinghours);
        $openinghours = str_replace('-', ' - ', $openinghours);
        $openinghours = str_replace('H', ':', $openinghours);
        return $openinghours;
    }

    private function translateIT($item)
    {
        $item['position']['lat'] = $item['field_pdv_lat_value'];
        $item['position']['lng'] = $item['field_pdv_lng_value'];
        $item['city'] = $item['field_pdv_citta_value'];
        $item['name'] = $item['title'];
        $item['address'] = $item['field_pdv_indirizzo_value'];
        $item['zipcode'] = $item['field_pdv_cap_value'];

        $details = $this->grabDetails('http://www.carrefour.it' . $item['url']);
        $item['contact_phone'] = $details['phone'];
        $item['opening'] = $details['opening'];

        return $item;
    }

    private function grabDetails($url)
    {
        $res = $this->client->request('GET', $url);
        $body = $res->getBody();

        $opening = $this->parseOpening($body);
        echo $body; die;
    }

    private function parseOpening($body)
    {
        $pattern = '<ul class="tbold only_desktop">('
    }

}