<?php

namespace GkCrawler\Crawler\Sources;


use GkCrawler\Crawler\Source;
use GuzzleHttp\Client;

class AuchanFrance extends Source
{
    protected $client;
    /**
     * @param Client $client
     * @return mixed
     */
    public function fetchData(Client $client)
    {
        $this->client = $client;
        $headers = unserialize($this->sourceData['headers']);
        $res = $client->request($this->sourceData['method'], $this->sourceData['url'], ['headers' => $headers]);
        $body = json_decode($res->getBody(), true)['features'];
        return [
            "status_code" => $res->getStatusCode(),
            "body" => $body,
        ];
    }

    /**
     * @param array $item
     * @return array
     */
    public function normalize(array $item)
    {
        if (! isset($item['properties']['address']['city'])) {
            return false;
        }

        return array_map('trim', [
            'country_code'  => $this->sourceData['country_code'],
            'city'          => $item['properties']['address']['city'],
            'name'          => $item['properties']['name'],
            'address'       => $item['properties']['address']['lines'][0],
            'phone'         => $item['properties']['contact']['phone'],
            'zipcode'       => $item['properties']['address']['zipcode'],
            'latitude'      => $item['geometry']['coordinates'][1],
            'longitude'     => $item['geometry']['coordinates'][0],
            'openinghours'  => $this->getOpeningHours($item['properties']),
        ]);
    }

    private function getOpeningHours($openinghours)
    {
        $days = [];
        if (array_key_exists('opening_hours', $openinghours)) {
            return $this->getSpecial($openinghours['opening_hours']);
        }
        $openinghours = $openinghours['weekly_opening'];
        foreach ($openinghours as $day => $row) {
            switch ($day) {
                case '1': $days[] = 'Mo: ' . $this->getDailyHours($row); break;
                case '2': $days[] = 'Tw: ' . $this->getDailyHours($row); break;
                case '3': $days[] = 'We: ' . $this->getDailyHours($row); break;
                case '4': $days[] = 'Th: ' . $this->getDailyHours($row); break;
                case '5': $days[] = 'Fr: ' . $this->getDailyHours($row); break;
                case '6': $days[] = 'Sa: ' . $this->getDailyHours($row); break;
                case '7': $days[] = 'Su: ' . $this->getDailyHours($row); break;
            }
        }
        return implode('; ', $days);
    }

    private function getDailyHours($input)
    {
        $ret = [];
        foreach ($input as $row) {
            if (! isset($row['hours'])) continue;
            $ret[] = $row['hours'][0]['start'] . ' - ' . $row['hours'][0]['end'];
        }
        return implode(', ', $ret);
    }

    private function getSpecial($input)
    {
        $ret = [];
        foreach ($input['special'] as $day => $row) {
            if (! count($row)) continue;
            $ret[] = $day . ': ' . $row[0]['start'] . ' - ' . $row[0]['end'];
        }
        foreach ($input['usual'] as $day => $row) {
            if (! count($row)) continue;
            switch ($day) {
                case '1': $ret[] = 'Mo: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
                case '2': $ret[] = 'Tw: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
                case '3': $ret[] = 'We: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
                case '4': $ret[] = 'Th: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
                case '5': $ret[] = 'Fr: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
                case '6': $ret[] = 'Sa: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
                case '7': $ret[] = 'Su: ' . $row[0]['start'] . ' - ' . $row[0]['end']; break;
            }
        }
        return implode('; ', $ret);
    }
}