<?php

namespace seeds\ds;

class Coop
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Coop';
    public $country_code = 'NO';
    public $url = 'https://coop.no/StoreService/StoresInfoByChain/?chain=999&&page=[[page]]';
}
