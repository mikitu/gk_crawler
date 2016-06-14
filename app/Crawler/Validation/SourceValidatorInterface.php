<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 13/06/2016
 * Time: 06:49
 */

namespace GkCrawler\Crawler\Validation;


interface SourceValidatorInterface
{
    public function addValidator(ValidatorInterface $validator);
    public function validate(array $item);
    public function printErrorMessage(array $item);
}