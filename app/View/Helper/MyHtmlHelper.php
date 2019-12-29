<?php

App::uses('HtmlHelper', 'View/Helper');
App::import('Component', 'AclComponent');
App::uses('ComponentCollection', 'Controller');
App::uses('Group', 'Model');

/**
 * Class MyHtmlHelper
 */
class MyHtmlHelper extends HtmlHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'MyHtml';

    /**
     * ACL
     *
     * @var AclComponent
     */
    public $Acl = null;

    /**
     * ACL
     *
     * @var Group
     */
    public $Group = null;

    /**
     * Helpers list
     *
     * @var array
     */
    public $helpers = array(
        0   =>  'Html',
        1   =>  'Session'
    );

    /**
     * Default Constructor
     *
     * @param View $View The View this helper is being attached to.
     * @param array $settings Configuration settings for the helper.
     */
    public function __construct(View $View, $settings = array()) {
        $collection = new ComponentCollection();
        $this->Acl = new AclComponent($collection);
        $this->Group = new Group();
        parent::__construct($View, $settings);
    }

    /**
     * Builds custom link
     *
     * @param $name
     * @param $url
     * @return mixed
     */
    public function customLink($name, $url)
    {
        if (preg_match('/^http:/', $url)) {
            return $this->MyHtml->link($name, $url);
        } else if (preg_match('/\//', $url)) {
            $parts = explode('/', $url, 3);
            if (!isset($parts[2]))
                $parts[2] = '';
            return $this->MyHtml->link($name, array('controller' => $parts[0], 'action' => $parts[1], $parts[2]));
        } else {
            return $this->MyHtml->link($name, array('controller' => 'pages', 'action' => $url));
        }
    }

    /**
     * Builds custom url
     *
     * @param $url
     * @return mixed
     */
    public function customUrl($url)
    {
        if (preg_match('/^http:/', $url)) {
            return $url;
        } else if (preg_match('/\//', $url)) {
            $parts = explode('/', $url, 3);
            if (!isset($parts[2]))
                $parts[2] = '';
            return $this->Html->url(array('plugin' => false, 'controller' => $parts[0], 'action' => $parts[1], $parts[2]), true);
        } else {
            return $this->Html->url(array('plugin' => false, 'controller' => 'pages', 'action' => $url), true);
        }
    }

    /**
     * Creates an HTML link.
     *
     * @param string $title          - The content to be wrapped by <a> tags.
     * @param array  $url            - Cake-relative URL or array of URL parameters
     *                                 or external URL (starts with http://)
     * @param array  $options        - Array of options and HTML attributes.
     * @param bool   $confirmMessage - JavaScript confirmation message.
     *
     * @return string - An `<a />` element.
     */
    public function link($title, $url = array(), $options = array(), $confirmMessage = false)
    {
        if (is_array($url) && !isset($url['language'])) {
            $url['language'] = Configure::read('Config.language');
        }

        if (isset($url['controller']) && (!isset($options['aco']) || (isset($options['aco']) &&  $options['aco'] !== false))) {
            $acos = $this->checkAcl($url);
            unset($options['aco']);
        } else {
            $acos = true;
        }

        if ($acos) {
            return parent::link($title, $url, $options, $confirmMessage);
        } else if (isset($options['returnText'])) {
            return $title;
        } else {
            return "";
        }
    }

    /**
     * @param       $title
     * @param null  $url
     * @param array $options
     * @param bool  $confirmMessage
     *
     * @return string
     */
    public function spanLink($title, $url = null, $options = array(), $confirmMessage = false)
    {
        $options['escape'] = false;
        return $this->link('<span>' . $title . '</span>', $url, $options, $confirmMessage);
    }

    /**
     * Checks ACL by Url
     *
     * @param array $url
     * @return bool
     */
    public function checkAcl($url = array())
    {
        $plugin     = isset($url['plugin']) ? $url['plugin'] : null;
        $controller = isset($url['controller']) ? $url['controller'] : null;
        $action     = isset($url['action']) ? $url['action'] : null;

        $userGroup = $this->Session->read('Auth.User.Group.id') == null ? Group::GUEST_GROUP : $this->Session->read('Auth.User.Group.id');

        $role_data = $this->Group->read(null, $userGroup);

        $aro_node = $this->Acl->Aro->node($role_data);

        $location = ( implode( '/', array_filter( array( 'controllers', $plugin, $controller, $action ) ) ) );

        $aco_node = $this->Acl->Aco->node($location);

        if(!empty($aco_node) && !empty($aro_node))
        {
            if($this->Acl->check($role_data,  $location))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks ACL by multiple url
     *
     * @param array $urls
     * @return bool
     */
    public function checkAcls($urls = array())
    {
        foreach ($urls as $url) {
            $u = array('controller' => $url[0], 'action' => $url[1]);
            if ($this->checkAcl($u)) {
                return true;
            }
        }
        return false;
    }
}