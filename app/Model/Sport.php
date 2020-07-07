<?php
/**
 * Sport Model
 *
 * Handles Sport Data Source Actions
 *
 * @package    Sports.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppModel', 'Model');


class Sport extends AppModel
{

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Sport';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'name' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'active' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        ),
        'order' => array(
            'type' => 'bigint',
            'length' => 20,
            'null' => false
        ),
        'min_bet' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'max_bet' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'feed_type' => array(
            'type' => 'string',
            'length' => 10,
            'null' => false
        )
    );

    // Use a different model
    public $translateModel = 'SportI18n';

    /**
     * Use a different table for translate table
     *
     * @var string $translateTable
     */
    public $translateTable = 'sports_i18n';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Containable',
        'Translate' => array(
            0 => 'name'
        )
    );

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('League');

    /** Sport state active value */
    const SPORT_STATE_ACTIVE = 1;

    /** Sport state inactive value */
    const SPORT_STATE_INACTIVE = 0;

    /** Football type value */
    const SPORT_TYPE_FOOTBALL = 'Football';

    /** Tennis type value */
    const SPORT_TYPE_TENNIS = 'Tennis';

    const SPORT_TYPE_FORMULA_1 = 'Formula 1';

    /** Ice hockey type value */
    const SPORT_TYPE_ICE_HOCKEY = 'Ice Hockey';

    /** Floorball type value */
    const SPORT_TYPE_FLOORBALL = 'Floorball';

    /** Handball type value */
    const SPORT_TYPE_HANDBALL = 'Handball';

    /** Bandy type value */
    const SPORT_TYPE_BANDY = 'Bandy';

    const SPORT_TYPE_PESAPALLO = 'Pesapallo';

    const SPORT_TYPE_FIELD_HOCKEY = 'Field Hockey';

    /** Basketball type value */
    const SPORT_TYPE_BASKETBALL = 'Basketball';

    /** American football type value */
    const SPORT_TYPE_AMERICAN_FOOTBALL = 'American Football';

    /** Volleyball type value */
    const SPORT_TYPE_VOLLEYBALL = 'Volleyball';

    /** Baseball type value */
    const SPORT_TYPE_BASEBALL = 'Baseball';

    /** Boxing type value */
    const SPORT_TYPE_BOXING = 'Boxing';

    /** Darts type value */
    const SPORT_TYPE_DARTS = 'Darts';

    /** Badminton type value */
    const SPORT_TYPE_BADMINTON = 'Badminton';

    /** Motor sports type value */
    const SPORT_TYPE_MOTOR_SPORTS = 'Motor sports';

    /** Alpine skiing type value */
    const SPORT_TYPE_ALPINE_SKIING = 'Alpine Skiing';

    /** Snooker type value */
    const SPORT_TYPE_SNOOKER = 'Snooker';

    const SPORT_TYPE_SPEEDWAY = 'Speedway';

    /** Table tennis type value */
    const SPORT_TYPE_TABLE_TENNIS = 'Table Tennis';

    /** RUGBY_UNION type value */
    const SPORT_TYPE_RUGBY_UNION = 'Rugby Union';

    /** Rugby league type value */
    const SPORT_TYPE_RUGBY_LEAGUE = 'Rugby League';

    /** Equine sports type value */
    const SPORT_TYPE_EQUINE_SPORTS = 'Equine Sports';

    /** Curling type value */
    const SPORT_TYPE_CURLING = 'Curling';

    /** Waterpolo type value */
    const SPORT_TYPE_WATERPOLO = 'Waterpolo';

    const SPORT_TYPE_BEACH_SOCCER = 'Beach Soccer';

    /** Australian rules type value */
    const SPORT_TYPE_AUSTRALIAN_RULES = 'Australian Rules';

    const SPORT_TYPE_OLYMPIC_ATHLETICS = 'Olympic Athletics';

    /** Cricket type value */
    const SPORT_TYPE_CRICKET = 'Cricket';

    /** Hockey type value */
    const SPORT_TYPE_HOCKEY = 'Hockey';

    /** Beach volleyball type value */
    const SPORT_TYPE_BEACH_HOCKEY = 'Beach Hockey';

    const SPORT_TYPE_BEACH_VOLLEY = 'Beach Volley';

    /** Futsal type value */
    const SPORT_TYPE_FUTSAL = 'Futsal';

    /** Horse racing type value */
    const SPORT_TYPE_HORSE_RACING = 'Horse Racing';

    /** Cycling type value */
    const SPORT_TYPE_CYCLING = 'Cycling';

    /** Golf type value */
    const SPORT_TYPE_GOLF = 'Golf';

    /** Motorcycling type value */
    const SPORT_TYPE_MOTORCYCLING = 'Motorcycling';

    /** Rally type value */
    const SPORT_TYPE_RALLY = 'Rally';

    /** Martial Arts type value */
    const SPORT_TYPE_MARTIAL_ARTS = 'Martial Arts';

    /** Nascar type value */
    const SPORT_TYPE_NASCAR = 'Nascar';

    const SPORT_TYPE_BEACH_VOLLEYBALL = 'Volleyball';

    /** Football type value */
    const SPORT_ID_FOOTBALL = 1;

    const SPORT_ID_TENNIS = 2;

    const SPORT_ID_BASKETBALL = 4;

    const SPORT_ID_ICE_HOCKEY = 11;

    /**
     * Get item wrapper
     *
     * @param int $id - Item Id
     * @param array $contain - Contain array
     * @param int $recursive - Recursive
     *
     * @return array
     */
    public function getItem($id, $contain = array(), $recursive = -1)
    {
        $this->create();

        // find first translations not binding..
        $result = $this->find('all', array(
            'recursive' => $recursive,
            'contain' => $contain,
            'conditions' => array(
                $this->name . '.id' => $id
            )
        ), true);

        return empty($result) ? array() : current($result);
    }

    /**
     * Default pagination scaffold settings
     *
     * @return array - returns default pagination settings
     */
    public function getPagination()
    {
        return array(
            'contain' => array(),
            "fields" => array(
                0 => 'Sport.id',
                1 => 'Sport__i18n_name',
                2 => 'Sport.active'
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        );
    }

    /**
     * Returns scaffold actions list
     *
     * @param CakeRequest $cakeRequest - cakeRequest object
     *
     * @return array
     */
    public function getActions(CakeRequest $cakeRequest)
    {
        return array(
            0 => array(
                'name' => __('View', true),
                'controller' => null,
                'action' => 'admin_view',
                'class' => 'btn btn-mini'
            ),

            1 => array(
                'name' => __('Edit', true),
                'controller' => null,
                'action' => 'admin_edit',
                'class' => 'btn btn-mini btn-primary'
            ),

            2 => array(
                'name' => __('Add League', true),
                'controller' => 'leagues',
                'action' => 'admin_add',
                'class' => 'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        $fields = array(
            'Sport.id' => array(),
            'Sport.active' => array('type' => 'checkbox')
        );
        return $fields;
    }

    /**
     * Inserts | updates Sport
     *
     * @param null $importId
     * @param $sportName
     * @param null $feedType
     * @param bool $update - update | force insert
     *
     * @return array|bool|int|mixed|string
     */
    public function insertSport($importId = null, $sportName, $feedType = null, $update = true)
    {
        $this->create();

        $data = array(
            'Sport' => array(
                'import_id' => $importId,
                'name' => $sportName,
                'updated' => time()
            )
        );

        if ($update == true) {
            $sport = $this->find('first', array(
                    'recursive' => -1,
                    'conditions' => array(
                        'Sport.import_id' => $importId,
                        'Sport.feed_type' => $feedType
                    ))
            );

            if (!empty($sport)) {
                $this->id = $sport['Sport']['id'];

                $this->save($data);

                return $this->id;
            }
        }

        $data['Sport']['active'] = 1;
        $data['Sport']['order'] = $this->findLastOrder() + 1;
        $data['Sport']['feed_type'] = $feedType;

        $sportData = $this->save($data, false);

        return $sportData['Sport']['id'];
    }

    /**
     * Returns sport by id
     *
     * @param $sportId
     * @param null $active
     * @return array
     *
     * todo: check for active?
     */
    public function getSport($sportId, $fields = array('id', 'import_id', 'name', 'active', 'order', 'min_bet', 'max_bet', 'updated', 'feed_type'), $conditions = array())
    {
        $this->bindTranslation(array('name'));
        $options = array(
            'fields' => $fields,
            'conditions' => array(
                'Sport.id' => $sportId
            )
        );

        $options['conditions'] = array_merge($options['conditions'], $conditions);

        return $this->find('first', $options);
    }

    public function getPrintSportLeagues($sportId)
    {
        $this->bindTranslation(array('name'));
        $options = array(
            'fields' => array('Sport.id', 'Sport.name'),
            'contain' => array(
                'League' => array(
                    'fields' => array(
                        'League.id',
                        'League.country_id',
                        'League.name'
                    ),
                    'conditions' => array(
                        'League.sport_id' => $sportId,
                        'League.active' => 1
                    )
                )
            ),
            'conditions' => array(
                'Sport.id' => $sportId,
                'Sport.active' => 1
            )
        );

        return $this->find('first', $options);
    }

    public function getLiveLeagues($sportId)
    {
        $list = array_unique(array_map(function ($league) {
            return $league["League"]["id"];
        }, $this->League->getLiveLeagues()));

        $this->bindTranslation(array('name'));
        $options = array(
            'fields' => array('Sport.id', 'Sport.name'),
            'contain' => array(
                'League' => array(
                    'fields' => array(
                        'League.id',
                        'League.country_id',
                        'League.name'
                    ),
                    'conditions' => array(
                        'League.id' => $list,
                        'League.sport_id' => $sportId,
//                        'League.active'     =>  1
                    )
                )
            ),
            'conditions' => array(
                'Sport.id' => $sportId,
                'Sport.active' => 1
            )
        );

        return $this->find('first', $options);
    }

    /**
     * Returns Sport By ImportId
     *
     * @param $importId
     * @return array
     */
    public function getSportByImportId($importId)
    {
        $this->contain();

        return $this->find('first', array(
            'conditions' => array(
                'Sport.import_id' => $importId
            )
        ));
    }

    /**
     * Returns sports list
     *
     * @return array
     */
    public function getSportsList()
    {
        $options = array(

            'conditions' => array(
                'Sport.active' => 1
            ),

            'order' => 'Sport__i18n_name ASC'
        );

        $options['contain'] = array(
            'League' => array(
                'conditions' => array('League.active' => true)
            )
        );

        $sports = $this->find('all', $options);

        $list = array();

        foreach ($sports as $sport) {
            $leagues = array();
            $count = 0;

            foreach ($sport['League'] as $league) {
                if ($this->League->isActive($league)) {
                    $count++;
                    $leagues[] = $league; //add league
                }
            }

            if ($count > 0) {
                $list[] = $sport['Sport'];
            }
        }

        return $list;
    }

    /**
     * Returns sports list with leagues
     *
     * @param null $countryId
     * @param bool $returnLeagues
     * @return array
     */
    public function getSports($countryId = null, $returnLeagues = true)
    {
        $LeagueConditions = array(
            'League.active' => 1
        );

        if ($countryId != null) {
            $LeagueConditions['League.country_id'] = $countryId;
        }

        if ($returnLeagues == true) {
            $contain = array(
                'League' => array(
                    'conditions' => $LeagueConditions,
                    'order' => array('League.name ASC')
                )
            );
        } else {
            $this->contain();
            $contain = null;
        }

        $Sports = $this->find('all', array(
            'conditions' => array(
                'Sport.active' => 1
            ),
            'contain' => $contain,
            'order' => array('Sport__i18n_name ASC')
        ));

        if ($countryId == null) {
            return $Sports;
        }

        foreach ($Sports AS $index => $Sport) {
            if (empty($Sport['League'])) {
                unset($Sports[$index]);
            }
        }

        return array_values($Sports);
    }

    /**
     * Updates Risks
     *
     * @param $sports
     * @return mixed
     */
    public function updateRisk($sports)
    {
        $data = array();

        foreach ($sports['Sport'] as $key => $value) {
            $value['id'] = $key;
            $data[]['Sport'] = $value;
        }

        return $this->saveAll($data);
    }

    public function getLiveMenuList()
    {
        $this->bindTranslation(array('name' => 'SportI18n'));
        $liveLeagues=$this->League->getLiveLeagues();

        $array_map=array_map(function ($league) {
            if (isset($league["League"]["sport_id"]))
                return $league["League"]["sport_id"];
//            else
//                print_r($league);  //  Undefined index: League
        }, $liveLeagues);

        $list = array_unique($array_map);

        return $this->find('all', array(
            "recursive" => -1,
            "contain" => array('SportI18n' => array('conditions' => array('SportI18n.locale' => 'eng'))),
            "conditions" => array(
                "Sport.id" => $list
            ),
            'fields' => array(
                'Sport.id',
                'Sport.name'
            )
        ));
    }

    public function getMenuList()
    {
        $this->bindTranslation(array('name' => 'SportI18n'));

        return $this->find('all', array(
            "recursive" => -1,
            "contain" => array('SportI18n' => array('conditions' => array('SportI18n.locale' => 'eng'))),
            "conditions" => array(
                'Sport.active' => 1,
                'Sport.menu_active' => 1
            ),
            'fields' => array(
                'Sport.id',
                'Sport.name'
            ),
            'order' => 'Sport.order'

        ));
    }

    /**
     * Returns list
     *
     * @return array
     */
    public function getList()
    {
        $this->bindTranslation(array('name'));
        return $this->find('list', array(
            "conditions" => array(
                "Sport.active" => 1
            ),
            'fields' => array(
                'Sport.id',
                'Sport.name'
            )
        ));
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        $fields = parent::getAdminIndexSchema();
        $fields[array_search('Sport.import_id', $fields)] = 'Sport.name';

        $key = array_search('Sport.feed_type', $fields);

        if ($key) {
            unset($fields[$key]);
        }

        return $fields;
    }

    public function getOrderBy()
    {
        return array($this->name . ".id" => 'asc');
    }
}