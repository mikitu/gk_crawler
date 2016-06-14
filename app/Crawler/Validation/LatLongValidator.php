<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 12/06/2016
 * Time: 18:31
 */

namespace GkCrawler\Crawler\Validation;


class LatLongValidator implements Validatorinterface
{

    protected $err;

    public function isValid(array $item)
    {
        if (! isset($item['latitude'])) {
            $this->err  = "Latitude value is missing";
            return false;
        }
        if (! isset($item['longitude'])) {
            $this->err  = "Longitude value is missing";
            return false;
        }
        if (! preg_match('/^(\-?\d+(\.\d+)?)$/', $item['latitude'])) {
            $this->err  = "Latitude value is in wrong format";
            return false;
        }
        if (! preg_match('/^(\-?\d+(\.\d+)?)$/', $item['longitude'])) {
            $this->err  = "Latitude value is in wrong format";
            return false;
        }
        return true;
    }

    public function getError()
    {
        return $this->err;
    }
}