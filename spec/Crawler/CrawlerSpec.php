<?php

namespace spec\GkCrawler\Crawler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use GkCrawler\Crawler\Source;
use GkCrawler\Crawler\SourceCollection;

class CrawlerSpec extends ObjectBehavior
{
    function Let(SourceCollection $sources)
    {
        $this->beConstructedWith($sources);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('GkCrawler\Crawler\Crawler');
    }

    function it_should_call_run_method_on_every_source_in_collection(Source $source) {

        $this->run();
        $source->run()->shouldBeCalled(1);
    }
}
