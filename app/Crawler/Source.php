<?php

namespace GkCrawler\Crawler;

use GkCrawler\Crawler\SourceInterface;
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
        foreach ($data['body'] as $item) {
            $this->save($item);
        }
    }
    /**
     * @param array $item
     * @return mixed
     */
    public abstract function save(array $item);

    public function getName()
    {
        return $this->sourceData['name'];
    }
}
