<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CarrefourTaiwan extends Source
{
    protected $client;
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $reqBody = unserialize($this->sourceData['data']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ['form_params' => $reqBody]);
        $body = json_decode($res->getBody(), true);
        $body = json_decode($body[1]['data'], true)['results'];
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
        if (! isset($item['city'])) {
            return false;
        }
        $item['opening'] = $this->grabDetails('http://www.carrefour.com.tw/store/' . urlencode($item['clean_url']));
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['contact_phone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['position']['lat'],
            'longitude'     => $item['position']['lng'],
            'type'          => $item['typology_id'],
            'openinghours'  => $item['opening'],
        ]);
    }

    private function grabDetails($url)
    {
        echo $url . PHP_EOL;
        $res = $this->client->request('GET', $url);
        if ($res->getStatusCode() != 200) {
            return false;
        }
        $body = $res->getBody();
        $body = $this->clean($body);

        $opening = $this->parseOpening($body);
        return  $opening;
    }

    private function parseOpening($body)
    {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($body);
        $xpath = new \DOMXpath($doc);
        $data = $xpath->query('//*[@id="store-opening-block"]/div[3]/div[1]');
        $body = $doc->saveXML($data->item(0));

        $pattern = '/pening-schedule-label">([^<]+)<\/span>([^<]+)<br\/>/iUs';
        preg_match_all($pattern, $body, $matches, PREG_PATTERN_ORDER );
        $openings = [];
        foreach ($matches[1] as $key => $day) {
            $day = $this->translateCn(trim($day));
            $hour = trim($matches[2][$key]);
            $hour = preg_replace('/[^\d -:]+/', '', $hour);
            $openings[] = trim($day . ": " . $hour);
        }
        return implode("; ", $openings);
    }


    private function translateCn($str)
    {
        switch ($str) {
            case "週一 :": return "Mo";
            case "週二 :": return "Tw";
            case "週三 :": return "We";
            case "週四 :": return "Th";
            case "週五 :": return "Fr";
            case "週六 :": return "Sa";
            case "週日 :": return "Su";
        }
        return $str;
    }

}