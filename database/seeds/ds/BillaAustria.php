<?php

namespace seeds\ds;

class BillaAustria
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Billa Austria';
    public $country_code = 'AT';
    public $url = 'https://secureservice.rewe.co.at/filialservice/FilialJson.asmx/GetFilialenJson?shopCd=%27BI%27';
}
