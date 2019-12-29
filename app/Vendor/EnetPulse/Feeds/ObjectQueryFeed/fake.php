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

class EnetPulseXmlObjectQueryFeedParser_fake
{
    /**
     * Model Model
     *
     * @var $_Model
     */
    private $_Model;

    /**
     * Model schema
     *
     * @var array $_schema
     */
    private $_schema = array();

    /**
     * Object data attributes
     *
     * @var array $_objectDataAttributes
     */
    private $_objectDataAttributes      = array();

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

    }
    /**
     * Updates, inserts data
     *
     * @return void
     */
    public function processObject()
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