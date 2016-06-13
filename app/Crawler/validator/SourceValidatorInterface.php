<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 13/06/2016
 * Time: 06:49
 */

namespace GkCrawler\validator;


interface SourceValidatorInterface
{
    public function addValidator(Validatorinterface $validator);
    public function validate(array $item);
    public function printErrorMessage();
}