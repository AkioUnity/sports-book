<?php
/**
 * Mail Model
 *
 * Handles Mail Data Source Actions
 *
 * @package    Mails.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Mail extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Mail';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * List of validation rules.
     *
     * @var $validate array
     */
    public $validate = array(
        'email' => array(
            'rule' => 'email',
            'message' => 'Please enter valid email address'
        ),
    	/* validation bug fix */    	
        'subject' => array(
            'rule' => array('maxLength', '100'),
            'allowEmpty' => false,
            'message' => 'Please enter subject shorter than 100 length'
        ),
        'content' => array(
            'rule' => array('minLength', '1'),
            'allowEmpty' => false,
            'message' => 'Please enter message'
        )
    );

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        return array(
            0   =>  array(
                'name'      =>  __('Send Mail', true),
                'active'    =>  $params['action'] == 'admin_index',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'mails',
                    'action'        =>  'admin_index'
                )
            ),
            1   =>  array(
                'name'      =>  __('Send to All', true),
                'active'    =>  $params['action'] == 'admin_all',
                'url'       =>  array(
                    'plugin'        =>  null,
                    'controller'    =>  'mails',
                    'action'        =>  'admin_all'
                )
            ),
        );
    }
}