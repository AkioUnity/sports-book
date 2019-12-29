<?php
/**
 * Page Model
 *
 * Handles Page Data Source Actions
 *
 * @package    Pages.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class TemplateI18n extends AppModel {

    /**
     * Model name
     *
     * @var string $name
     */
    public $name = 'TemplateI18n';

    /**
     * Custom display field name.
     * Display fields are used by Scaffold, in SELECT boxes' OPTION elements.
     *
     * This field is also used in `find('list')`
     * when called with no extra parameters in the fields list
     *
     * @var string $displayField
     */
    public $displayField = 'field'; // important

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'templates_i18n';
}