<?php
/**
 * Contact Model
 *
 * Handles Contact Data Source Actions
 *
 * @package    Contact.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */


class Contact extends ContactAppModel{
    protected $_schema = array(
        'name' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '30'),
        'email' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '60'),
        'subject' => array('type' => 'string' , 'null' => false, 'default' => '', 'length' => '60'),
        'message' => array('type' => 'text' , 'null' => false, 'default' => ''),
    );

    var $useTable = false;  // Not using the database, of course.
     
    // All the fancy validation you could ever want.
    var $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'valErrMandatoryField',
                'last' => true
            )
        ),
        'subject' => array(
            'rule' => array('minLength', 5),
            'message' => 'Subject must be 5 characters long'
        ),
        'email' => array(          
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address'
            ),
        ),
        'message' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'please enter your message',
                'last' => true
            )
        ),
    );
 

}