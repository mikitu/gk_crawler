<?php

namespace GkCrawler\Crawler;

use GkCrawler\Model\Country;
use GkCrawler\Model\City;
use GkCrawler\Crawler\Validation\SourceValidatorInterface;
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
     * @var
     */
    protected $validator;
    
    protected $client;
    /**
     * @var Model
     */
    private $source;

    /**
     * Source constructor.
     * @param Model $source
     * @param Model $dbModel
     */
    public function __construct(Model $source, Model $dbModel)
    {
        $this->sourceData = $source->toArray();
        $this->dbModel = $dbModel;
        $this->source = $source;
    }

    /**
     * @param Client $client
     */
    public function run(Client $client)
    {
        $this->client = $client;
        $res = $this->fetchData($client);
        $this->saveData($res);
        $this->log($res);
    }

    /**
     * @param array $data
     */
    protected function log(array $data)
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
            if (! $this->validator->validate($item)) {
                $this->validator->printErrorMessage($item);
                continue;
            };
            $this->save($k, $item);
        }
        $this->postSave();
    }

    /**
     * @param $index
     * @param array $item
     */
    public function save($index, array $item)
    {
        if (empty($item['city'])) {
            echo PHP_EOL . "City is empty!!! " . ($item['url'] ?? $item['name']). PHP_EOL;
            print_r($item);
            return;
        }
        $country_id = $this->getCountryId($item['country_code']);
        $city_id = $this->getCityId($country_id, $item['city']);
        $modelName = get_class($this->dbModel);
        $dbModel = new $modelName;
        if (empty($item['zipcode'])) {
            $item['zipcode'] = 'auto_' . $index;
        }
        if (empty($item['type'])) {
            $item['type'] = '';
        }
        $instance = $dbModel->firstOrNew([
            'source_id'     => $this->sourceData['id'],
            'country_id'    => $country_id,
            'city_id'       => $city_id,
            'zipcode'       => $item['zipcode'],
        ]);
        $type = $item['type'];
        if(! empty($instance->typology) && ! empty($type)) {
            $types = explode(',', $instance->typology);
            if(! in_array($type, $types)) {
                $types[] = $type;
            }
            $type = implode(',', $types);
        }
        $instance->fill([
            'source_id'     => $this->sourceData['id'],
            'country_id'    => $country_id,
            'city_id'       => $city_id,
            'name'          => $item['name'],
            'typology'      => $type,
            'address'       => $item['address'],
            'phone'         => $item['phone'],
            'zipcode'       => $item['zipcode'],
            'latitude'      => $item['latitude'],
            'longitude'     => $item['longitude'],
            'openinghours'  => $item['openinghours'],
        ])->save();
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

    public function done()
    {
        $this->source->parsed = true;
        $this->source->save();
    }

    /**
     * @param SourceValidatorInterface $validator
     */
    public  function addValidator(SourceValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    /**
     * @param Client $client
     * @return array
     */
    public function makeRequest(Client $client, $url)
    {
        $res = $client->request($this->sourceData['method'], $url);
        $body = json_decode($res->getBody(), true);
        $page_meta = $body['page_meta'];
        $body = $body['results'];
        return array($body, $page_meta, $res->getStatusCode());
    }

    /**
     * @param $body
     * @param $body1
     */
    protected function appendResults(&$body, $body1)
    {
        foreach ($body1 as $result) {
            $body[] = $result;
        }
    }

    /**
     * executed after data is saved in db
     */
    protected function postSave()
    {
    }
}
