<?php
/**
 * Handles Languages
 *
 * Handles Languages Actions
 *
 * @package    Languages
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class LanguagesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Languages';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Language', 'MyI18n');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function getLanguages()
    {
        return $this->Language->get();
    }

    public function setLanguage($LanguageId)
    {
        $Language = $this->Language->findById($LanguageId);
        if (isset($Language)) {
            if ($this->Session->read('Auth.User.id')) {
                $this->Session->write('Auth.User.Language_id', $Language['Language']['id']);
            } else {
                $this->Cookie->write('language', $Language['Language']['name'], $encrypt = false, $expires = null);
            }
        }
        $this->redirect($this->referer());
    }

    /**
     * Admin Add scaffold functions
     *
     * @param null $id - parentId
     *
     * @return void
     */
    public function admin_add($id = null) {
        $i18n = I18n::getInstance();
        $l10n = $i18n->l10n;
        $Languages = $l10n->catalog();

        foreach ($Languages as $key => $value) {
            $LanguagesList[$key] = $value['language'];
        }

        if (!empty($this->request->data)) {
            if (1 == 2) {
                //add new Language
                $LanguageId = $this->request->data['Language']['name'];

                if (!$this->Language->findByName($Languages[$LanguageId]['locale'])) {

                    $this->request->data['Language']['name'] = $Languages[$LanguageId]['locale'];
                    $this->request->data['Language']['language'] = $Languages[$LanguageId]['language'];
                    $this->request->data['Language']['LanguageFallback'] = $Languages[$LanguageId]['localeFallback'];
                } else {
                    $this->request->data = array();
                    $this->Admin->setMessage(__('Language already exist', true), 'error');
                }
            } else {
                $this->Admin->setMessage(__('Please contact Softbroke technical support team for additional languages'), 'error');
                $this->request->data = array();
            }
        }
        parent::admin_add();
    }

    /**
     * Admin Delete scaffold functions
     *
     * @param int $id - delete item id
     *
     * @return void
     */
    public function admin_delete($id)
    {
        $Language = $this->Language->getItem($id);
        $this->MyI18n->deleteAll($Language['Language']['name']);

        $model = $this->__getModel();
        if ($this->$model->delete($id)) {
            $this->Admin->setMessage(__('Item deleted', true), 'success');
        } else {
            $this->Admin->setMessage(__('can\'t delete item', true), 'error');
        }
        $this->redirect($this->referer(array('action' => 'index')));
    }
}