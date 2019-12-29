<?php
/**
 * Language Model
 *
 * Handles Language Data Source Actions
 *
 * @package    Languages.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Language extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Language';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'name'              => array(
            'type'      => 'string',
            'length'    => 10,
            'null'      => false
        ),
        'language'          => array(
            'type'      => 'string',
            'length'    => 30,
            'null'      => false
        )
    );

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        $actions = parent::getActions($cakeRequest);
        unset($actions[0]);
        unset($actions[1]);
        return $actions;
    }

    /**
     * Returns admin scaffold tabs
     *
     * @param array $params - url params
     *
     * @return array
     */
    public function getTabs(array $params)
    {
        $tabs = parent::getTabs($params);
        unset($tabs['Languagesadmin_search']);
        return $tabs;
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        $LanguagesList = array();

        foreach (I18n::getInstance()->l10n->catalog() as $key => $value) {
            $LanguagesList[$key] = $value['language'];
        }

        return array(
            'Language.name' => array(
                'label' => __('Language', true),
                'type' => 'select',
                'options' => $LanguagesList
            )
        );
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        return array(
            'fields'    =>  array(
                'Language.id',
                'Language.language'
            ),
            'conditions'    =>  array(
                'Language.id <>' => 1
            ),
            'limit'         =>  Configure::read('Settings.itemsPerPage')
        );
    }

    public function getLanguagesList2()
    {
        $list = array();

        foreach ($this->get() AS $lang) {
            $list[$lang["name"]] = $lang["language"];
        }

        return $list;
    }

    /**
     * Returns all languages entries
     *
     * @return array
     */
    public function get()
    {
        $Languages = array();

        foreach ($this->find('all') as $Language) {
            $Languages[$Language['Language']['name']] = $Language['Language'];
        }

        return $Languages;
    }

    public function getSlugById($languageId)
    {
        $ids = $this->getLanguagesListIds();

        return $ids[$languageId];
    }


    /**
     * Returns languages list
     *
     * @return array
     */
    public function getLanguagesList()
    {
        $Languages = array();

        foreach ($this->get() as $key => $value) {
            $Languages[$key] = $value['language'];
        }

        return $Languages;
    }

    /**
     * Returns languages ids
     *
     * @return array
     */
    public function getLanguagesListIds()
    {
        return $this->find('list', array(
                'Language.id',
                'Language.language'
            )
        );
    }
}