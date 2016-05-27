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
    protected $data = [];
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
        $this->data = $data;
        $this->dbModel = $dbModel;
    }

    /**
     * @param Client $client
     */
    public function run(Client $client)
    {
        $res = $this->fetchData($client);
        $this->save($res);
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
     * @param array $data
     * @return mixed
     */
    public abstract function save(array $data);

}
