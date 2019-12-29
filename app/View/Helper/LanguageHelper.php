<?php
 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppHelper', 'View/Helper');
App::uses('LanguageComponent', 'Controller/Component');

class LanguageHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Language';

    public $components = array(
        0   =>  'Cookie'
    );

    /**
     * Returns current or specified language name
     *
     * @param null $language - language code
     *
     * @return string - language name
     */
    public function getName($language = null)
    {
        $availableLanguages = $this->getLanguages();

        if (is_null($language)) {
            $language = $this->getLanguage();
        }

        if (isset($availableLanguages[$language])) {
            return $availableLanguages[$language]['language'];
        }

        return '';
    }
    /**
     * Returns current language
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return  Configure::read('Config.language');
    }

    public function getLanguage2Img($lang = null)
    {
        if (is_null($lang)) {
            $lang = $this->getLanguage();
        }

        return $lang;
    }

    /**
     * Returns all current available languages
     *
     * @return array
     */
    public function getLanguages()
    {
        return  Configure::read('Config.languages');
    }
}