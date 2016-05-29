<?php

namespace GkCrawler\Crawler;

use GkCrawler\Model\SourceData;
use Mockery\Exception;

class SourceFactory
{
    public static function create($name, array $data = [])
    {
        $name = preg_replace('/[^0-9a-zA-Z]/is', ' ', $name);
        $className = "GkCrawler\\Crawler\\Sources\\" . ucwords($name);
        $className = preg_replace('/\s+/is', '', $className);
        if (! class_exists($className)) {
            throw new Exception("Object " . $className . " not found");
        }
        $class = new $className($data, new SourceData);
        return $class;
    }
}
