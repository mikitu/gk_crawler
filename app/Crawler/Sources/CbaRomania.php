<?php

namespace GkCrawler\Crawler\Sources;


use Facebook\WebDriver\WebDriverBy;
use GkCrawler\Crawler\SeleniumSource;
use GuzzleHttp\Client;

class CbaRomania extends SeleniumSource
{

    /**
     * @param Client $guzzleHttpClient
     * @return mixed
     */
    public function fetchData(Client $guzzleHttpClient)
    {
        $this->client->get($this->sourceData['url']);
        $latlngscript = 'coords = new Array(); for(var i in latlng){coords.push({\'lat\': latlng[i].lat(), \'lng\' : latlng[i].lng()});}; return coords';
        $latlng = $this->client->executeScript($latlngscript);
        $linfoWindows = $this->client->executeScript("info = new Array(); for(var i in infoWindows){info.push(infoWindows[i].content);}; return info;");
        $details = $this->parseDetails($linfoWindows);
        foreach ($latlng as $key => $coord) {
            $items[] = array_merge($coord, $details[$key]);
        }
        
        return [
            "status_code" => 200,
            "body" => $items,
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
            'city'          => $item['city'],
            'name'          => $item['title'],
            'address'       => $item['address'] . ' ' . $item['address1'],
            'phone'         => $item['phone'] ?? '',
            'zipcode'       => $item['zipcode'] ?? '',
            'latitude'      => $item['lat'] ?? '',
            'longitude'     => $item['lng'] ?? '',
            'openinghours'  => '',
            'type'          => ''
        ]);
    }

    private function parseDetails($content)
    {
        $items = [];
        foreach($content as $row) {
            $pattern = '%<h2 class="com_locator_title"><a href="(?<url>[^"]+)">(?<title>[^<]+)</a></h2><span class="line_item address">(?<address>[^<]*)</span>(<br/>)?<span class="line_item address2">(?<address2>[^<]*)</span> <span class="line_item city">(?<city>[^<]+)</span>, <span class="line_item state">[^<]*</span> <span class="line_item postalcode">(?<zipcode>[^<]*)</span><span class="line_item phone">(?<phone>[^<]*)</span>%';
            preg_match($pattern, $row, $match);
            $item = [];
            $item['url'] = $match['url'] ?? '';
            $item['title'] = $match['title'] ?? '';
            $item['address'] = $match['address'] ?? '';
            $item['address1'] = $match['address1'] ?? '';
            $item['city'] = $match['city'] ?? '';
            $item['zipcode'] = $match['zipcode'] ?? '';
            $item['phone'] = $match['phone'] ?? '';
            $items[] = $item;
        }
        return $items;
    }


}