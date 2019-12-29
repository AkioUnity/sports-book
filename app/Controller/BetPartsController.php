<?php
/**
 * Handles BetParts
 *
 * Handles BetParts Actions
 *
 * @package    BetParts
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('AppController', 'Controller');

class BetPartsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'BetParts';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('BetPart', 'Event');

    /**
     *
     */
    public function admin_update()
    {
        switch($this->request->data["type"]) {
            case "suspend":
                $this->BetPart->setSuspended($this->request->data["BetPart"]["id"], $this->request->data["BetPart"]["value"]);
                break;
            case "odd":
                $this->BetPart->setOdd($this->request->data["BetPart"]["id"], $this->request->data["BetPart"]["value"]);
                break;
            case "suspend_event":
                $this->Event->updateState($this->request->data["Event"]["id"], $this->request->data["Event"]["value"] == "true" ? 0 : 1);
                break;
        }

        $this->set('_serialize', array());
    }
}