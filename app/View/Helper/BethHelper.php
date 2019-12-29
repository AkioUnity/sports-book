<?php

App::uses('AppHelper', 'View/Helper');

class BethHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Beth';

    /**
     * Helpers list
     *
     * @var array
     */
    public $helpers = array(
        0   =>  'Ajax',
        1   =>  'Session',
        2   =>  'Time'
    );

    public function convertCurrency($amount)
    {
        return sprintf('%s', number_format((float)$amount, intval(Configure::read('Settings.balance_decimal_places')), '.', ''));
    }

    /**
     * @param $odd
     * @return float|string
     */
    public function convertOdd($odd)
    {
        if ($this->Session->read('Auth.User.odds_type')) {
            $type = $this->Session->read('Auth.User.odds_type');
        } else if ($this->Session->read('odds_type')) {
            $type = $this->Session->read('odds_type');
        } else {
            $type = 'default';
        }

        switch ($type) {
            case 2:
                return $this->convertToFractional($odd);
                break;
            case 3:
                return $this->convertToAmerican($odd);
                break;
            default:
                return sprintf("%01.2f", round($odd, 2));
                break;
        }
    }

    public function convertToFractional($odd)
    {
        $numerator = ($odd - 1) * 100;
        $denominator = 100;
        return $numerator . '/' . $denominator;
    }

    public function convertToAmerican($odd)
    {
        if ($odd >= 2) {
            return '+' . 100 * ($odd - 1);
        } else {
            return round(-100 / ($odd - 1));
        }
    }

    /**
     * Calculates a selling price after a % mark-up has been added
     *
     * @param     $total
     * @param     $percentage
     * @param int $precision
     *
     * @return int|string
     */
    public function calculatePercentageAfter($total, $percentage, $precision = 2) {
        $total      = !is_numeric($total) ? 0 : number_format($total, $precision, '.', '');
        $percentage = !is_numeric($percentage) ? 0 : number_format($percentage, $precision, '.', '');

        if ($percentage <= 0) {
            return 0;
        }

        return number_format( (  ( ($percentage / 100) ) * $total ),  $precision, '.', '');
    }

    /**
     * Find a sale price when a % discount is given
     *
     * @param     $total
     * @param     $percentage
     * @param int $precision
     *
     * @return int|string
     */
    public function calculatePercentageGiven($total, $percentage, $precision = 2) {
        $total      = !is_numeric($total) ? 0 : number_format($total, $precision, '.', '');
        $percentage = !is_numeric($percentage) ? 0 : number_format($percentage, $precision, '.', '');

        if ($percentage <= 0) {
            return $total;
        }

        return number_format( (  $total / ( 1 + ($percentage / 100) ) ),  $precision, '.', '');
    }

    public function getStatus($status)
    {
        $str = '';
        switch (intval($status)) {
            case 1:
                $str = '<span class="ticket-won">' . __('Win', true) . '</span>';
                break;
            case 0:
                $str = '<span class="ticket-pending">' . __('Pending', true) . '</span>';
                break;
            case -1:
                $str = '<span class="ticket-lost">' . __('Lost', true) . '</span>';
                break;
            case -2:
                $str = '<span class="ticket-canceled">' . __('Cancelled', true) . '</span>';
                break;
        }
        return $str;
    }

    public function getOddsType($type)
    {
        $str = '';
        switch ($type) {
            case 0:
                $str = __('Decimal');
                break;
            case 2:
                $str = __('Fractional');
                break;
            case 3:
                $str = __('American');
                break;
        }
        return $str;
    }

    /**
     * Return odds types
     *
     * @return array
     */
    public function getOddsTypes()
    {
        return array(
            'Decimal'       =>  __('Decimal'),
            'Fractional'    =>  __('Fractional'),
            'American'      =>  __('American')
        );
    }

    public function betToPath($betType)
    {
        $betType = str_replace('_', '-', $betType);
        $betType = str_replace('(', '', $betType);
        $betType = str_replace(')', '', $betType);
        $betType = str_replace('/', '-', $betType);
        return strtolower(str_replace(' ', '-', $betType));
    }

    public function countToPath($count) {
        if ($count == 2) {
            return '12';
        }elseif($count == 3){
            return '1x2';
        }else{
            return 'correct-score';
        }
    }
}