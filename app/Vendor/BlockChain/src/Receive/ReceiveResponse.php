<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Short File Description
 * 
 * PHP version 5
 * 
 * @category   aCategory
 * @package    aPackage
 * @subpackage aSubPackage
 * @author     anAuthor
 * @copyright  2014 a Copyright
 * @license    a License
 * @link       http://www.aLink.com
 */
namespace Blockchain\Receive;

/**
 * Short Class Description
 * 
 * PHP version 5
 * 
 * @category   aCategory
 * @package    aPackage
 * @subpackage aSubPackage
 * @author     anAuthor
 * @copyright  2014 a Copyright
 * @license    a License
 * @link       http://www.aLink.com
 */
class ReceiveResponse 
{
    /**
     * Properties
     */
    public $address;                    // string
    public $fee_percent;                // int
    public $destination;                // string
    public $callback_url;               // string

    /**
     * Methods
     */
    public function __construct($json) {
        if(array_key_exists('input_address', $json))
            $this->address = $json['input_address'];
        if(array_key_exists('fee_percent', $json))
            $this->fee_percent = $json['fee_percent'];
        if(array_key_exists('destination', $json))
            $this->destination = $json['destination'];
        if(array_key_exists('callback_url', $json))
            $this->callback_url = $json['callback_url'];
    }
}