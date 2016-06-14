<?php
/**
 * Created by PhpStorm.
 * User: mihaibucse
 * Date: 13/06/2016
 * Time: 06:51
 */

namespace GkCrawler\Crawler\Validation;


class SourceValidator implements SourceValidatorInterface
{
    protected $validators = [];
    protected $err;

    /**
     * SourceValidator constructor.
     */
    public function __construct()
    {
        $this->addValidator(new LatLongValidator());
    }

    /**
     * @param Validatorinterface $validator
     */
    public function addValidator(ValidatorInterface $validator) {
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

    public function printErrorMessage(array $item)
    {
        echo "ERROR: " . $this->err . '`' . json_encode($item) . '`' . PHP_EOL;
    }
}