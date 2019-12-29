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

App::import('Model','BetPart');

class Odd1X2Processor
{
    protected $OddID = null;

    protected $OddImportID = null;

    protected $OutcomeID = null;

    protected $Name = null;

    protected $Odd = null;

    protected $Line = null;

    protected $OrderID = null;

    public function setOddID($OddID)
    {
        $this->OddID = $OddID;

        return $this;
    }

    public function setOddImportID($OddImportID)
    {
        $this->OddImportID = $OddImportID;

        return $this;
    }

    public function setOutcomeID($OutcomeID)
    {
        $this->OutcomeID = $OutcomeID;

        return $this;
    }

    public function setName($Name)
    {
        $this->Name = $Name;

        return $this;
    }

    public function setOdd($Odd)
    {
        $this->Odd = $Odd;

        return $this;
    }

    public function setLine($Line)
    {
        $this->Line = $Line;

        return $this;
    }

    public function setOrderID($OrderID = null)
    {
        if (!is_null($OrderID)) {
            $this->OrderID = $OrderID;

            return $this;
        }

        $BetPart = new BetPart();

        $this->OrderID = $BetPart->getBetPartOrderId($this->Name, '1X2');

        return $this;
    }

    public function __toArray()
    {
        return array(
            'id'        =>  $this->OddID,
            'import_id' =>  $this->OddImportID,
            'bet_id'    =>  $this->OutcomeID,
            'name'      =>  $this->Name,
            'odd'       =>  $this->Odd,
            'line'      =>  $this->Line,
            'order_id'  =>  $this->OrderID
        );
    }
}