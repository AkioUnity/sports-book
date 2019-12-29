<?php
/**
 * Front Slides Controller
 *
 * Handles Slides Actions
 *
 * @package    Events
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class SlidesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Slides';

    /**
     * Array containing the names of components this controller uses.
     *
     * @var $components array
     */
    public $components = array('Images');

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'Content.Slide'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * Returns slides
     *
     * @return mixed
     * @throws ForbiddenException
     */
    public function admin_getSlides()
    {
        return $this->Slide->getSlides();
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
        $this->__getModel();

        if($this->request->is('post') && $this->Slide->validates()) {
            if ($this->Slide->save($this->request->data)) {
                $image = $this->request->data['Slide']['image'];
                $this->Images->save($image['tmp_name'], $this->Slide->id, $this->Slide->sizes, WWW_ROOT . 'media/slides');
                $this->Admin->setMessage(__('Item added', true), 'success');
                $this->redirect(array('action' => 'index'), null, true);
            }
            $this->Admin->setMessage(__('Slide cannot be added.', true), 'error');
        }

        $this->set('fields', $this->Slide->getAdd());
    }

    /**
     * Admin edit slides
     *
     * @param $id
     */
    public function admin_edit($id)
    {
        $this->__getModel();

        if (!empty($this->request->data)) {

            $this->request->data['Slide']['id'] = $id;

            if(!isset($this->request->data['Slide']['image']['name']) || $this->request->data['Slide']['image']['name'] == '') {
                unset($this->Slide->validate['image']);
            }

            if($this->Slide->validates() && $this->Slide->save($this->request->data)) {
                if($this->request->data['Slide']['image']['name'] != '') {
                    $this->Images->save($this->request->data['Slide']['image']['tmp_name'], $id, $this->Slide->sizes, WWW_ROOT . 'media/slides');
                }
                $this->Admin->setMessage(__('Slide edited', true), 'success');
                $this->redirect(array('action' => 'index'), null, true);
            }
            $this->Admin->setMessage(__('Slide cannot be edited.', true), 'error');
        }

        $this->set('fields', $this->Slide->getEdit());

        $this->request->data = $this->Slide->getItem($id);
    }
}