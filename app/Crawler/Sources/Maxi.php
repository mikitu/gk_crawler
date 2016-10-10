<?php

namespace GkCrawler\Crawler\Sources;


use Facebook\WebDriver\WebDriverBy;
use GkCrawler\Crawler\SeleniumSource;
use GuzzleHttp\Client;

class Maxi extends SeleniumSource
{

    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $guzzleHttpClient)
    {
        $this->client->get($this->sourceData['url']);
        $items = $this->client->executeScript("return data");
die(var_dump($items));
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