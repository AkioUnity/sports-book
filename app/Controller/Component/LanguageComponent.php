<?php
/**
 * Language Controller Component
 *
 * This is base Language Component Class
 * Class provides reusable bits of controller logic that can be composed into another controllers.
 *
 * @package     Language.Controller
 * @author      Deividas Petraitis <deividas@laiskai.lt>
 * @copyright   2013 The ChalkPro Betting Scripts
 * @license     http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version     Release: @package_version@
 * @link        http://www.chalkpro.com/
 * @see         Controller::$components
 */

class LanguageComponent extends Component
{
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');

    /**
     * Returns current language
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return array_search(
            Configure::read('Config.language'),
            Configure::read('Config.languages')
        );
    }
}