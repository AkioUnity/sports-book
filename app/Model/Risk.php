<?php
/**
 * Risk Model
 *
 * Handles Risk Data Source Actions
 *
 * @package    Risks.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Risk extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Risk';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = false;
}