<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 29/05/2016
 * Time: 08:26
 */

namespace GkCrawler\Crawler;


interface OutputInterface
{
    public function info($string, $verbosity = null);
}