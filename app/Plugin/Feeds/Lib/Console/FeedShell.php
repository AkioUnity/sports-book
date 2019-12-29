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

interface FeedShell
{
    /**
     * Method which implements countries importing / updating
     *
     * @return mixed
     */
    public function importCountries();

    /**
     * Method which implements sports importing / updating
     *
     * @return mixed
     */
    public function importSports();

    /**
     * Method which implements leagues importing / updating
     *
     * @return mixed
     */
    public function importLeagues();

    /**
     * Method which implements events importing / updating
     *
     * @return mixed
     */
    public function importEvents();
}