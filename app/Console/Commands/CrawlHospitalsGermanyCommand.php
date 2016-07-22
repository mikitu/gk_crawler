<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlHospitalsGermanyCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:hospitals:germany';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $URLS = [];
        $URLS[] = 'https://bookinghealth.com/clinics/treatment/all/&page=';
        $URLS[] = 'https://bookinghealth.com/clinics/diagnosis/all/&page=';
        $URLS[] = 'https://bookinghealth.com/clinics/rehabilitation/all/&page=';
        $URLS[] = 'https://bookinghealth.com/clinics/all/&page=';
        $urls = [];
        foreach ($URLS as $url) {
            for ($i = 1; $i < 20; $i++) {
                $request = $this->client->get($url . $i);
                $body = $request->getBody();
                preg_match_all('/<span class="programMore"><a href="([^"]+)">/iU', $body, $matches);
                if (! empty($matches[1])) {
                    $urls = array_merge($urls, $matches[1]);
                }
            }
        }
        $urls = array_unique($urls);
        foreach($urls as $url) {
            try {
                $model = new Hospital();
                $model->url = $url;
                $request = $this->client->get($url);
                $body = $request->getBody();
                $body = $this->clean($body);
                preg_match('#<h2>([^<]+)</h2><div class="land#iU', $body, $matches);
                $model->name = $matches[1];
                preg_match_all('#<div class="land"><b>[^<]+</b>([^<]+)</div>#iU', $body, $matches);
                $model->country = $matches[1][0];
                $model->city = $matches[1][1];
                preg_match('#LatLng\(([^,]+),([^\)]+)\);#iU', $body, $matches);
                $this->info($url);
                var_dump($matches);
                if (! empty($matches[1]) && ! empty($matches[2])) {
                    $model->latitude = $matches[1];
                    $model->longitude = $matches[2];
                }
                $model->save();
                echo "\n\n";
            } catch (\Exception $e) {
                $this->info($e->getMessage());
            }
        }
    }

    /**
     * @param $urls
     * @param $details_url
     * @param $matches2
     * @return array
     */
    protected function level3(&$urls, $details_url)
    {
        $request = $this->client->get($details_url);
        $body2 = $request->getBody();

        $pattern2 = '/<a href="(\/index.php\/hospitals-[^\d]+\d+-[^.]+.html)">/is';
        preg_match_all($pattern2, $body2, $matches2);
        try {
            $urls = array_merge($urls, $matches2[1]);
            return array($request, $urls);
        } catch (\Exception $e) {
            print_r($matches2);
//                    echo $body2 . PHP_EOL;
            die($details_url);
        }
    }
}
