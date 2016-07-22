<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlProcessHospitalsCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:process:hospitals {--from=} {--to=}';

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
        $from = $this->option('from');
        $to = $this->option('to');

        $base_url = 'http://www.hospitalglobal.com';
        if (! is_null($from) && ! is_null($to)) {
            $urls = Hospital::whereBetween('id', array(intval($from), intval($to)))->get();;
        } else {
            $urls = Hospital::all();
//            $urls = Hospital::all()->sortByDesc("id");;
            //$urls = Hospital::where('latitude', '')->orderBy("id", 'desd')->get();
//            $urls = Hospital::where('name', '')->orderBy("id", 'desd')->get();
//            $urls = Hospital::where('country', '')->orderBy("id", 'desd')->get();
            $urls = Hospital::where('city', '')->orderBy("id", 'desd')->get();
//            $urls = Hospital::where('raw_data', 'LIKE', '[%')->orderBy("id", 'desd')->get();

        }
//        $urls = Hospital::whereBetween('id', array(3100, 3200))->get();;
        $total = count($urls);
        $this->info("Total: " . $total);
        foreach($urls as $model) {
//            if (! empty($model->latitude)) continue;
            echo $model->url.PHP_EOL;
            $model->url = str_replace($base_url, '', $model->url);
            $model->url = $base_url . $model->url;
            try {
                $client = new Client(['allow_redirects' => true,]);
                $request = $client->get($model->url);
                $body = $this->clean($request->getBody());
                $pattern3 = '/(?:<br\s?\/>)?<strong>([^<]+):?<\/strong.?>(?:<a[^>]+)?([^<]+)/is';
                preg_match_all($pattern3, $body, $matches, PREG_SET_ORDER);

                array_walk($matches, function(&$value) {
                    array_shift($value);
                    $value = [trim($value[0],':') => trim($value[1], '<')];
                });
                $matches = $this->normalizeMatches($matches);
                $model->raw_data = json_encode($matches);
                if (isset($matches['Name'])) {
                    $model->name = trim($matches['Name'], '>');
                }
                if (isset($matches['Telephone']) && $matches['Telephone'] != 'Not available') {
                    $model->phone = $matches['Telephone'];
                }
                if (isset($matches['Adress'])) {
                    $model->address = $matches['Adress'];
                }
                if (isset($matches['Code'])) {
                    $model->zipcode = $matches['Code'];
                }
                $pattern4 = '/"latitude":"([^"]+)","longitude":"\s?([^"]+)"/is';
                preg_match($pattern4, $body, $matches);
                if (! empty($matches[1]) && ! empty($matches[1])) {
                    $model->latitude = $matches[1];
                    $model->longitude = $matches[2];
                    $this->info($matches[1]);
                }
                $model->save();
            } catch (\Exception $e) {
                $this->info($e->getMessage() . " " . $e->getLine());
            }
            $total--;
            $this->info("Remaining: " . $total);
        }
    }

    private function normalizeMatches($matches)
    {
        $res = [];
        foreach ($matches as $match) {
            foreach ($match as $key => $val) {
                if (substr($key, 0, 3) != 'Map') {
                    $res[$key] = $val;
                }
            }
        }
        return $res;
    }
}
