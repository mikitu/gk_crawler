<?php

namespace seeds\ds;

class AuchanFrance
{
    public $headers = ['Referer' => 'http://www.auchan.fr/magasins/votremagasi'];
    public $data = [];
    public $method = 'GET';
    public $name = 'Auchan France';
    public $country_code = 'FR';
    public $url = 'http://api.woosmap.com/stores/?key=auchan-woos';
}
