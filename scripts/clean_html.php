<?php

$url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-spain/details-and-maps/3615-clinica-quirurgica-onyar.html';
$url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-united-kingdom/hospitals-in-england/details-and-maps-england/36713-north-hampshire-hospital.html';
$url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitales-in-belgium/maps-and-details-belgium/38347-_centre-hospitalier-universitaire-de-lioge.html';
$url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitales-in-netherland/maps-and-details-netherland/38214-leids-universitair-medisch-centrum.html';
$url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-russia/details-and-maps/18927-european-medical-centre-group.html';

$html = file_get_contents($url);

$html = clean($html);

$pattern3 = '/(?:<br\s?\/>)?<strong>([^<]+):?<\/strong.?>(?:<a[^>]+)?([^<]+)/is';
preg_match_all($pattern3, $html, $matches, PREG_SET_ORDER);

print_r($matches);

function clean($body)
{
    $body = preg_replace("/\n\r/", "", $body);
    $body = preg_replace("/\n/", "", $body);
    $body = preg_replace("/\t/", " ", $body);
    $body = preg_replace("/\s+/", " ", $body);
    $body = preg_replace("/> </", "><", $body);
    $body = preg_replace("/>\s+/", ">", $body);
    $body = preg_replace("/\s+>/", ">", $body);
    $body = preg_replace("/\s+</", "<", $body);
    $body = preg_replace("/\s+>/", ">", $body);
    $body = str_replace("<strong><strong>", "<br /><strong>", $body);
    return $body;
}
