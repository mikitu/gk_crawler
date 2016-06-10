<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 27/05/2016
 * Time: 14:50
 */

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GkCrawler\Model\Country;
use GkCrawler\Model\City;
use GuzzleHttp\Client;

class Kaufland extends Source
{
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $reqBody = unserialize($this->sourceData['data']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ['form_params' => $reqBody]);
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
            'country_code'  => $item['country'],
            'city'          => $item['city'],
            'name'          => $this->sourceData['name'],
            'type'          => '',
            'address'       => $item['street'],
            'phone'         => $item['telephone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['location']['latitude'],
            'longitude'     => $item['location']['longitude'],
            'openinghours'  => (string)$this->getOpeningHours($item['openinghours']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $openinghours = str_replace("\n", '', $openinghours);
        $openinghours = str_replace("> <", '><', $openinghours);
        preg_match_all('/<div class="stofi-days.*>(.*)<\/div><div class="stofi-times">(.*)<\/div>/iU', $openinghours, $matches, PREG_SET_ORDER);
        $ret = [];
        try {
            foreach ($matches as $match) {
                $ret[] = trim($match[1]) . ": " . trim(preg_replace('/ uhr/is', '', $match[2]));
            }
        } catch (Exception $e) {
            echo "ERROR: " . $openinghours;
        }
        return implode('; ', $ret);
    }
}