<?php

namespace spec\GkCrawler\Validation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SourceValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('GkCrawler\Validation\SourceValidator');
    }
}
