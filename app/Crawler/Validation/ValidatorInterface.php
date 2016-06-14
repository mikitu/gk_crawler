<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 12/06/2016
 * Time: 18:32
 */

namespace GkCrawler\Crawler\Validation;


interface ValidatorInterface
{
    public function isValid(array $item);
    public function getError();
}