<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class CbaHungary extends Source
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
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['A_VAROS'],
            'name'          => $item['P_NAME'],
            'address'       => $item['A_CIM'],
            'phone'         => $item['PS_PUBLIC_TEL'],
            'zipcode'       => $item['A_IRSZ'],
            'latitude'      => $item['PS_GPS_COORDS_LAT'],
            'longitude'     => $item['PS_GPS_COORDS_LNG'],
            'type'          => $item['PS_VEVOTIPUS'],
            'openinghours'  => $this->getOpeningHours($item),
        ]);
    }

    private function getOpeningHours($item)
    {
        $ret = "Mo: " . $this->getTime($item['PS_OPEN_FROM_1']) . ' - ' . $this->getTime($item['PS_OPEN_TO_1']) . '; ';
        $ret .= "Tu: " . $this->getTime($item['PS_OPEN_FROM_2']) . ' - ' . $this->getTime($item['PS_OPEN_TO_2']) . '; ';
        $ret .= "We: " . $this->getTime($item['PS_OPEN_FROM_3']) . ' - ' . $this->getTime($item['PS_OPEN_TO_3']) . '; ';
        $ret .= "Th: " . $this->getTime($item['PS_OPEN_FROM_4']) . ' - ' . $this->getTime($item['PS_OPEN_TO_4']) . '; ';
        $ret .= "Fr: " . $this->getTime($item['PS_OPEN_FROM_5']) . ' - ' . $this->getTime($item['PS_OPEN_TO_5']) . '; ';
        $ret .= "Sa: " . $this->getTime($item['PS_OPEN_FROM_6']) . ' - ' . $this->getTime($item['PS_OPEN_TO_6']) . '; ';
        $ret .= "Su: " . $this->getTime($item['PS_OPEN_FROM_7']) . ' - ' . $this->getTime($item['PS_OPEN_TO_7']);
        return $ret;
    }

    private function getTime($input)
    {
        $str = str_pad($input, 4, '0', STR_PAD_LEFT);
        $str = substr($str, 0, 2) . ':' . substr($str, 2, 2);
        return $str;
    }

}