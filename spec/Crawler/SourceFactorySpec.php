<?php

namespace spec\GkCrawler\Crawler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SourceFactorySpec extends ObjectBehavior
{

    function it_should_create_objects_by_name_given()
    {
        $this->beConstructedThrough('create', ['Kauffland']);
        $this->shouldHaveType('GkCrawler\Crawler\Source');
    }

    function it_should_throw_error_if_the_class_doesnt_exists()
    {
        $this->shouldThrow('\Exception')->during("create", ['Kauffland in action']);
    }
}
