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
        foreach ($items as $item) {
            $details = $this->parseDetails($client, $item);
        }
        die;
        $res = $client->request($this->sourceData['method'], $url);
        return [
            "status_code" => $res->getStatusCode(),
            "body" => json_decode($this->parse($res->getBody()), true),
        ];
    }

    public function parseDetails($client, $item)
    {
        $res = $client->request($this->sourceData['method'], $item['url']);
        $body = $this->clean($res->getBody());
        <p class="telefono"><i class="fa fa-phone"></i> 0825-666081</p>
<tr>
    <td>Orari di apertura</td>
    <td>Dal Lunedì al Sabato 07:00-14:00-16:30-21:00. Domenica:08:00-13:30</td>
</tr>
    $(document).ready(function() {
        createGMap('pdv-map', 'DESPAR',
            'VIA ARC. PALOMBELLA,30/A, ACQUAVIVA',
            40.89582905460185, 16.838234784378074);
    });

        echo($body);die;
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        list($lat,$lon) = explode(',', $item['geocode']);
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['town'],
            'name'          => html_entity_decode($item['name']),
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => '',
            'latitude'      => $lat,
            'longitude'     => $lon,
            'openinghours'  => ''//$this->getOpeningHours($item['openinghours']),
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