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
use GkCrawler\Model\SourceData;
use GuzzleHttp\Client;

class Kauffland extends Source
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
     * @return mixed
     */
    public function save(array $item)
    {
        $country = (new Country())->firstOrCreate(['country_code' => $item['country']]);
        $city = (new City())->firstOrCreate(['country_id' => $country->id, 'name' => $item['city']]);
        $modelName = get_class($this->dbModel);
        $dbModel = new $modelName;
        $dbModel->updateOrCreate([
            'source_id'     => $this->sourceData['id'],
            'country_id'    => $country->id,
            'city_id'       => $city->id,
            'zipcode'       => $item['zipcode'],
        ], [
            'source_id'     => $this->sourceData['id'],
            'country_id'    => $country->id,
            'city_id'       => $city->id,
            'address'       => $item['street'],
            'phone'         => $item['telephone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['location']['latitude'],
            'longitude'     => $item['location']['longitude'],
        ]);
    }
}