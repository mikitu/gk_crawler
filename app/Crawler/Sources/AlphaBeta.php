<?php

namespace GkCrawler\Crawler\Sources;


use Facebook\WebDriver\WebDriverBy;
use GkCrawler\Crawler\SeleniumSource;
use GuzzleHttp\Client;

class AlphaBeta extends SeleniumSource
{

    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $guzzleHttpClient)
    {
        $this->client->get($this->sourceData['url']);
        $latitudes = $this->client->executeScript("return latitudes");
        $longitudes = $this->client->executeScript("return longitudes");
        $posNames = $this->client->executeScript("return posNames");
        $posDescription = $this->client->executeScript("return posDescription");
        $addressesLine1 = $this->client->executeScript("return addressesLine1");
        $addressesLine2 = $this->client->executeScript("return addressesLine2");
        $addressesTown = $this->client->executeScript("return addressesTown");
        $addressesPostCode = $this->client->executeScript("return addressesPostCode");
        $warehouses = $this->client->executeScript("return warehouses");
        $storeTypes = $this->client->executeScript("return storeTypes");
        $items = [];
        foreach ($latitudes as $key => $latitude) {
            $items[] = [
                'lat' => $latitude,
                'lng' => $longitudes[$key],
                'id' => $posNames[$key],
                'name' => $posDescription[$key]  ?? '',
                'address' => $addressesLine1[$key] . ' ' . $addressesLine2[$key],
                'city' => $addressesTown[$key] ?? '',
                'zip' => $addressesPostCode[$key] ?? '',
                'url' => 'http://www.ab.gr/storelocator/viewStoreDetail?poiName=' . $posNames[$key],
                'type' => $storeTypes[$key] ?? '',
            ];
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
        $item = $this->getDetails($item, $item['url']);
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
        $this->client->get($url);
        $tel = $this->client->findElement(WebDriverBy::xpath("//*[@id=\"wrap\"]/div[4]/div/div[2]/div/div[3]/div[1]/div/div[1]/section[1]/table/tbody/tr/td[3]/div/p"))->getText();
        $h = $this->client->findElements(WebDriverBy::xpath("//*[contains(@class, 'store-detail-timing') and contains(@class, 'grey-border-r')]"));
        $hours = "Sa: " . $h[0]->getText() .'; ';
        $hours .= "Su: " . $h[1]->getText() .'; ';
        $hours .= "Mo: " . $h[2]->getText() .'; ';
        $hours .= "Tu: " . $h[3]->getText() .'; ';
        $hours .= "We: " . $h[4]->getText() .'; ';
        $hours .= "Th: " . $h[5]->getText() .'; ';
        $hours .= "Fr: " . $h[6]->getText() .'; ';
        $item['phone'] = $tel;
        $item['openinghours'] = $hours;
        return $item;
    }


}