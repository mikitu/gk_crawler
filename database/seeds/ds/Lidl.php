<?php

namespace seeds\ds;

class Lidl
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Lidl';
    public $country_code = '';
    public $url = '';
    public $urls = [
        'UK' => 'https://spatial.virtualearth.net/REST/v1/data/588775718a4b4312842f6dffb4428cff/Filialdaten-UK/Filialdaten-UK?spatialFilter=nearby(51.50642013549805,-0.12721000611782074,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=Argt0lKZTug_IDWKC5e8MWmasZYNJPRs0btLw62Vnwd7VLxhOxFLW2GfwAhMK5Xg',
        'FR' => 'https://spatial.virtualearth.net/REST/v1/data/717c7792c09a4aa4a53bb789c6bb94ee/Filialdaten-FR/Filialdaten-FR?spatialFilter=nearby(46.096961975097656,2.6636500358581543,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AgC167Ojch2BCIEvqkvyrhl-yLiZLv6nCK_p0K1wyilYx4lcOnTjm6ud60JnqQAa',
        'BE' => 'https://spatial.virtualearth.net/REST/v1/data/2be5f76f36e8484e965e84b7ee0cd1b1/Filialdaten-BE/Filialdaten-BE?spatialFilter=nearby(50.84270095825195,4.378109931945801,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&&key=AvGfUYinH_I7qdNZWDlXTHHysoytHWqkqZpxHBN9Z0Z0YLQup0u6qZoB8uQXUW_p',
        'BG' => 'https://spatial.virtualearth.net/REST/v1/data/04982a582660451a8e08b705855a1008/Filialdaten-BG/Filialdaten-BG?spatialFilter=nearby(42.688819885253906,23.32975959777832,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AkK9Xgxa6n1ly8c3xz1ntR6ojGGT3h-hys5yW7P9xHpJS2FycVLoYxLo_eeFR69o',
        'ES' => [
            'https://spatial.virtualearth.net/REST/v1/data/b5843d604cd14b9099f57cb23a363702/Filialdaten-ES/Filialdaten-ES?spatialFilter=nearby(28.409500122070312,-16.548799514770508,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AjhJAzQQN7zhpMcZcJinxel86P600c6JcsHsyNjlqpO7MhjrPO-lcpDGHF9jNZOw',
            'https://spatial.virtualearth.net/REST/v1/data/b5843d604cd14b9099f57cb23a363702/Filialdaten-ES/Filialdaten-ES?spatialFilter=nearby(40.847060,-3.339844,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AjhJAzQQN7zhpMcZcJinxel86P600c6JcsHsyNjlqpO7MhjrPO-lcpDGHF9jNZOw'
        ],
        'CZ' => 'https://spatial.virtualearth.net/REST/v1/data/f6c4e6f3d86d464088f7a6db1598538e/Filialdaten-CZ/Filialdaten-CZ?spatialFilter=nearby(50.12691879272461,14.456720352172852,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AiNNY2F5r0vNd6fJFLwr-rT5fPEDBzibjcQ0KMyUalKrIqaoM8HUlNAMEFFkBEv-',
        'DK' => 'https://spatial.virtualearth.net/REST/v1/data/9ca2963cb5f44aa3b4c241fed29895f8/Filialdaten-DK/Filialdaten-DK?spatialFilter=nearby(56.14739990234375,8.958939552307129,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AsaaAZuUgeIzOb829GUz0a2yjzX0Xw1-OTmjH_27CS5ilYr5v9ylNxg4rQSRhh8Z',
        'GR' => 'https://spatial.virtualearth.net/REST/v1/data/c1070f3f97ad43c7845ab237eef704c0/Filialdaten-GR/Filialdaten-GR?spatialFilter=nearby(38.88779830932617,22.443099975585938,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AjbddE6Qo-RdEfEZ74HKQxTGiCSM4dEoDL5uGGCiw7nOWaQiaKWSzPoGpezAfY_x',
        'HR' => 'https://spatial.virtualearth.net/REST/v1/data/d82c19ca83104facab354f376bf4312b/Filialdaten-HR/Filialdaten-HR?spatialFilter=nearby(45.490501403808594,16.385499954223633,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AoX_2ZTHnC3kIskrKATT7ZcheF4L7vHMCaHTqcTpjmaOm3kIGwASRnEqLpeH7_-S',
        'IE' => 'https://spatial.virtualearth.net/REST/v1/data/94c7e19092854548b3be21b155af58a1/Filialdaten-RIE/Filialdaten-RIE?spatialFilter=nearby(53.333099365234375,-6.248889923095703,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AvlHnuUnvOF2tIm9bTeXIj9T4YvpuerURAEX2uC8YKY3-1Q9cWJpmxVM_tqiduGt',
        'IT' => 'https://spatial.virtualearth.net/REST/v1/data/a360ccf2bf8c442da306b6eb56c638d7/Filialdaten-IT/Filialdaten-IT?spatialFilter=nearby(41.90304946899414,12.495800018310547,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AotMQpa96W8m5_F4ayN9WYBaEQLlxtI3ma8VpOWubmVHTOdZmmKoXjZ8IGLnratj',
        'LT' => 'https://spatial.virtualearth.net/REST/v1/data/8a2167d4bd8a47d9930fc73f5837f0bf/Filialdaten-LT/Filialdaten-LT?spatialFilter=nearby(54.69062042236328,25.26983070373535,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AkEdcFBe-gxNmSmCIpOKE_KuLBZv-NHRKY_ndbJKiVvc0ramz4hZKta-rZRpuNZS',
        'LU' => 'https://spatial.virtualearth.net/REST/v1/data/805eed5281bb4c9a89b17eb9e9fc41c7/Filialdaten-LU/Filialdaten-LU?spatialFilter=nearby(49.59989929199219,6.117720127105713,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=Amyc-zSJ0JCdCZyNd8Oooim-qFTdBkz7JplOmU5c4aJ-dbdWlr8jrnQ9dTXVg9MQ',
        'HU' => 'https://spatial.virtualearth.net/REST/v1/data/4c781cd459b444558df3d574f082358d/Filialdaten-HU/Filialdaten-HU?spatialFilter=nearby(47.482818603515625,19.063039779663086,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=Ao1GqKj4R8CqJrqpewEs49enx3QSzeWBPtSei353drKi3WWHOzPad_qzp3Fn7qs0',
        'NL' => 'https://spatial.virtualearth.net/REST/v1/data/067b84e1e31f4f71974d1bfb6c412382/Filialdaten-NL/Filialdaten-NL?spatialFilter=nearby(51.952598571777344,5.853419780731201,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=Ajsi91aW1OJ9ikqcOGadJ74W0D94pBKQ9Gha57tI6vXTTZZi1lwUuTXD2xDA-i7B',
        'AT' => 'https://spatial.virtualearth.net/REST/v1/data/d9ba533940714d34ac6c3714ec2704cc/Filialdaten-AT/Filialdaten-AT?spatialFilter=nearby(47.5625,14.244600296020508,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=Ailqih9-jVv2lUGvfCkWmEFxPjFBNcEdqZ3lK_6jMMDDtfTYu60SwIaxs32Wtik2',
        'PL' => 'https://spatial.virtualearth.net/REST/v1/data/f4c8c3e0d96748348fe904413a798be3/Filialdaten-PL/Filialdaten-PL?spatialFilter=nearby(52.53219985961914,19.141799926757812,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AnZ7UrM33kcHeNxFJsJ6McC4-iAx6Mv55FfsAzmlImV6eJ1n6OX4zfhe2rsut6CD',
        'PT' => 'https://spatial.virtualearth.net/REST/v1/data/e470ca5678c5440aad7eecf431ff461a/Filialdaten-PT/Filialdaten-PT?spatialFilter=nearby(39.60300064086914,-8.402509689331055,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=55&$format=json&key=Ahu0_AMpxF4eh7QlrRMfkhtrPnAKxYItqztODUDyRvuE4TzajeGVOxJSIZ6PUoR_',
        'RO' => 'https://spatial.virtualearth.net/REST/v1/data/5fa0193e1e0c4496aed1acb4af59f35b/Filialdaten-RO/Filialdaten-RO?spatialFilter=nearby(46.22079849243164,24.781999588012695,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=55&$format=json&key=An0oYvlZIU9SiOXJnlxv88zCVWyKYv4Z59Uv77KTalXIp--H9F_cg8vioBxxF5im',
        'SI' => 'https://spatial.virtualearth.net/REST/v1/data/541ab265ccaa471896a644d46a803e8f/Filialdaten-SI/Filialdaten-SI?spatialFilter=nearby(46.06050109863281,14.516599655151367,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=55&$format=json&key=As7USYkXX-Ev0Di15XD2jM8zJkdRJ0fVdPC0cT62tfCxqY_S-6wctLO3MH74F-Bt',
        'SK' => 'https://spatial.virtualearth.net/REST/v1/data/018f9e1466694a03b6190cb8ccc19272/Filialdaten-SK/Filialdaten-SK?spatialFilter=nearby(48.584800720214844,19.131500244140625,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownAddressLine,ShownStoreName&$top=50&$format=json&key=AqN50YiXhDtZtWqXZcb7nWvF-4Xc9rg9IXd6YWepqk4WnlmbvD-NV3KHA3A0dOtw',
        'CH' => 'https://spatial.virtualearth.net/REST/v1/data/7d24986af4ad4548bb34f034b067d207/Filialdaten-CH/Filialdaten-CH?spatialFilter=nearby(46.94160079956055,7.408860206604004,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AijRQid01hkLFxKFV7vcRwCWv1oPyY5w6XIWJ-LdxHXxwfH7UUG46Z7dMknbj_rL',
        'FI' => 'https://spatial.virtualearth.net/REST/v1/data/d5239b243d6b4672810cbd11f82750f5/Filialdaten-FI/Filialdaten-FI?spatialFilter=nearby(64.45191955566406,26.734420776367188,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AhRg1sJKLrhfytyanzu32Io1e7le8W-AZs5Xo88SgdwF33tPSxjVn9h72EpJ7gqD',
        'SE' => 'https://spatial.virtualearth.net/REST/v1/data/b340d487953044ba8e2b20406ce3fcc6/Filialdaten-SE/Filialdaten-SE?spatialFilter=nearby(63.86309814453125,15.565899848937988,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=AiHIKQCACRaaOyOJQjGEGl5uxp7KOTXwae435wJqW3jBo_HLpRWmOVrhOI-eI-Rj',
        
    ];
}
