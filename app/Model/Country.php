<?php
/**
 * Country Model
 *
 * Handles Country Data Source Actions
 *
 * @package    Countries.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');

class Country extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Country';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'import_id' => array(
            'type'      => 'bigint',
            'length'    => 255,
            'null'      => false
        ),
        'name'      => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'updated'   => array(
            'type'      => 'int',
            'length'    => 9,
            'null'      => false
        )
    );

    // Use a different model
    public $translateModel = 'CountryI18n';

    /**
     * Use a different table for translate table
     *
     * @var string $translateTable
     */
    public $translateTable = 'countries_i18n';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Containable',
        'Translate' => array(
            0   =>  'name'
        )
    );

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array(
        'League' => array(
            'className' => 'League',
            'foreignKey' => 'country_id',
            'dependent' => false
        )
    );

    /**
     * Active country const value
     */
    const STATE_COUNTRY_ACTIVE = 1;

    /**
     * Inactive country const value
     */
    const STATE_COUNTRY_INACTIVE = 0;

    /**
     * Inserts | Updates Country
     *
     * @param $importId
     * @param $name
     * @param bool $update
     * @return mixed
     */
    public function insertCountry($importId, $name, $update = true)
    {
        $this->create();

        $data = array(
            'Country'    =>  array(
                'name'      =>  $name,
                'updated'   =>  time()
            )
        );

        if($update == true) {

            $Country = $this->find('first', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Country.import_id' => $importId
                )
            ));

            if(!empty($Country)) {
                $this->id = $Country['Country']['id'];

                $this->save($data);

                return $this->id;
            }
        }

        $CountryData = $this->save(array(
            'Country'    =>  array(
                'import_id' =>  $importId,
                'name'      =>  $name,
                'active'    =>  1,
                'updated'   =>  time()
            )
        ), false);

        return $CountryData['Country']['id'];
    }

    /**
     * Returns all countries list
     *
     * @param null $active
     * @param null $sportId
     * @return array
     */
    public function getCountries($active = null, $sportId = null)
    {
        $CountryConditions = array();

        if($active != null) {
            $CountryConditions['Country.active'] = $active;
        }

        if($sportId != null) {
            $contain = array(
                'League' =>  array(
                    'conditions'    =>  array(
                        'League.sport_id'   =>  $sportId,
                        'League.active'     =>  1
                    ),
                    'order'         =>  array('League.name ASC')
                )
            );
        }else{
            $this->contain();
            $contain = null;
        }

        $Countries = $this->find('all', array(
            'recursive'     =>  -1,
            'conditions'    =>  $CountryConditions,
            'contain'       =>  $contain,
            'order'         =>  array('Country__i18n_name ASC')
        ));

        if($sportId == null) {
            return $Countries;
        }

        foreach($Countries AS $index => $Country) {
            if(empty($Country['League'])) {
                unset($Countries[$index]);
            }
        }

        return array_values($Countries);
    }

    /**
     * Returns sport by id
     *
     * @param $countryId
     * @param null $active
     * @return array
     */
    public function getCountry($countryId, $active = null)
    {
        $options['conditions'] = array(
            'Country.id'          =>  $countryId
        );

        if($active != null) {
            $options['conditions']['active'] = $active;
        }

        return $this->find('first', $options);
    }

    /**
     * Returns country entry by import id
     *
     * @param $countryImportId
     * @return array
     */
    public function getCountryByImportId($countryImportId)
    {
        return $this->find('first', array(
            'recursive'     =>  -1,
            'contain'       =>  array(),
            'conditions'    =>  array(
                'Country.import_id'  => $countryImportId
            )
        ));
    }

    /**
     * Returns list
     *
     * @return array
     */
    public function getList()
    {
        return $this->find('list', array(
            'fields'    =>  array(
                'Country.id',
                'Country.name'
            )
        ));
    }
}