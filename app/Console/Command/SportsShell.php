<?php
 /**
 * Sports Shell
 *
 * Handles Sports Shell Tasks
 *
 * @package    Sports
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */ 

class SportsShell extends Shell
{
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Sport');

    /**
     * Updates Leagues activity statuses
     *
     * @return void
     */
    public function updateSportsStatuses()
    {
        ini_set('memory_limit', '-1');

        $Sports = $this->Sport->find('all', array(
            'contain'   =>  array(
                'League' =>  array(
                    'conditions'    =>  array( 'League.active' => 1 ),
                    'limit'         =>  1
                )
            )
        ));

        foreach ($Sports AS $Sport) {
            $hasActiveLeagues = !empty($Sport['League']);

            if ((int) $hasActiveLeagues != (int) $Sport['Sport']['active']) {
                $this->Sport->create();
                $this->Sport->id = $Sport['Sport']['id'];
                $this->Sport->save( array( 'Sport' => array( 'active' => (int) $hasActiveLeagues  ) ) );

                $this->out('#' . $Sport['Sport']['id'] . ' - ' . $Sport['Sport']['name'] . ' : ' . (int) $Sport['Sport']['active'] . '-' . (int) $hasActiveLeagues  );
            }
        }
    }
}