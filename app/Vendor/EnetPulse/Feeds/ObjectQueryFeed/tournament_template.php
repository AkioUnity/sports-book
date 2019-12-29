<?php
 /**
 * Short description for class
 *
 * Long description for class EnetPulse_ObjectParser_(if any)...
 *
 * @package    <package>
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('Country', 'Model');
App::uses('League', 'Model');

class EnetPulseXmlObjectQueryFeedParser_tournament_template
{
    /**
     * Object data attributes
     *
     * @var array $_objectDataAttributes
     */
    private $_objectDataAttributes      = array();

    private $_map                       =   array();

    /**
     * Initializes required classes, data
     *
     * @param array $objectDataAttributes
     */
    public function __construct(array $objectDataAttributes)
    {
        $this->_objectDataAttributes    =   $objectDataAttributes;
    }

    public function initObject($objectAttributesData)
    {
        $this->mapObjects($objectAttributesData);
    }
    /**
     * Updates, inserts data
     *
     * @return void
     */
    public function processObject()
    {
        $this->reduceObjects();

        $this->saveObjects();
    }

    /**
     *
     * @param array $items
     *
     * @return void
     *
     */
    public function mapObjects(array $items)
    {
        $this->_map = $items;
    }

    public function getMapObjects()
    {
        return $this->_map;
    }

    /**
     * @return void
     */
    public function reduceObjects()
    {

    }

    /**
     * Saves mapped and reduced data
     *
     * @return void
     */
    public function saveObjects()
    {

    }

    /**
     * Returns insert fields with data
     *
     * @param array $objectAttributesData
     *
     * @return array
     */
    public function getInsertFields($objectAttributesData)
    {
        $insertFields =   array();

        foreach ($this->_objectDataAttributes AS $attribute) {

        }

        return $insertFields;
    }

    /**
     * Returns update fields with data
     *
     * @param array $objectAttributesData
     *
     * @return array
     */
    public function getUpdateFields($objectAttributesData)
    {
        $updateFields =   array();

        foreach ($this->_objectDataAttributes AS $attribute) {

        }

        return $updateFields;
    }

    /**
     * Flush map & reduce results
     *
     * @return void
     */
    public function reset()
    {
        $this->_map = array();
    }
}