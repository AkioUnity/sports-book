<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
    Router::parseExtensions('rss', 'json', 'xml', 'csv');

    Router::mapResources(array('api', 'pages'));

    Router::connect('/receive_file.php', array('language' => 'eng', 'plugin' => 'feeds', 'controller' => 'EnetPulse', 'action' => 'receive_file'));

    Router::connect('/:language/Feeds/OddService/receive_file', array('language' => 'eng', 'plugin' => 'feeds', 'controller' => 'OddService', 'action' => 'receive_file'));

    Router::connect('/admin321', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'login'));
    Router::connect('/:language/admin321', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'login'));

    Router::connect('/:language/getCountriesMenu', array('plugin' => null, 'controller' => 'countries', 'action' => 'getCountriesMenu'));
    Router::connect('/:language/getSportsMenu', array('plugin' => null, 'controller' => 'sports', 'action' => 'getSports'));
    Router::connect('/:language/sports/getSportsMenu2', array('plugin' => null, 'controller' => 'sports', 'action' => 'getSports'));

    Router::connect('/', array('plugin' => 'content', 'controller' => 'pages', 'action' => 'main', 'admin' => false));
    Router::connect('/:language', array('plugin' => 'content', 'controller' => 'pages', 'action' => 'main', 'admin' => false));

    Router::connect('/:language/sports/*', array('plugin' => null, 'controller' => 'sports', 'action' => 'display'));
    Router::connect('/:language/live-betting/event/*', array('plugin' => null, 'controller' => 'live', 'action' => 'display_event'));
    Router::connect('/:language/live-betting/*', array('plugin' => null, 'controller' => 'live', 'action' => 'display_sports'));
    Router::connect('/:language/pages/*', array('plugin' => 'content', 'controller' => 'pages', 'action' => 'display'));
    Router::connect('/:language/events/*', array('plugin' => 'events', 'controller' => 'events', 'action' => 'display'));
    Router::connect('/:language/contact/*', array('plugin' => 'contact', 'controller' => 'contact', 'action' => 'index'));
    Router::connect('/:language/livecasino/*', array('plugin' => 'Livecasino', 'controller' => 'livecasino', 'action' => 'index'));
    Router::connect('/:language/virtualsports/*', array('plugin' => 'Virtualsports', 'controller' => 'virtualsports', 'action' => 'index'));
    Router::connect('/lobby/*', array('controller' => 'lobby', 'action' => 'display'));
/**
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes(); // čia pluginų route'ai
/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
    require APPLIBS . 'Config' . DS . 'routes.php';