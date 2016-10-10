<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 07/10/2016
 * Time: 13:13
 */

namespace GkCrawler\Crawler;


use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class SeleniumSource extends Source
{
    /*
     * @var Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected $client;

    public function __construct(Model $source, Model $dbModel)
    {
        parent::__construct($source, $dbModel);
        $host = 'http://localhost:4444/wd/hub';
        $client = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
        $this->client = $client;
    }

    public function run(Client $client)
    {
        $res = $this->fetchData($client);
        $this->saveData($res);
        $this->log($res);
    }
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        // TODO: Implement fetchData() method.
    }

    /**
     * @param array $item
     * @return mixed
     */
    public function normalize(array $item)
    {
        // TODO: Implement normalize() method.
    }

    /**
     * executed after data is saved in db
     * close browser
     */
    protected function postSave()
    {
        $this->client->close();
    }

}