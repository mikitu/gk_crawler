<?php

namespace seeds\ds;

class Carrefour
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Carrefour';
    public $country_code = '';
    public $url = '';
    public $urls = [
        'FR' => 'http://storereference.prd2.fr.carrefour.com//stores/js-api/store.json?parameters[lang]=fr&parameters[map]=true&parameters[macro]=true&parameters[min_cluster]=5000&parameters[store_bounds]=29.027322603152346,-15.198331156544668,51.30999039179263,18.90323134345533&parameters[store_services]=&parameters[store_types]=2263,13,134,2266&parameters[opendate]=&parameters[delta_distance]=100000&parameters[channel]=portailfr',
        'BE' => 'http://storelocator.prod.be.carrefour.eu/fr/stores/js-api/store.jsonp?parameters[lang]=fr&parameters[typology]=&parameters[store_bounds]=47.98957015879512,-4.960203277246137,53.539995276401584,13.606691254003863',
    ];
    
}
