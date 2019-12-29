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

class SettingFixture extends CakeTestFixture
{
    /**
     *
     * @var $import string
     */
    public $import = 'Setting';

    /**
     * Initialize the fixture.
     *
     * @return void
     */
    public function init()
    {
        $this->records = array(
            0   =>  array(
                'id'    =>  1,
                'key'   =>  'defaultCurrency',
                'value' =>  '1'
            ),
            1   =>  array(
                'id'    =>  2,
                'key'   =>  'shortDate',
                'value' =>  'Y-m-d'
            ),
            2   =>  array(
                'id'    =>  3,
                'key'   =>  'registration',
                'value' =>  1
            ),
            3   =>  array(
                'id'    =>  4,
                'key'   =>  'defaultTitle',
                'value' =>  'ChalkPro Betting Platfrom'
            ),
            4   =>  array(
                'id'    =>  5,
                'key'   =>  'contactMail',
                'value' =>  'chalkpro@outlook.com'
            ),
            5   =>  array(
                'id'    =>  6,
                'key'   =>  'maxBet',
                'value' =>  100000
            ),
            6   =>  array(
                'id'    =>  7,
                'key'   =>  'minBet',
                'value' =>  1
            ),
            7   =>  array(
                'id'    =>  8,
                'key'   =>  'maxWin',
                'value' =>  250000
            ),
            9   =>  array(
                'id'    =>  10,
                'key'   =>  'charset',
                'value' =>  'utf-8'
            ),
            11  =>  array(
                'id'    =>  12,
                'key'   =>  'metaDescription',
                'value' =>  'ChalkPro Betting Platfrom'
            ),
            12  =>  array(
                'id'    =>  13,
                'key'   =>  'metaKeywords',
                'value' =>  'ChalkPro Betting Platfrom'
            ),
            14  =>  array(
                'id'    =>  15,
                'key'   =>  'metaAuthor',
                'value' =>  'ChalkPro'
            ),
            16  =>  array(
                'id'    =>  17,
                'key'   =>  'metaReplayTo',
                'value' =>  'chalkpro@outlook.com'
            ),
            17  =>  array(
                'id'    =>  18,
                'key'   =>  'metaCopyright',
                'value' =>  'ChalkPro'
            ),
            18  =>  array(
                'id'    =>  19,
                'key'   =>  'metaRevisitTime',
                'value' =>  '1 day'
            ),
            19  =>  array(
                'id'    =>  20,
                'key'   =>  'metaIdentifierUrl',
                'value' =>  'n/a'
            ),
            20  =>  array(
                'id'    =>  21,
                'key'   =>  'defaultTimezone',
                'value' =>  '+0.0'
            ),
            21  =>  array(
                'id'    =>  22,
                'key'   =>  'defaultLanguage',
                'value' =>  1
            ),
            22  =>  array(
                'id'    =>  23,
                'key'   =>  'printing',
                'value' =>  1
            ),
            23  =>  array(
                'id'    =>  24,
                'key'   =>  'bigDeposit',
                'value' =>  10000
            ),
            24  =>  array(
                'id'    =>  25,
                'key'   =>  'bigWithdraw',
                'value' =>  50000
            ),
            25  =>  array(
                'id'    =>  26,
                'key'   =>  'bigStake',
                'value' =>  5000
            ),
            26  =>  array(
                'id'    =>  27,
                'key'   =>  'bigOdd',
                'value' =>  10
            ),
            27  =>  array(
                'id'    =>  28,
                'key'   =>  'bigWinning',
                'value' =>  10000
            ),
            28  =>  array(
                'id'    =>  29,
                'key'   =>  'defaultTheme',
                'value' =>  'Green'
            ),
            29  =>  array(
                'id'    =>  30,
                'key'   =>  'itemsPerPage',
                'value' =>  15
            ),
            30  =>  array(
                'id'    =>  31,
                'key'   =>  'ticketPreview',
                'value' =>  1
            ),
            31  =>  array(
                'id'    =>  32,
                'key'   =>  'minDeposit',
                'value' =>  1000
            ),
            32  =>  array(
                'id'    =>  33,
                'key'   =>  'copyright',
                'value' =>  'Copyright - 2013'
            ),
            33  =>  array(
                'id'    =>  34,
                'key'   =>  'websiteName',
                'value' =>  'ChalkPro'
            ),
            34  =>  array(
                'id'    =>  35,
                'key'   =>  'referals',
                'value' =>  1
            ),
            35  =>  array(
                'id'    =>  36,
                'key'   =>  'maxDeposit',
                'value' =>  500000
            ),
            36  =>  array(
                'id'    =>  37,
                'key'   =>  'minWithdraw',
                'value' =>  1000
            ),
            37  =>  array(
                'id'    =>  38,
                'key'   =>  'maxWithdraw',
                'value' =>  10000
            ),
            38  =>  array(
                'id'    =>  39,
                'key'   =>  'passwordReset',
                'value' =>  1
            ),
            39  =>  array(
                'id'    =>  40,
                'key'   =>  'login',
                'value' =>  1
            ),
            40  =>  array(
                'id'    =>  41,
                'key'   =>  'maxBetsCount',
                'value' =>  16
            ),
            41  =>  array(
                'id'    =>  42,
                'key'   =>  'minBetsCount',
                'value' =>  1
            ),
            42  =>  array(
                'id'    =>  43,
                'key'   =>  'allowMultiSingleBets',
                'value' =>  0
            ),
            43  =>  array(
                'id'    =>  44,
                'key'   =>  'showClock',
                'value' =>  0
            ),
            44  =>  array(
                'id'    =>  45,
                'key'   =>  'deposits',
                'value' =>  1
            ),
            45  =>  array(
                'id'    =>  46,
                'key'   =>  'withdraws',
                'value' =>  1
            ),
            46  =>  array(
                'id'    =>  47,
                'key'   =>  'lastDepositUpdate',
                'value' =>  '2013-10-28 22:10:55'
            ),
            47  =>  array(
                'id'    =>  48,
                'key'   =>  'referral_deposit_percentage',
                'value' =>  10
            ),
            48  =>  array(
                'id'    =>  49,
                'key'   =>  'left_promo_header',
                'value' =>  'Display promotions'
            ),
            49  =>  array(
                'id'    =>  50,
                'key'   =>  'left_promo_body',
                'value' =>  ''
            ),
            50  =>  array(
                'id'    =>  51,
                'key'   =>  'right_promo_header',
                'value' =>  'Get direct quote'
            ),
            51  =>  array(
                'id'    =>  52,
                'key'   =>  'right_promo_body',
                'value' =>  ''
            ),
            52  =>  array(
                'id'    =>  53,
                'key'   =>  'left_promo_enabled',
                'value' =>  0
            ),
            53  =>  array(
                'id'    =>  54,
                'key'   =>  'right_promo_enabled',
                'value' =>  1
            ),
            54  =>  array(
                'id'    =>  55,
                'key'   =>  'D_Manual',
                'value' =>  1
            ),
            55  =>  array(
                'id'    =>  56,
                'key'   =>  'eTranzactStatus',
                'value' =>  0
            ),
            56  =>  array(
                'id'    =>  57,
                'key'   =>  'show_sub_event_id',
                'value' =>  1
            ),
            57  =>  array(
                'id'    =>  58,
                'key'   =>  'show_main_event_id',
                'value' =>  1
            ),
            58  =>  array(
                'id'    =>  59,
                'key'   =>  'bottom_promo_header',
                'value' =>  'Contact for pricing'
            ),
            59  =>  array(
                'id'    =>  60,
                'key'   =>  'bottom_promo_body',
                'value' =>  ''
            ),
            60  =>  array(
                'id'    =>  61,
                'key'   =>  'bottom_promo_enabled',
                'value' =>  0
            ),
            61  =>  array(
                'id'    =>  62,
                'key'   =>  'feedType',
                'value' =>  'OddService'
            ),
            62  =>  array(
                'id'    =>  63,
                'key'   =>  'commission',
                'value' =>  50
            )
        );

        parent::init();
    }
}