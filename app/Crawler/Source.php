<?php

namespace GkCrawler\Crawler;

use GkCrawler\Model\Country;
use GkCrawler\Model\City;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

abstract class Source implements SourceInterface
{
    /**
     * @var array
     */
    protected $sourceData = [];
    /**
     * @var
     */
    protected $dbModel;

    /**
     * Source constructor.
     * @param array $data
     * @param $dbModel
     */
    public function __construct(array $data, Model $dbModel)
    {
        $this->sourceData = $data;
        $this->dbModel = $dbModel;
    }

    /**
     * @param Client $client
     */
    public function run(Client $client)
    {
        $res = $this->fetchData($client);
        $this->saveData($res);
        $this->log($res);
    }

    /**
     * @param array $data
     */
    private function log(array $data)
    {
        echo "save to history";
    }

    /**
     * @param Client $client
     * @return mixed
     */
    public abstract function fetchData(Client $client);

    /**
     * @param array $data array of items
     * @return mixed
     */
    public function saveData(array $data)
    {
        foreach ($data['body'] as $k => $item) {
            $item = $this->normalize($item);
            if (! $item) continue;
            $this->save($k, $item);
        }
    }
    public function save($index, array $item)
    {
        if (empty($item['city'])) {
            return;
        }
        $country_id = $this->getCountryId($item['country_code']);
        $city_id = $this->getCityId($country_id, $item['city']);
        $modelName = get_class($this->dbModel);
        $dbModel = new $modelName;
        if (empty($item['zipcode'])) {
            $item['zipcode'] = 'auto_' . $index;
        }
        $dbModel->updateOrCreate([
            'source_id'     => $this->sourceData['id'],
            'country_id'    => $country_id,
            'city_id'       => $city_id,
            'zipcode'       => $item['zipcode'],
        ], [
            'source_id'     => $this->sourceData['id'],
            'country_id'    => $country_id,
            'city_id'       => $city_id,
            'name'          => $item['name'],
            'typology'      => $item['type'],
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'openinghours'  => $item['openinghours'],
        ]);
    }

    /**
     * @param array $item
     * @return mixed
     */
    public abstract function normalize(array $item);

    public function getName()
    {
        return $this->sourceData['name'];
    }

    public function getCountryCode()
    {
        return $this->sourceData['country_code'];
    }

    /**
     * @param $country_id
     * @param $city_name
     * @return int | null
     */
    public function getCityId($country_id, $city_name)
    {
        $city = null;
        if ($city_name) {
            $city = (new City())->firstOrCreate(['country_id' => $country_id, 'name' => $city_name]);
        }
        if ($city) {
            return $city->id;
        }
        return null;
    }

    /**
     * @param $country_code
     * @return integer country_id
     */
    private function getCountryId($country_code)
    {
        $country = (new Country())->firstOrCreate(['country_code' => $country_code]);
        return $country->id;
    }

    /**
     * @param $body
     * @return mixed
     */
    protected function clean($body)
    {
        $body = preg_replace("/\n\r/", "", $body);
        $body = preg_replace("/\n/", "", $body);
        $body = preg_replace("/\t/", " ", $body);
        $body = preg_replace("/\s+/", " ", $body);
        $body = preg_replace("/> </", "><", $body);
        return $body;
    }

}
