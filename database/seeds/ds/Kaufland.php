<?php

namespace seeds\ds;
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 29/05/2016
 * Time: 09:06
 */
class Kaufland
{
    public $name = 'Kaufland';
    public $headers = [];
    public $data = [
        'filtersettings' => '{"filtersettings":{"area":"63.56702565784306,159.94135559999995,-3.121032063136179,-93.18364440000005"},"location":{"latitude":37.0532142703462,"longitude":33.37885559999993},"clienttime":"20160529105147"}',
        'loadStores' => 'true',
        'locale' => 'EN',
    ];
    public $url = 'http://www.kaufland.de/Storefinder/finder';
    public $method = 'POST';
    public $country_code = '';
}