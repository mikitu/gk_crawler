<?php
/**
 * Created by PhpStorm.
 * User: mbucse
 * Date: 27/05/2016
 * Time: 14:50
 */

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class Kauffland extends Source
{

    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $reqBody = unserialize($this->data['data']);
        $res = $client->request($this->data['method'], $this->data['url'], ['form_params' => $reqBody]);
        return [
            "status_code" => $res->getStatusCode(),
            "body" => json_decode($res->getBody(), true),
        ];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        $this->dbModel->
        var_dump($data);
    }
}