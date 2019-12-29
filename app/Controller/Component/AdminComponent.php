<?php
/**
 * Admin Controller Component
 *
 * This is base Admin Component Class
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

class AdminComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * Sets message for admin environment
     *
     * @param string $message - Message
     * @param string $type    - Message type
     * @param array  $params  - Additions params
     *
     * @return void
     */
    public function setMessage($message, $type = 'info', $params = array())
    {
        $this->Session->setFlash($message, null, $params, 'admin_flash_message_' . $type);
    }
}