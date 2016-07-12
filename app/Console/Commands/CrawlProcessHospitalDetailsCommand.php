<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlProcessHospitalDetailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:process:hospital:details';

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
        $hospitals = Hospital::all();
        $results = [];
        $keys = [];
        foreach($hospitals as $model) {
            if (empty($model->raw_data)) continue;
            $data = json_decode($model->raw_data, true);
            $this->updateModel($model, $data);
            $keys = array_merge($keys, $data);
        }
//        array_unique($keys);
        print_r(array_keys($keys));
    }

    private function updateModel($model, $val)
    {
//        foreach($data as $val) {
            if (isset($val['Name'])) {
                $model->name = $this->cleanStr(trim($val['Name'], '>'));
            }
            if (isset($val['Telephone']) && $val['Telephone'] != 'Not available') {
                $model->phone = $this->cleanStr($val['Telephone']);
            }
            if (isset($val['Adress'])) {
                $model->address = $this->cleanStr($val['Adress']);
            }
            if (isset($val['Address'])) {
                $model->address = $this->cleanStr($val['Address']);
            }
            if (isset($val['Code'])) {
                $model->zipcode = $this->cleanStr($val['Code']);
            }
            if (isset($val['City'])) {
                $model->city = $this->cleanStr($val['City']);
            }
            if (isset($val['Town'])) {
                $model->city = $this->cleanStr($val['Town']);
            }
            if (isset($val['Province'])) {
                $model->city = $this->cleanStr($val['Province']);
            }
            $country_found = false;
            if (isset($val['Country'])) {
                $country_found = true;
                $model->country = $this->cleanStr($val['Country']);
            }
            if (isset($val['Nation'])) {
                $country_found = true;
                $model->country = $this->cleanStr($val['Nation']);
            }
            if (! $country_found) {
                $model->country = $this->identifyByUrl($model->url);
            }
            $model->save();
//        }
        
    }

    private function cleanStr($str)
    {
        $str = str_replace('&nbsp;', ' ', $str);
        $str = str_replace('\u00a0', ' ', $str);
        $str = trim($str);
        $str = html_entity_decode($str);
        $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
        return $str;
    }

    private function identifyByUrl($url)
    {
        preg_match('#hospitals-in-([^/]+)/details#', $url, $matches);
        return ucwords(str_replace('-', ' ' , $matches[1]));
    }
}
