<?php

App::uses('AppHelper', 'View/Helper');

class UtilsHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Utils';

    /**
     * Percentage calculation
     *
     * @param $value
     * @param $percentage
     * @return float
     */
    public function percentage($value, $percentage) {
        return ($value * $percentage) / 100;
    }
}