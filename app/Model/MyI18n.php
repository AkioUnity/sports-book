<?php
/**
 * MyI18n Model
 *
 * Handles MyI18n Data Source Actions
 *
 * @package    MyI18n.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class MyI18n extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'MyI18n';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'i18n';

    /**
     * Deletes all translations
     *
     * @param mixed $conditions
     * @param bool $cascade
     * @param bool $callbacks
     * @return bool|void
     */
    public function deleteAll($conditions, $cascade = true, $callbacks = false)
    {
        $conditions = array(
            'MyI18n.locale' => $conditions
        );
        parent::deleteAll($conditions, $cascade, $callbacks);
    }
}