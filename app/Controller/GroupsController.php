<?php
/**
 * Handles Groups
 *
 * Handles Groups Actions
 *
 * @package    Groups
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class GroupsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Groups';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Group');

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array
     */
    public $helpers = array();

    /**
     * Admin Index scaffold functions
     *
     * @param array $conditions -   admin_index scaffold conditions
     * @param null  $model      -   admin_index $model name
     *
     * @return array
     */
    public function admin_index($conditions = array(), $model = null)
    {
        if (!empty($conditions) && is_numeric($conditions) && $conditions >= 1) {
            $conditions = array(
                'parent_id' =>  $this->request->params['pass'][0]
            );
        } else {
            $conditions = array(
                'parent_id' =>  0
            );
        }

        parent::admin_index($conditions, $model);
    }
}