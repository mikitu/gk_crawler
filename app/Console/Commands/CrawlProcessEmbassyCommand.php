<?php

namespace GkCrawler\Console\Commands;

use GkCrawler\Model\Embassy;
use GkCrawler\Model\EmbassyTmp;
use GkCrawler\Model\Hospital;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlProcessEmbassyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:process:embassy';

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
        $res = EmbassyTmp::all();
        $results = [];
        $keys = [];
        foreach($res as $model_tmp) {
            $this->parse($model_tmp);
        }
    }

    private function parse($model_tmp)
    {
        preg_match_all('#<article>.*Send Edits</a></div></div></article>#iU', $model_tmp->source, $matches);
        echo $model_tmp->country . PHP_EOL;
        foreach ($matches[0] as $article) {
            $model = new Embassy();
            $model->embassy_in = $model_tmp->country;
            // title
            preg_match('#<h6 class="embassy-title title">([^>]+)</h6>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->title = $amatches[1];
            }
            // embassy_name
            preg_match('#<span class="embassy-name">([^<]+)</span>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->embassy_name = $amatches[1];
            }
            // address
            preg_match('#<span class="embassy-address">([^<]+)</span>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->address = $amatches[1];
            }
            // address
            preg_match('#<span class="embassy-address">([^<]+)</span>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->address = $amatches[1];
            }
            // phone
            preg_match('#<div><span>Phone</span>([^<]+)</div>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->phone = $amatches[1];
            }
            // fax
            preg_match('#<div><span>Fax</span>([^<]+)</div>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->fax = $amatches[1];
            }
            // email
            preg_match('#<div><span>Email</span>.*>([^<]+)</a></div>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->email = $amatches[1];
            }
            // website
            preg_match('#<div><span>Website</span>.*>([^<]+)</a></div>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->website = $amatches[1];
            }
            // office_hours
            preg_match('#<div><span>Office Hours</span>([^<]+)</div>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->office_hours = $amatches[1];
            }
            // details
            preg_match('#<div><span>Details</span>([^<]+)</div>#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->details = $amatches[1];
            }
            // country_of
            preg_match('#<img class="embassy-flag" src="http://images.goabroad.com/images/flags/[^.]+.gif" alt="([^"]+)" />#iU', $article, $amatches);
            if (! empty($amatches[1])) {
                $model->embassy_of = $amatches[1];
            }
            $model->save();
        }

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
