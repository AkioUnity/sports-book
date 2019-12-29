<?php
 /**
 * Leagues Shell
 *
 * Handles Leagues Shell Tasks
 *
 * @package    Leagues
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */ 

class LeaguesShell extends Shell
{
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('League');

    /**
     * Updates Leagues activity statuses
     *
     * @return void
     */
    public function updateLeaguesStatuses()
    {
        ini_set('memory_limit', '-1');

        $limit      =   50;
        $i          =   0;

        $conditions = array();

        $queryCount = $this->League->find('count', array(
            'conditions'    =>  $conditions
        ));

        $count  = (string) ceil($queryCount / $limit);

        $this->out(__("Total objects count: %d", $queryCount));
        $this->out(__("Total queries count: %d", $count));

        while($count > $i) {
            $this->out(__("i: %d; Offset: %d; Limit: %d", $i, $i * $limit, $limit));

            $Leagues        =   $this->League->find('all', array(
                'conditions'    =>  $conditions,
                'limit'         =>  $limit,
                'offset'        =>  $i * $limit
            ));

            foreach ($Leagues AS $League) {
                $hasEvents = $this->League->hasEvents($League['League']['id']);
                $this->out('#' . $League['League']['id'] . ' - ' . $League['League']['name'] . ' : ' . (int) $League['League']['active'] . '-' . (int) $hasEvents  );
                if ((int) $hasEvents != (int) $League['League']['active']) {
                    $this->League->create();
                    $this->League->id = $League['League']['id'];
                    $this->League->save( array( 'League' => array( 'active' => (int) $hasEvents  ) ) );
                }
            }
            $i++;
        }
    }
}