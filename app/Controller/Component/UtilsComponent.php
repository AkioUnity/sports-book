<?php
/**
 * Utils Component
 *
 * This is Common Utils Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 * Class provides common and independent misc methods from any controller
 *
 * @package     App.Controller
 * @author      Deividas Petraitis <deividas@laiskai.lt>
 * @copyright   2013 The ChalkPro Betting Scripts
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     Release: @package_version@
 * @link        http://www.chalkpro.com/
 * @see         Controller::$components
 */

App::uses('Component', 'Controller');

class UtilsComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * Generates and returns random string
     *
     * @param int $length
     * @return string
     */
    public function getRandomString($length = 10) {
        $salt = array_merge(range('a', 'z'), range(0, 9));
        $maxIndex = count($salt) - 1;

        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $maxIndex);
            $result .= $salt[$index];
        }
        return $result;
    }

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