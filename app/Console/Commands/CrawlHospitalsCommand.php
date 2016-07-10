<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlHospitalsCommand extends Command
{
    protected $base_url = 'http://www.hospitalglobal.com';
    protected $client;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:hospitals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = $this->base_url . '/index.php/hospitals-in-the-world.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe.html';

        $this->client = new Client(['allow_redirects' => true,]);
//        $request = $this->client->get($url);
//        $body = $request->getBody();
        $urls = [];

        //world (usa)
//        $pattern = '/<li>[^<]+<a[^>]+><img[^>]+><\/a>[^<]+<p>[^<]+<a href="(\/index.php\/hospitals-in-[^.]+.html)"/is';
//        preg_match_all($pattern, $body, $matches);
//        $this->level1($urls, $matches);

        //europe
//        $pattern = '/<li>[^<]+<a[^>]+><img[^>]+><\/a>[^<]+<p>[^<]+<a href="(\/index.php\/hospitals-in-[^.]+.html)"/is';
//        preg_match_all($pattern, $body, $matches);
//        $this->level2($urls, $matches);

        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitals-in-austria/details-and-maps-austria.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitals-in-france/details-and-maps.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitals-in-spain/details-and-maps.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitales-in-netherland/maps-and-details-netherland.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitals-in-turkey/details-and-maps.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitals-in-bulgaria/details-and-maps-bulgaria-.html';
        $url = $this->base_url . '/index.php/hospitals-in-europe/hospitales-in-belgium/maps-and-details-belgium.html';
        $url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-united-kingdom/hospitals-in-england/details-and-maps-england.html';
        $url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-united-kingdom/hospitals-in-northern-ireland/details-and-maps-northern-ireland.html';
        $url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-united-kingdom/hospitals-in-scotland/details-and-maps-scotland.html';
        $url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-united-kingdom/hospitals-in-wales/details-and-maps-wales.html';
        $url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-russia/details-and-maps.html';
        $url = 'http://www.hospitalglobal.com/index.php/hospitals-in-europe/hospitals-in-ukraine/details-and-maps-ukraine.html';
//        $url = '';
//        $url = '';
//        $url = '';
//        $url = '';
//        $url = '';
//        $url = '';
//        $url = '';
//        $url = '';
//        $url = '';

        $url .= '?limit=0';
        $this->level3($urls, $url);

//        print_r($urls);die;
        foreach($urls as $url) {
            try {
                $model = new Hospital();
                $model->url = $url;
                $model->save();
            } catch (\Exception $e) {

            }
        }
    }

    protected function level1(&$urls, $matches) {
        foreach ($matches[1] as $url) {
            $url = $this->base_url . $url;
            $request = $this->client->get($url);
            $body = $request->getBody();
            $pattern = '/<li>[^<]+<div[^>]+>[^<]+<div[^>]+>[^<]+<a[^>]+><img[^>]+><\/a><br \/>[^<]+<a href="(\/index.php\/hospitals-in-[^.]+.html)"/is';
            preg_match_all($pattern, $body, $matches);
            $this->level2($urls, $matches);
        }
    }

    protected function level2(&$urls, $matches)
    {
        foreach ($matches[1] as $url1) {
            $url1 = $this->base_url . $url1;
            $request = $this->client->get($url1);
            $body1 = $request->getBody();
            $pa = '/<li class="item\d+"><a href="([^"]+)"><span>Details and maps[^<]*<\/span>/is';
            preg_match($pa, $body1, $matches1);
            try {
                $details_url = $this->base_url . $matches1[1] . '?limit=0';
            } catch (\Exception $e) {
                print_r($matches1);
//                    echo $body2 . PHP_EOL;
                die($url1);
            }
            $this->level3($urls, $details_url);
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
