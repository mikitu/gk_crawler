<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 21/07/2016
 * Time: 06:50
 */

namespace GkCrawler\Console\Commands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class BaseCommand extends Command
{
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client(['allow_redirects' => true,]);
        parent::__construct();
    }
    /**
     * @param $body
     * @return mixed
     */
    protected function clean($body)
    {
        $body = preg_replace("/\n\r/", "", $body);
        $body = preg_replace("/\n/", "", $body);
        $body = preg_replace("/\t/", " ", $body);
        $body = preg_replace("/\s+/", " ", $body);
        $body = preg_replace("/>\s+/", ">", $body);
        $body = preg_replace("/\s+>/", ">", $body);
        $body = preg_replace("/\s+</", "<", $body);
        $body = preg_replace("/\s+>/", ">", $body);
        $body = preg_replace("/> </", "><", $body);
        $body = str_replace("&nbsp;", " ", $body);
        $body = str_replace('\u00a0', " ", $body);
        $body = str_replace("> <", "><", $body);
        $body = str_replace("> <", "><", $body);

        $body = str_replace("<strong><strong>", "<br /><strong>", $body);
        $body = str_replace("</strong></strong>", "</strong>", $body);

        return $body;
    }
}