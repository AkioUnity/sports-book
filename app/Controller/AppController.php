<?php
/**
 * Front App Controller
 *
 * Handles App Scaffold Actions
 *
 * @package    App
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('Controller', 'Controller');

App::import('Sanitize');
App::uses('CakeTime', 'Utility');
App::uses('CakeEvent', 'Event');
App::uses('Queue', 'Queue.Lib');

App::uses('AppListener', 'Event');
App::uses('SettingsListener', 'Event');
App::uses('UserListener', 'Event');
App::uses('LanguageListener', 'Event');
App::uses('StaffListener', 'Event');
App::uses('UserSettingsListener', 'Event');

App::uses('RunCpa', 'Lib/RunCpa');

App::import('Plugin/WebSocket/Lib/Network/Http', 'WebSocket', array('file'=>'WebSocket.php'));

use outcomebet\casino25\api\client\Client;
require __DIR__.'/../../vendor/autoload.php';

class AppController extends Controller
{
    /**
     * Controller name
     *
     * @var $name string
     */
    public $name = 'App';
    public $client;
    /**
     * Array containing the names of components this controller uses.
     *
     * @var $components array
     */
    public $components = array(
        'App',
        'Admin',
        'Acl',
        'Session',
        'Cookie',
        'Email',
        'Utils',
        'RequestHandler',
        'TimeZone',
        'Files',
        'PhpExcel',
        'Paginator',
//        'Security',
        'Auth'  =>  array(
//            'authorize'     =>  array(
//                'Actions'   =>  array('actionPath' => 'controllers')
//            ),
            'loginRedirect'     =>  array('plugin' => false, 'admin' => false, 'controller' => false, 'action' => '/'),
            'logoutRedirect'    =>  array('plugin' => false, 'admin' => false, 'controller' => false, 'action' => '/'),
            'loginAction'       =>  array('plugin' => false, 'admin' => false, 'controller' => 'users', 'action' => 'login'),
            'authenticate'      =>  array('Form')
        )
    );

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var $helpers array
     */
    public $helpers = array(
        0   =>  'App',
        1   =>  'Beth',
        2   =>  'MyForm',
        3   =>  'MyHtml',
        4   =>  'TimeZone',
        5   =>  'Html',
        6   =>  'Session',
        7   =>  'Form',
        8   =>  'Js',
        10  =>  'Text',
        11  =>  'Ticket',
        12  =>  'Content.Menu',
        13  =>  'Admin',
        14  =>  'Utils',
        15  =>  'PhpExcel',
        16  =>  'Sports',
        17  =>  'Countries',
        18  =>  'Language'
    );

    /**
     * The name of the View class this controller sends output to.
     *
     * @var $viewClass string
     */
    public $viewClass = 'Theme';

    /**
     * Theme
     *
     * @var string
     */
    public $theme = null;

    /**
     * Paginate
     *
     * @var array
     */
    public $paginate = array();

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array
     */
    public $uses = array(
        0   =>  'AppModel',
        1   =>  'Currency',
        2   =>  'User',
        3   =>  'Permission',
        4   =>  'Ticket',
        5   =>  'Group',
        6   =>  'Setting',
        7   =>  'Aro',
        8   =>  'Aco',
        9   =>  'Language',
        10  =>  'Bets.Bet',
        11  =>  'Content.News',
        12  =>  'Sport'
    );

    /**
     * User side theme name
     */
    const USER_THEME    =   'ChalkPro';

    /**
     * Admin side theme name
     */
    const ADMIN_THEME   =   'Admin';

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        $this->Auth->allow('receive_file', 'test', 'importSports', 'importCountries', 'importEvents');

        $this->getEventManager()->attach(new AppListener());
        $this->getEventManager()->attach(new SettingsListener());
        $this->getEventManager()->attach(new UserListener());
        $this->getEventManager()->attach(new LanguageListener());
        $this->getEventManager()->attach(new StaffListener());
        $this->getEventManager()->attach(new UserSettingsListener());

        $this->getEventManager()->dispatch(
            new CakeEvent('Controller.App.beforeFilter', $this, array())
        );

        $SportId     =  null;
        $LeagueId    =  null;
        $LeaguesMenu =  $this->request->params['controller']== "sports" &&
                        $this->request->params['action'] == "display" &&
                        count($this->request->params['pass']) >= 1
                        ?
                        $this->Sport->getPrintSportLeagues($this->request->params['pass'][0])
                        :
                        array();


        $LeaguesLiveMenu =  $this->request->params['controller']== "live" &&
                            $this->request->params['action'] == "display_sports" &&
                            count($this->request->params['pass']) >= 1
                            ?
                            $this->Sport->getLiveLeagues($this->request->params['pass'][0])
                            :
                            array();


        $SportId     =  !empty($LeaguesMenu) || !empty($LeaguesLiveMenu) ? $this->request->params['pass'][0] : null;
        $LeagueId    =  isset($this->request->params['pass'][1]) ?  $this->request->params['pass'][1] : null;

        $this->set('SportsLiveMenu', $this->Sport->getLiveMenuList());
        $this->set('SportsMenu', $this->Sport->getMenuList());


        $this->set('LeaguesMenu', $LeaguesMenu);
        $this->set('LeaguesLiveMenu', $LeaguesLiveMenu);

        $this->set('SportId', $SportId);
        $this->set('LeagueId', $LeagueId);

        $this->set('referer', $this->referer());

        RunCpa::getInstance()->captureRequest();

        parent::beforeFilter();
//        echo ("App controller Before");

        if ($this->Auth->loggedIn() && CakeSession::check('casino.balance') && !strpos($_SERVER['REQUEST_URI'],'/tickets/')){
            $this->client = new Client(array(
                'url' => 'https://api.c27.games/v1/',
                'sslKeyPath' => __DIR__.'/../../ssl/apikey.pem',
            ));
            $player=array(
                'PlayerId'=>$this->Auth->user('username')
            );
            $casinoBalance=$this->client->getBalance($player)['Amount'];
            $diffCasinoBalance=$casinoBalance-CakeSession::read('casino.balance');

//            CakeLog::write('casino', 'AppController-----  casinoBalance '.$casinoBalance.'     casino.balance '.CakeSession::read('casino.balance'));

            if ($diffCasinoBalance!=0){
                $newBalance=CakeSession::read('Auth.User.balance')+$diffCasinoBalance/100;
                $this->User->setBalance($this->Auth->user('id'),$newBalance);
//                CakeSession::write('old.balance', CakeSession::read('Auth.User.balance'));
                CakeSession::write('casino.balance', $casinoBalance);

                CakeLog::write('casino', 'AppController-------  changed.casino.balance '.$diffCasinoBalance.'  casino.balance '.CakeSession::read('casino.balance').'  User.balance '.CakeSession::read('Auth.User.balance').'  old.balance '.CakeSession::read('old.balance'));

            }


            CakeSession::write('changed.casino.balance', $diffCasinoBalance);

//            echo ('diff:'.$diffCasinoBalance.'  casino.balance='.$casinoBalance);
//            echo $_SERVER['REQUEST_URI'];
        }
    }

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
        echo ("300");
        $model = $this->__getModel($model);

        $this->request->data[$model] = $this->request->query;

        if (!empty($this->request->data)) {
            $conditions = array_merge(
                $conditions,
                $this->{$model}->getSearchConditions($this->request->data)
            );
        }

        if (!is_array($conditions)) {
            $parent = $this->{$model}->getParent();
            if ($parent != null) {
                $conditions = array(
                    $model . '.' . $this->$model->belongsTo[$parent]['foreignKey'] => $conditions
                );
            }
        }

        $AdminIndexSchema   =   $this->{$model}->getAdminIndexSchema();

        $PaginatorSettings  =   $this->{$model}->getPagination();

        $orderBy = $this->{$model}->getOrderBy();

        if (empty($orderBy)) {
            if ($this->{$model}->isOrderable()) {
                $PaginatorSettings['order'] = array($model . '.order' => 'desc');
            } else {
                $PaginatorSettings['order'] = array($model . '.id' => 'desc');
            }
        } else {
            $PaginatorSettings['order'] = $orderBy;
        }

        if (isset($PaginatorSettings['conditions'])) {
            $PaginatorSettings['conditions'] = array_merge(
                $PaginatorSettings['conditions'],
                $conditions
            );
        } else {
            $PaginatorSettings['conditions'] = $conditions;
        }

        $PaginatorSettings['fields']    =   $AdminIndexSchema;

        $this->{$model}->locale         =   Configure::read('Config.language');
        $this->Paginator->settings      =   array($model => $PaginatorSettings);

        $data                           =   $this->Paginator->paginate($model);

        foreach ($AdminIndexSchema AS $field) {
            if ($field == $model . '.amount' OR $field == $model . '.return' OR $field == $model . '.balance') {
                $fieldData = explode('.', $field);
                $total = $this->{$model}->find('first', array(
                        'contain'   =>  array(),
                        'fields'    =>  array(
                            'sum('. $field .') AS total'
                        ),
                        'conditions'    =>  $conditions
                    )
                );
                $this->set('total' . ucfirst($fieldData[1]), $total[0]['total']);
            }
        }

        $translate = (isset($this->{$model}->actsAs['Translate'])) ? true : false;

        if ($translate) {
            $locales = $this->Language->getLanguagesList();
            unset($locales[Configure::read('Config.language')]);
            $this->set('locales', $locales);
        }

        $this->set('translate', $translate);
        $this->set('actions', $this->{$model}->getActions($this->request));
        $this->set('controller', $this->params['controller']);

        $this->set('data', $data);

        return $data;
    }

    /**
     * Admin view scaffold functions
     *
     * @param int $id - view id
     *
     * @return void
     */
    public function admin_view($id = -1)
    {
        $model = $this->__getModel();
        $this->{$model}->locale = Configure::read('Config.language');
        $data = $this->{$model}->getView($id);

        if (!empty($data)) {
            $data = $this->{$model}->getAdminIndex($data);
            $this->set('fields', $data);
        } else {
            $this->Admin->setMessage(__('Error, not found.', true), 'error');
        }
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
        $model = $this->__getModel();

        $this->$model->validate = $this->$model->getValidation();

        if (!empty($this->request->data)) {
            //save changes
            $this->{$model}->locale = Configure::read('Config.language');
            if ($id != null) {
                $parent = $this->$model->getParent();
                $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
                $this->request->data[$model][$foreignKey] = $id;
            }

            if ($this->$model->isOrderable()) {
                $order = $this->$model->findLastOrder();
                $this->request->data[$model]['order'] = $order + 1;
            }

            $this->getEventManager()->dispatch(new CakeEvent('Controller.' . $this->name . '.beforeValidates', $this, array()));

            if ($this->{$model}->validates()) {
                $this->{$model}->create();
                if ($this->{$model}->save($this->request->data)) {
                    $this->getEventManager()->dispatch(new CakeEvent('Controller.' . $this->name . '.add', $this, array(
                        'dataId'   =>  $this->{$model}->id
                    )));
                    $this->Admin->setMessage(__('Item added', true), 'success');

                    switch ($this->name) {
                        case 'Leagues':
                            $this->redirect(array('controller' => 'sports', 'action' => 'admin_view', $this->request->data["League"]["country_id"]));
                            break;
                        case 'Events':
                            $this->redirect(array('plugin' => null, 'controller' => 'leagues', 'action' => 'admin_view', $this->request->data["Event"]["league_id"]));
                            break;
                        default:
                            $this->redirect(array('action' => 'admin_view', $this->{$model}->id));
                            break;
                    }
                }
            }

            if (is_array($this->{$model}->validationErrors) && !empty($this->{$model}->validationErrors)) {
                $currentError = current($this->{$model}->validationErrors);
                $this->Admin->setMessage(end($currentError), 'error');
            } else {
                $this->Admin->setMessage(__('This cannot be added.', true), 'error');
            }
        }

        if ($id != null) {
            $parent = $this->$model->getParent();
            $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
            $this->request->data[$model][$foreignKey] = $id;
        }

        $fields = $this->{$model}->getAdd();

        $this->set('fields', $fields);
    }

    /**
     * Admin Edit scaffold functions
     *
     * @param int $id - item id
     *
     * @throws NotFoundException
     *
     * @return void
     */
    public function admin_edit($id)
    {
        $model = $this->__getModel();

        $item = $this->$model->getItem($id);

        if (empty($item)) {
            throw new NotFoundException("Not found", 404);
        }

        $this->$model->validate = $this->$model->getValidation();

        if (!empty($this->request->data)) {
            $this->{$model}->locale = Configure::read('Config.language');
            $this->request->data[$model]['id'] = $id;
            if ($this->{$model}->save($this->request->data)) {
                $this->getEventManager()->dispatch(new CakeEvent('Controller.' . $this->name . '.save', $this, array(
                    'itemId'    =>  $this->{$model}->id,
                    'itemData'  =>  $this->{$model}->getItem($this->{$model}->id)
                )));
                $this->Admin->setMessage(__('Changes saved', true), 'success');

                $this->redirect( Router::url( $this->referer(), true ) );
            }

            if (is_array($this->{$model}->validationErrors) && !empty($this->{$model}->validationErrors)) {
                $currentError = current($this->{$model}->validationErrors);
                $this->Admin->setMessage(end($currentError), 'error');
            } else {
                $this->Admin->setMessage(__('This cannot be added.', true), 'error');
            }

        } else {
            $this->getEventManager()->dispatch(new CakeEvent('Controller.' . $this->name . '.edit', $this, array(
                'itemId'    =>  $id,
                'itemData'  =>  $item
            )));
        }

        $this->request->data = $this->$model->getItem($id);

        $this->set('fields', $this->{$model}->getEdit($this->request->data));
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
        $model = $this->__getModel();
        if ($this->$model->delete($id))
        {
            $this->Admin->setMessage(__('Item deleted', true), 'success');
        } else {
            $this->Admin->setMessage(__('This cannot be deleted.', true), 'error');
        }

        $this->redirect($this->referer(array('action' => 'index')));
    }

    /**
     * Admin Translate scaffold functions
     *
     * @param $id
     * @param null $locale
     */
    public function admin_translate($id, $locale = null)
    {
        $model = $this->__getModel();

        if (isset($locale)) {
            $this->{$model}->locale = $locale;
        }

        if (!empty($this->request->data)) {
            $this->{$model}->locale = $this->request->data[$model]['locale'];
            $this->request->data[$model]['id'] = $id;

            if ($this->{$model}->save($this->request->data)) {
                $this->Admin->setMessage(__('Item saved', true), 'success');
                $this->redirect(
                    array(
                        'action' => 'translate',
                        0   =>  $id,
                        1   =>  $this->{$model}->locale
                    )
                );
            }
            $this->Admin->setMessage(__('This cannot be added.', true), 'error');
        }

        $locales = $this->Language->getLanguagesList();

        unset($locales[Configure::read('Config.language')]);

        $this->request->data = $this->{$model}->getItem($id);

        $this->set('fields', $this->{$model}->getTranslate());
        $this->set('locales', $locales);

    }

    /**
     * Admin moveUp scaffold functions
     *
     * @param int $id - element id
     *
     * @return void
     */
    public function admin_moveUp($id)
    {
        $model = $this->__getModel();
        $this->$model->moveUp($id);
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Admin moveDown scaffold functions
     *
     * @param int $id - element id
     *
     * @return void
     */
    public function admin_moveDown($id)
    {
        $model = $this->__getModel();
        $this->$model->moveDown($id);
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Returns current working model
     *
     * @param null $model - Model Name
     *
     * @return null
     */
    public function __getModel($model = null)
    {
        if ($model == null) {
            $model = $this->AppModel->getModelName($this->name);
        }

        if (!isset($this->$model)) {
            $this->loadModel($model);
        }

        $this->set('model', $this->{$model}->name);
        $this->set('tabs', $this->$model->getTabs($this->request->params));
        $this->set('search_fields', $this->{$model}->getSearch());
        $this->set('orderable', $this->{$model}->isOrderable());
        $this->set('mainField', 1);
        $this->set('translate', false);

        return $model;
    }

    /**
     * Redirects to given $url, after turning off $this->autoRender.
     * Script execution is halted after the redirect.
     *
     * @param string|array $url    - A string or array-based URL pointing
     *                              to another location within the app
     *                              or an absolute URL
     * @param integer      $status - Optional HTTP status code (eg: 404)
     * @param boolean      $exit   - If true, exit() will be
     *                                called after the redirect
     *
     * @return void
     */
    public function redirect($url, $status = null, $exit = true)
    {
        if (is_array($url) && !isset($url["language"])) {
            if (!is_null($this->request->language)) {
                $url['language'] = $this->request->language;
            } else {
                if (!is_null(Configure::read('Config.language'))) {
                    $url['language'] = Configure::read('Config.language');
                }
            }
        }

        parent::redirect($url, $status, $exit);
    }
}
