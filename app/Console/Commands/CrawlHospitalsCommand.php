<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlHospitalsCommand extends Command
{
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
        $base_url = 'http://www.hospitalglobal.com';
        $url = $base_url . '/index.php/hospitals-in-the-world.html';
        $client = new Client(['allow_redirects' => true,]);

        $request = $client->get($url);

        $body = $request->getBody();
        $pattern = '/<li>[^<]+<a[^>]+><img[^>]+><\/a>[^<]+<p>[^<]+<a href="(\/index.php\/hospitals-in-[^.]+.html)"/is';

        preg_match_all($pattern, $body, $matches);
        $urls = [];
        foreach ($matches[1] as $url) {
            $url = $base_url . $url;
            $request = $client->get($url);
            $body = $request->getBody();
            $pattern = '/<li>[^<]+<div[^>]+>[^<]+<div[^>]+>[^<]+<a[^>]+><img[^>]+><\/a><br \/>[^<]+<a href="(\/index.php\/hospitals-in-[^.]+.html)"/is';
            preg_match_all($pattern, $body, $matches);
            foreach ($matches[1] as $url1) {
                $url1 = $base_url . $url1;
                $request = $client->get($url1);
                $body1 = $request->getBody();
                $pa = '/<li class="item\d+"><a href="([^"]+)"><span>Details and maps[^<]*<\/span>/is';
                preg_match($pa, $body1, $matches1);
                try {
                    $details_url = $base_url . $matches1[1] . '?limit=0';
                } catch (\Exception $e) {
                    print_r($matches1);
//                    echo $body2 . PHP_EOL;
                    die($url1);
                }
                $request = $client->get($details_url);
                $body2 = $request->getBody();
                
                $pattern2 = '/<a href="(\/index.php\/hospitals-[^\d]+\d+-[^.]+.html)">/is';
                preg_match_all($pattern2, $body2, $matches2);
                try {
                    $urls = array_merge($urls, $matches2[1]);
                } catch (\Exception $e) {
                    print_r($matches2);
//                    echo $body2 . PHP_EOL;
                    die($details_url);
                }
            }
        }
        foreach($urls as $url) {
            try {
                $model = new Hospital();
                $model->url = $url;
                $model->save();
            } catch (\Exception $e) {
                
            }
        }

//        $pattern3 = '/<br \/>[^<]*<strong>([^<]+):\s+<\/strong>\s+([^<]+)\s+/is';
//        $pattern4 = '/"latitude":"([^"]+)","longitude":"\s?([^"]+)"/is';

    }
}
