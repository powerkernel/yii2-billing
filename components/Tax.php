<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */
namespace modernkernel\billing\components;


/**
 * Class Tax
 * @package modernkernel\billing\components
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