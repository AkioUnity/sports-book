<?php
/**
 * App Controller Component
 *
 * This is base App Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
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

class AppComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * Sets message for user environment
     *
     * @param $message
     * @param string $type
     * @param array $params
     */
    public function setMessage($message, $type = 'info', $params = array()) {
        $this->Session->setFlash($message, null, $params, 'user_flash_message_' . $type);
    }

    /**
     * Returns App Controllers
     *
     * @return array
     */
    public function getControllers() {
        $Controllers = array_unique(App::objects('controller'));

        $appIndex = array_search('App', $Controllers);

        if ($appIndex !== false) {
            unset($Controllers[$appIndex]);
        }

        return array_values($Controllers);
    }

    /**
     * Returns base methods by scope
     *
     * @param string $scope
     * @return array
     */
    public function getBaseMethods($scope = 'Controller') {
        return get_class_methods($scope);
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
            return 0;
        }

        return number_format( (  $total / ( 1 + ($percentage / 100) ) ),  $precision, '.', '');
    }
}