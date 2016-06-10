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
        if (! isset($item['typology_name']) && isset($item['typology'])) {
            $item['typology_name'] = $item['typology'];
        }

        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'type'          => $item['typology_name'],
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
        $item['typology_name'] = $item['field_pdv_insegna_value'];

        return $item;
    }

    private function grabDetails($url)
    {
        echo $url . PHP_EOL;
        $opening = "";
        $phone = "";
        try {
            $res = $this->client->request('GET', $url);
            if ($res->getStatusCode() != 200) {
                return false;
            }
            $body = $res->getBody();
            $body = $this->clean($body);
            $opening = $this->parseOpening($body);
            $phone = $this->parsePhone($body);
        } catch (\Exception $e) {
            echo "\tERROR: " . $e->getCode() . " : " . $e->getMessage() . PHP_EOL;
        }
        return [
            'opening' => $opening,
            'phone'   => $phone
        ];
    }

    private function parseOpening($body)
    {
        $pattern = '/<span class="data">([^<]+)<\/span><span>([^<]+)<\/span>/is';
        preg_match_all($pattern, $body, $matches, PREG_PATTERN_ORDER );
        $openings = [];
        foreach ($matches[1] as $key => $day) {
            $openings[] = trim($day . ":" . str_replace(".", "H", trim($matches[2][$key])));
        }
        return implode(";", $openings);
    }

    private function parsePhone($body)
    {
        $pattern = '/<div class="address"><div class="txt">.*T\. (\d+)/s';
        preg_match($pattern, $body, $matches );
        if (!isset($matches[1])) {
            return '';
        }
        return trim($matches[1]);
    }
    
}