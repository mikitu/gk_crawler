<?php

namespace spec\GkCrawler\Crawler;

use GkCrawler\Crawler\SourceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SourceCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('GkCrawler\Crawler\SourceCollection');
        $this->shouldHaveType('\Countable');
        $this->shouldHaveType('\IteratorAggregate');
        $this->shouldHaveType('\Traversable');
    }

    function it_should_return_one(SourceInterface $source)
    {
        $this->add($source);
        $this->getSources()->shouldHaveCount(1);
    }
}
