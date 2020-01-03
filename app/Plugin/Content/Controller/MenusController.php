<?php
/**
 * Front Menus Controller
 *
 * Handles Menus Actions
 *
 * @package    Menus.Controller
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('ContentAppController', 'Content.Controller');

class MenusController extends ContentAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Menus';

    /**
     * Additional models
     *
     * @var array
     */
    public $uses = array(
        0   =>  'Content.Menu'
    );

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        0   =>  'Security'
    );

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
                'parent_id' =>  1
            );
        }

        parent::admin_index($conditions, $model);
    }

    /**
     * Admin Add scaffold functions
     *
     * @param null $id - parentId
     *
     * @return void
     */
    public function admin_add($id = null)
    {
        if ($id!=$this->name)
            return;
        if ($this->request->is('post') && !empty($this->request->data)) {
            $this->Menu->create();
            $this->Menu->save($this->request->data);
            $this->redirect($this->referer(), null, true);
        }

        $this->__getModel();
        $this->set('fields',  $this->Menu->getAdd());
    }
}