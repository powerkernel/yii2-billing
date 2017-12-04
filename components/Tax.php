<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */
namespace powerkernel\billing\components;


/**
 * Class Tax
 * @package powerkernel\billing\components
 */
class Tax
{

    public $tax = [
        'VN' => 0.1
    ];

    /**
     * get tax value by country
     * @param $country
     * @return int|mixed
     */
    public function getTaxValue($country)
    {
        if (isset($this->tax[$country])) {
            return $this->tax[$country];
        }
        return 0;
    }

}