<?php
/**
 * Admin Model
 *
 * Handles Admin Data Source Actions
 *
 * @package    Admin.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Admin extends AppModel
{

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Admin';
}