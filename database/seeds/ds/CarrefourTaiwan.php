<?php

namespace seeds\ds;

class CarrefourTaiwan
{
    public $headers = [];
    public $data = [
        "query" => "http://store-reference.carrefour.com.tw//stores/js-api/store.json?parameters[lang]=zh-hans&parameters[map]=true&parameters[macro]=false&parameters[min_cluster]=2&parameters[store_bounds]=21.14520371582982,117.94626367187493,26.174400310033057,126.40573632812493&parameters[store_services]=&parameters[store_types]=&parameters[openday]=&parameters[opendate]=&parameters[opentime]=&parameters[open24h]="
    ];
    public $method = 'POST';
    public $name = 'Carrefour Taiwan';
    public $country_code = 'TW';
    public $url = 'http://www.carrefour.com.tw/store/ws-request/';
}
