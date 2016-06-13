<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 13/06/2016
 * Time: 06:51
 */

namespace GkCrawler\validator;


use GkCrawler\Crawler\OutputInterface;

class SourceValidator implements SourceValidatorInterface
{
    protected $validators = [];
    
    public function __construct()
    {
        $this->addValidator(new LangLongValidator());
    }

    /**
     * @param Validatorinterface $validator
     */
    public function addValidator(Validatorinterface $validator) {
        $this->validators[] = $validator;
    }

    /**
     * @param array $item
     * @return bool
     */
    public function validate(array $item)
    {
        foreach ($this->validators as $validator) {
            if (! $validator->isValid($item)) {
                $this->err = $validator->getError();
                return false;
            }
        }
        return true;
    }

    public function printErrorMessage(OutputInterface $output)
    {
        $output->error();
    }
}