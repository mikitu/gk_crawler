<?php

namespace seeds\ds;

class Penny
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Penny';
    public $country_code = '';
    public $url = '';
    public $urls = [
        'RO' => 'http://service.pyro.rewe.co.at/filialservice/filialjson.asmx/getfilialenjson?shopcd=%22P5%22',
        'HU' => 'http://service.hu.rewe.co.at/filialservice/filialjson.asmx/getfilialenjson?shopcd=%22P2%22',
        'IT' => 'http://service.pyit.rewe.co.at/filialservice/filialjson.asmx/getfilialenjson?shopcd=%22P1%22',
        'CZ' => 'http://service.pycz.rewe.co.at/filialservice/filialjson.asmx/getfilialenjson?shopcd=%22P3%22',
        'AT' => 'https://secureservice.rewe.co.at/filialservice/filialjson.asmx/getfilialenjson?shopcd="PE"',

    ];
    
}
