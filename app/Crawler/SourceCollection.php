<?php
namespace GkCrawler\Crawler;

class SourceCollection implements \Countable, \IteratorAggregate
{

    protected $data = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    public function add(SourceInterface $source)
    {
        $this->data[] = $source;
    }

    public function getSources()
    {
        return $this->data;
    }
}