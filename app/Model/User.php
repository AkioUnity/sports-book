<?php
/**
 * User Model
 *
 * Handles User Data Source Actions
 *
 * @package    Users.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

class User extends AppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'User';

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
        'username'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'password'          => array(
            'type'      => 'string',
            'length'    => 40,
            'null'      => false
        ),
        'email'             => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'balance'           => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'time_zone'         => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'group_id'          => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'language_id'       => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'currency_id'       => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'odds_type'         => array(
            'type'      => 'tinyint',
            'length'    => 4,
            'null'      => true
        ),
        'allow_betslip' => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
        'status'            => array(
            'type'      => 'tinyint',
            'length'    => 4,
            'null'      => false
        ),
        'referal_id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'ip'                => array(
            'type'      => 'string',
            'length'    => 39,
            'null'      => false
        ),
        'last_visit'        => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'registration_date' => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        ),
        'personal_question' => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'personal_answer'   => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'first_name'        => array(
            'type'      => 'string',
            'length'    => 45,
            'null'      => true
        ),
        'last_name'         => array(
            'type'      => 'string',
            'length'    => 45,
            'null'      => true
        ),
        'date_of_birth'    => array(
            'type'      => 'date',
            'length'    => null,
            'null'      => true
        ),
        'address1'          => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => true
        ),
        'address2'          => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => true
        ),
        'zip_code'          => array(
            'type'      => 'string',
            'length'    => 45,
            'null'      => true
        ),
        'city'              => array(
            'type'      => 'string',
            'length'    => 45,
            'null'      => true
        ),
        'country'           => array(
            'type'      => 'string',
            'length'    => 45,
            'null'      => true
        ),
        'mobile_number'    => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => true
        ),
        'confirmation_code' => array(
            'type'      => 'string',
            'length'    => 20,
            'null'      => true
        ),
        'bank_name'         => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'account_number'    => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'bank_code'         => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'ticket_count'      => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => true
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var array
     */
    public $actsAs = array('Containable', 'Acl' => array('type' => 'requester', 'enabled' => false));

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array('Group', 'Language');

    public $hasOne = array('RuncpaModel');

    /**
     * Detailed list of hasMany associations.
     *
     * @var array
     */
    public $hasMany = array(
        0   =>  'Ticket',
        1   =>  'Deposit',
        2   =>  'Withdraw',
        3   =>  'BonusCodesUser',
        4   =>  'PaymentBonusUsage',
        5   =>  'UserSettings'
    );

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'allowEmpty' => false,
                'message' => 'Alphabets and numbers only'
            ),
            'between' => array(
                'rule' => array('between', 3, 15),
                'message' => 'Between 3 to 15 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            )
        ),

        'password_raw' => array(
            'rule' => array('minLength', '2'),
            'message' => 'Mimimum 2 characters long'
        ),

        'password_confirm' => array(
            'rule' => array('minLength', '2'),
            'message' => 'Mimimum 2 characters long'
        ),

        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email has already been registered.'
            )
        ),

        'first_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your name'
        ),

        'last_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your surname'
        ),

        'address1' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter your street address'
        ),

        'city' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter city'
        ),

        'country' => array(
            'rule' => array('minLength', '1'),
            'allowEmpty' => false,
            'message' => 'Please enter country'
        ),

        'date_of_birth' => array(
            'rule'          =>  'dateOfBirthValidation',
            'required'      =>  true,
            'allowEmpty'    =>  false,
            'message'       =>  'User should be over 21 years old to complete registration'
        ),

        'mobile_number' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter valid mobile number'
        ),

        'personal_question' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please your personal question'
        ),

        'personal_answer' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter valid answer'
        ),

    	/*'bank_name' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => true,
            'message' => 'Please enter valid answer'
    	),*/

    	/*'account_number ' => array(
    		'rule' => array('minLength', '2'),
    		'allowEmpty' => false,
    		'message' => 'Please enter valid answer'
    	),*/

        'agree' => array(
            'rule' => array('inList', array('1', 1, 'true', true, 'on')),
            'message' => 'You need to accept the Terms Of Use to be able to register.'
        )
    );

    /**
     * Constructor. Binds the model's database table to the object.
     *
     * @param bool $id    - Set this ID for this model on startup
     * @param null $table - Name of database table to use.
     * @param null $ds    - DataSource connection name
     */
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }

    /**
     * Acl Parent node
     *
     * @return array|null
     */
    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }

    /**
     * Bind ACL node
     *
     * @param array $user - user data array
     *
     * @return array
     */
    public function bindNode(array $user)
    {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getAdminIndexSchema()
    {
        return array(
            0   =>  'User.id',
            1   =>  'User.username',
            2   =>  'User.email',
            3   =>  'User.balance',
            4   =>  'User.registration_date',
            5   =>  'User.last_visit'
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
            0   =>  array(
                'name'          =>  __('Edit', true),
                'plugin'        =>  false,
                'controller'    =>  null,
                'action'        =>  'admin_edit',
                'class'         =>  'btn btn-mini btn-primary'
            ),

            1   =>  array(
                'name'          =>  __('Delete', true),
                'plugin'        =>  false,
                'controller'    =>  null,
                'action'        =>  'admin_delete',
                'class'         =>  'btn btn-mini btn-danger'
            ),

            2   =>  array(
                'name'          =>  __('Fund', true),
                'plugin'        =>  false,
                'controller'    =>  'deposits',
                'action'        =>  'admin_add',
                'class'         =>  'btn btn-mini btn-success'
            ),

            3   =>  array(
                'name'          =>  __('Charge', true),
                'plugin'        =>  false,
                'controller'    =>  'withdraws',
                'action'        =>  'admin_add',
                'class'         =>  'btn btn-mini btn-warning'
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
            'contain'       =>  array(),
            'fields'        =>  array(
                'User.id',
                'User.username',
                'User.email',
                'User.balance',
                'User.registration_date',
                'User.last_visit'
            ),
            'conditions'    =>  array(
                'User.group_id' => Group::USER_GROUP,
                'User.username IS NOT null'
            ),
            'limit'         =>  Configure::read('Settings.itemsPerPage')
        );
    }

    /**
     * Returns users by group
     *
     * @param int   $groupId    - user group id
     * @param array $conditions - conditions array
     *
     * @return array
     */
    public function getUsersByGroup($groupId = Group::USER_GROUP, $conditions = array())
    {
        return $this->find('all', array(
                'contain'       =>  array(),
                'conditions'    => array_merge($conditions, array('User.group_id' =>  $groupId)))
        );
    }

    /**
     * Returns scaffold view fields
     *
     * @param int $id - view entry id
     *
     * @return array
     */
    public function getView($id)
    {
        return $this->find('first', array(
            'fields'    =>  array(
                'User.id',
                'User.username',
                'User.email',
                'User.balance',
                'User.first_name',
                'User.last_name',
                'User.address1',
                'User.address2',
                'User.zip_code',
                'User.city',
                'User.country',
                'User.date_of_birth',
                'User.mobile_number',
                'User.last_visit',
                'User.bank_name',
                'User.account_number'
            ),
            'recursive'     =>  -1,
            'conditions'    =>  array(
                'User.id'       => $id,
                'User.group_id' => Group::USER_GROUP
            )
        ));
    }

    /**
     * Returns admin scaffold edit fields
     *
     * @return array
     */
    public function getEdit($data)
    {
        return array(
            'User.username'         =>  array('type' => 'text', 'label' => __("Username")),
            'User.email'            =>  array('type' => 'text', 'label' => __("Email")),
            'User.first_name'       =>  array('type' => 'text', 'label' => __("First Name")),
            'User.last_name'        =>  array('type' => 'text', 'label' => __("Last Name")),
            'User.status'           =>  $this->getFieldHtmlConfig('select', array('options' => array(1 => __('Active'), 0 =>  __('Not Activated'), 2 =>  __('Suspended')))),
            'User.verified'         =>  $this->getFieldHtmlConfig('select', array('options' => array(1 => __('Verified'), 0 =>  __('Not Verified')))),
            'User.address1'         =>  array('type' => 'text', 'label' => __("Address1")),
            'User.address2'         =>  array('type' => 'text', 'label' => __("Address2")),
            'User.zip_code'         =>  array('type' => 'text', 'label' => __("Zip code")),
            'User.city'             =>  array('type' => 'text', 'label' => __("City")),
            'User.country'          =>  array('type' => 'text', 'label' => __("Country")),
            'User.date_of_birth'    =>  array('type' => 'text', 'label' => __("Date Of Birth"), 'placeholder' => 'Y-m-d'),
            'User.mobile_number'    =>  array('type' => 'text', 'label' => __("Mobile number")),
            'User.allow_betslip'    =>  $this->getFieldHtmlConfig('select', array('options' => array(1 => __('Yes'), 0 =>  __('No')))),
            'User.min_stake'        =>  array('type' => 'number', 'label' => __("Min stake"), "placeholder" => __("Global limits")),
            'User.max_stake'        =>  array('type' => 'number', 'label' => __("Max stake"), "placeholder" => __("Global limits")),
            'User.max_winning'      =>  array('type' => 'number', 'label' => __("Max winning"), "placeholder" => __("Global limits")),
            'User.day_stake_limit'  =>  array('type' => 'number', 'label' => __("Day Stake Limit")),

        );
    }

    /**
     * Model scaffold search fields wrapper
     *
     * @return array
     */
    public function getSearch()
    {
        return array(
            'User.username'             =>  array('type' => 'text', 'label' => __("Username")),
            'User.email'                =>  array('type' => 'email', 'label' => __("Email")),
            'User.balance'              =>  array('type' => 'number', 'label' => __("Balance")),
            'User.registration_date'    =>  $this->getFieldHtmlConfig('date'),
            'User.last_visit'           =>  $this->getFieldHtmlConfig('date')

        );
    }

    /**
     * Returns admin scaffold add fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'User.username'         =>  array('type' => 'text', 'label' => __("Username")),
            'User.password_raw'     =>  array('type' => 'password', 'label' => __('Password')),
            'User.email'            =>  array('type' => 'text', 'label' => __("Email")),
            'User.first_name'       =>  array('type' => 'text', 'label' => __("First Name")),
            'User.last_name'        =>  array('type' => 'text', 'label' => __("Last Name")),
            'User.address1'         =>  array('type' => 'text', 'label' => __("Address1")),
            'User.address2'         =>  array('type' => 'text', 'label' => __("Address2")),
            'User.zip_code'         =>  array('type' => 'text', 'label' => __("Zip code")),
            'User.city'             =>  array('type' => 'text', 'label' => __("City")),
            'User.country'          =>  array('type' => 'text', 'label' => __("Country")),
            'User.date_of_birth'    =>  array('type' => 'text', 'label' => __("Date Of Birth"), 'placeholder' => 'Y-m-d'),
            'User.mobile_number'    =>  array('type' => 'text', 'label' => __("Mobile number")),
            'User.allow_betslip'    =>  $this->getFieldHtmlConfig('select', array('options' => array(1 => __('Yes'), 0 =>  __('No'))))
        );
    }

    public function findReferralId($id)
    {
        if (is_null($id) || !is_numeric($id)) {
            return null;
        }

        $user = $this->getItem($id);

        if (empty($user)) {
            return null;
        }

        return $id;
    }
    /**
     * Minimum registration age
     *
     * @param array $field         - Field data
     * @param null  $compare_field - Compare field
     *
     * @return bool
     */
    public function dateOfBirthValidation($field=array(), $compare_field=null)
    {
        if (!isset($field['date_of_birth'])) {
            return false;
        }

        return (floor( (strtotime(date('Y-m-d')) - strtotime($field['date_of_birth'])) / 31556926) >= 21);
    }

    /**
     * Saves new user data
     *
     * @param $data
     *
     * @return mixed
     */
    public function register($data)
    {
        $this->create();

        return $this->save($data);
    }

    /**
     * Updates user last visit date
     *
     * @param int $userId - UserId
     *
     * @return void
     */
    public function updateLastVisit($userId)
    {
        $User = $this->getItem($userId);
        $User['User']['last_visit'] = gmdate('Y-m-d H:i:s');
        $this->save($User, false);
    }

    /**
     * Adds deposit
     *
     * @param int   $userId - Adds Deposit
     * @param float $amount - Deposit amount
     *
     * @return int
     */
    public function addFunds($userId, $amount)
    {
        $User = $this->getItem($userId);

        if (empty($User)) {
            return 0;
        }

        $User['User']['balance'] += $amount;

        if ($User['User']['balance'] < 0) {
            $User['User']['balance'] = 0;
        }

        $this->save($User, false);

        return $User['User']['balance'];
    }

    /**
     * Adds user balance
     *
     * @param int   $userId - UserId
     * @param float $amount - Balance amount
     *
     * @return bool
     */
    public function addBalance($userId, $amount, $event = false)
    {
        if (!$event) {
            switch(CakeSession::read("Auth.User.group_id")) {
                case Group::ADMINISTRATOR_GROUP:
                case Group::BRANCH_GROUP:
                case Group::AGENT_GROUP:
                    $this->addFunds($userId, $amount);
                    break;
                default:
                    if(!$this->transferFunds(CakeSession::read('Auth.User.id'), $userId, $amount)) {
                        return false;
                    }
                    break;
            }

            return true;
        }

        $this->addFunds($userId, $amount);
    }

    /**
     * Transfer funds
     *
     * @param int   $fromUserId - From User Id
     * @param int   $toUserId   - To User Id
     * @param float $amount     - Amount
     *
     * @return bool
     */
    public function transferFunds($fromUserId, $toUserId, $amount)
    {
        if ($amount <= 0) {
            return false;
        }

        $fromUser = $this->getItem($fromUserId);
        $toUser = $this->getItem($toUserId);

        if (empty($fromUser) || empty($toUser)) {
            return false;
        }

        if ($fromUser['User']['balance'] - $amount < 0) {
            return false;
        }

        $fromUser['User']['balance'] -= $amount;
        $toUser['User']['balance'] += $amount;

        $this->save($fromUser, false);
        $this->save($toUser, false);

        if (CakeSession::read('Auth.User.id') == $fromUser['User']['id']) {
            CakeSession::write('Auth.User.balance', $fromUser['User']['balance']);
        }

        return true;
    }

    function getReport($from, $to, $userId = null, $limit = null) {
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'User.registration_date BETWEEN ? AND ?' => array($from, $to),
            'User.group_id' => Group::USER_GROUP
        );
        if ($userId != null)
            $options['conditions']['User.id'] = $userId;
        if ($limit != null)
            $options['limit'] = $limit;
        $data = $this->find('all', $options);
        $data['header'] = array(
            'User ID',
            'Date of registration',
            'Username',
            'Balance',
            'First name',
            'Last name',
            'Address first line',
            'Address second line',
            'Zip/Post code',
            'City',
            'Country',
            'Email',
            'Telephone number',
            'Date of birth'
        );
        return $data;
    }

    function getAllEmails() {
        return $this->find('list', array(
            'fields'        =>  array(
                'User.id',
                'User.email'
            ),
            'conditions'    =>  array(
                'User.group_id' => Group::USER_GROUP
            )
        ));
    }

    function getQuestions() {
        $questions = array('Favorite team?' => 'Favorite team?', 'Favorite food?' => 'Favorite food?', 'My dog name?' => 'My dog name?');
        return $questions;
    }

    function getCountriesList() {
        $countries = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan ',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain ',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad (Tchad)',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros (Comores)',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic of the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',           
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'KP' => 'North Korea',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territories',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',         
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'CS' => 'Serbia and Montenegro',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'KR' => 'South Korea',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia)',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States minor outlying islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );
        return $countries;
    }

    /**
     * Returns validation fields
     *
     * @return array
     */
    public function getValidation()
    {
        return array(
            'username' => array(
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'allowEmpty' => false,
                    'message' => 'Alphabets and numbers only'
                ),
                'between' => array(
                    'rule' => array('between', 5, 15),
                    'message' => 'Between 5 to 15 characters'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'This username has already been taken.'
                )
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address'
            ),
            'password_raw' => array(
                'rule' => array('minLength', '2'),
                'message' => 'Mimimum 2 characters long'
            )
        );
    }
}
