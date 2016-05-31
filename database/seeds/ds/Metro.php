<?php

namespace seeds\ds;

class Metro
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Metro';
    public $country_code = '';
    public $url = '';
    public $urls = [
        'AT' => 'https://www.metro.at/services/StoreLocator/StoreLocator.ashx?lat=47.6783107&lng=11.1026238&language=de-AT&distance=50000000&limit=500',
        'BE' => 'https://www.makro.be/services/StoreLocator/StoreLocator.ashx?lat=50.846704&lng=4.346408&language=nl-BE&distance=50000000&limit=100',
        'BG' => 'https://www.metro.bg/services/StoreLocator/StoreLocator.ashx?lat=42.709328&lng=25.192418&language=bg-BG&distance=50000000&limit=500',
        'CN' => 'http://www.metro.com.cn/services/StoreLocator/StoreLocator.ashx?lat=26.5652268&lng=127.9729855&language=zh-CN&distance=500000000&limit=500',
        'HR' => 'https://www.metro-cc.hr/services/StoreLocator/StoreLocator.ashx?lat=44.4534473&lng=14.2255453&language=hr-HR&distance=50000000&limit=500',
        'CZ' => 'https://www.makro.cz/services/StoreLocator/StoreLocator.ashx?lat=49.7856629&lng=13.2319458&language=cs-CZ&distance=50000000&limit=500',
        'FR' => 'https://www.metro.fr/services/StoreLocator/StoreLocator.ashx?lat=46.1354274&lng=-2.2803237&language=fr-FR&distance=50000000&limit=500',
        'DE' => 'https://www.metro.de/services/StoreLocator/StoreLocator.ashx?lat=51.23735&lng=6.823282&language=de-DE&distance=150000000&limit=500',
        'HU' => 'https://www.metro.hu/services/StoreLocator/StoreLocator.ashx?lat=47.45796&lng=18.900283&language=hu-HU&distance=5000000&limit=500',
        'IN' => 'https://www.metro.co.in/services/StoreLocator/StoreLocator.ashx?lat=13.014745&lng=77.554861&language=en-IN&distance=12000000&limit=200',
        'IT' => 'https://www.metro.it/services/StoreLocator/StoreLocator.ashx?lat=42.5283255&lng=10.9113583&language=it-IT&distance=50000000&limit=500',
        'JP' => 'http://www.metro-cc.jp/services/StoreLocator/StoreLocator.ashx?lat=35.653912&lng=139.81433&language=ja-JP&distance=500000&limit=200',
        'KZ' => 'https://www.metro.com.kz/services/StoreLocator/StoreLocator.ashx?lat=43.25654&lng=76.92848&language=kk-KZ&distance=15000000&limit=100',
        'NL' => 'https://www.makro.nl/services/StoreLocator/StoreLocator.ashx?lat=52.1950986&lng=3.036571&language=nl-NL&distance=15000000&limit=500',
        'PK' => 'https://www.metro.pk/services/StoreLocator/StoreLocator.ashx?lat=33.649416&lng=73.02696&language=en&distance=1410065408&limit=100',
        'PL' => 'https://www.makro.pl/services/StoreLocator/StoreLocator.ashx?lat=52.44&lng=19.4&language=pl-PL&distance=150000000&limit=100',
        'PT' => 'http://www.makro.pt/services/StoreLocator/StoreLocator.ashx?lat=40.243584&lng=-8.43352&language=pt-PT&distance=10000000&limit=100',
        'RO' => 'https://www.metro.ro/services/StoreLocator/StoreLocator.ashx?lat=44.4267674&lng=26.1025384&language=ro-RO&distance=50000000&limit=500',
        'RU' => 'https://www.metro-cc.ru/services/StoreLocator/StoreLocator.ashx?lat=59.79911&lng=51.787263&language=ru-RU&distance=200000000&limit=500',
        'RS' => 'https://www.metro.rs/services/StoreLocator/StoreLocator.ashx?lat=44.865633&lng=20.34934&language=sr-Latn-RS&distance=10000000&limit=100',
        'SK' => 'http://www.metro.sk/services/StoreLocator/StoreLocator.ashx?lat=48.697868&lng=19.198927&language=sk-SK&distance=500000&limit=100',
        'ES' => 'https://www.makro.es/services/StoreLocator/StoreLocator.ashx?lat=40.4&lng=-3.6&language=es-ES&distance=10666000&limit=100',
        'TR' => 'https://www.metro-tr.com/services/StoreLocator/StoreLocator.ashx?lat=36.992068&lng=35.331669&language=tr-TR&distance=5000000&limit=300',
        'UA' => 'https://www.metro.ua/services/StoreLocator/StoreLocator.ashx?lat=50.3894927&lng=30.6309208&language=uk-UA&distance=50000000&limit=200',
    ];
    
}
