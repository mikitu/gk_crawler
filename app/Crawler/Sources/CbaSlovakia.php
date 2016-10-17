<?php

namespace GkCrawler\Crawler\Sources;


use Facebook\WebDriver\WebDriverBy;
use GkCrawler\Crawler\SeleniumSource;
use GuzzleHttp\Client;

class CbaSlovakia extends SeleniumSource
{

    /**
     * @param Client $guzzleHttpClient
     * @return mixed
     */
    public function fetchData(Client $guzzleHttpClient)
    {
        $this->client->get($this->sourceData['url']);
        $coords = $this->client->executeScript("return coords");
        $items = [];
        foreach ($coords as $key => $coord) {
            list($lat, $lng, $name, $url) = $coord;
            $tmp = explode(',', $name);
            $city = array_shift($tmp);
            $address = implode(',', $tmp);
            $items[] = ['name' => $name, 'city' => $city, 'address' => $address, 'latitude' => $lat, 'longitude' => $lng, 'url' => $url];
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
        $item = $this->getDetails($item);
        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['city'],
            'name'          => $item['name'],
            'address'       => $item['address'],
            'phone'         => $item['phone'] ?? '',
            'zipcode'       => '',
            'latitude'      => $item['latitude'] ?? '',
            'longitude'     => $item['longitude'] ?? '',
            'openinghours'  => $item['openinghours'],
            'type'          => ''
        ]);
    }

    private function getDetails($item)
    {
        $this->client->get($item['url']);
        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[2]/td[2]';
        $tel = $this->client->findElement(WebDriverBy::xpath($xpath))->getText();
        $hours = [];
        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[3]/td[2]';
        $hours[] = 'Mo: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[4]/td[2]';
        $hours[] = 'Tu: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[5]/td[2]';
        $hours[] = 'We: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[6]/td[2]';
        $hours[] = 'Th: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[7]/td[2]';
        $hours[] = 'Fr: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[8]/td[2]';
        $hours[] = 'Sa: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $xpath = '//*[@id="content"]/div/div[1]/div[2]/table/tbody/tr[9]/td[2]';
        $hours[] = 'Su: ' . $this->client->findElement(WebDriverBy::xpath($xpath))->getText();

        $item['phone'] = $tel;
        $item['openinghours'] = implode('; ', $hours);
        return $item;
    }


}