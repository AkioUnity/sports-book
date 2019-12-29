<?php
 /**
 * CountryMenuShell Shell
 *
 * Handles CountryMenuShell Shell Tasks
 *
 * @package    Country.Console.CountryMenuShell
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */ 

class CountriesShell extends Shell
{
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Country');

    /**
     * Updates Countries activity statuses
     */
    public function updateCountriesStatuses()
    {
        ini_set('memory_limit', '-1');

        $Countries = $this->Country->find('all', array(
            'contain'   =>  array(
                'League' =>  array(
                    'conditions'    =>  array( 'League.active' => 1 ),
                    'limit'         =>  1
                )
            )
        ));

        foreach($Countries AS $Country)
        {
            $hasActiveLeagues = !empty($Country['League']);

            if((int) $hasActiveLeagues != (int) $Country['Country']['active'])
            {
                $this->Country->create();
                $this->Country->id = $Country['Country']['id'];
                $this->Country->save( array( 'Country' => array( 'active' => (int) $hasActiveLeagues  ) ) );

                $this->out('#' . $Country['Country']['id'] . ' - ' . $Country['Country']['name'] . ' : ' . (int) $Country['Country']['active'] . '-' . (int) $hasActiveLeagues  );
            }
        }
    }
}