<?php

namespace seeds\ds;

class SparGetmany
{
    public $headers = [];
    public $data = [
        'rows' => 10000,
        'q' => 'indexName:ccgmSparExpress  AND lat_d:[46.68596765794024 TO 54.58428294205976] AND lng_d:[5.908951775674609 TO 15.255009424325294]',
        'fl' => 'angebote_s,art_s,beschreibung_s,id,kontakt_s,lat_d,lng_d,marktname_tlc,oeffnungszeiten_s,ort_tlc,plz_s,strasse_tlc,telefon_s',
    ];
    public $method = 'POST';
    public $name = 'Spar Germany';
    public $country_code = 'DE';
    public $url = 'http://edeka-food-service.de/GROSSMARKT/search.xml';
}
