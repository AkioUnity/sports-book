<?php
/**
 * Feed Model
 *
 * Handles Feed Data Source Actions
 *
 * @package    Feeds.Model
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('FeedsAppModel', 'Feeds.Model');

class EnetPulse extends FeedsAppModel
{
    /**
     * Model name
     *
     * @var string
     */
    public $name = 'EnetPulse';

    /**
     * Table name for this Model.
     *
     * @var string
     */
    public $table = 'enet_pulse';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var string
     */
    public $useTable = 'enet_pulse';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'    => array(
            'type'      => 'bigint',
            'length'    => 22,
            'null'      => false
        ),
        'created' => array(
            'type'      => 'int',
            'length'    => 9,
            'null'      => false
        ),
        'updated' => array(
            'type'      => 'int',
            'length'    => 9,
            'null'      => false
        ),
        'state' => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
    );

    /**
     * List of validation rules. It must be an array with the field name as key and using
     * as value one of the following possibilities
     *
     * @var array
     */
    public $validate = array(
        'xml' => array(
            'upload-file' => array(
                'required'  =>  true,
                'rule' => array('receiveFile'), // Is a function below
                'message' => 'Error uploading file'
            )
        )
    );

    /**
     * Returns is feed parser job active
     *
     * @param string $parseXmlCommand - job command
     *
     * @return bool
     */
    public function parseFeedTaskInProgress($parseXmlCommand)
    {
        // Ant daugiau core'ų galima naudoti Queue::inProgress(), - pagal load'ą
        foreach (Queue::inStatus() AS $QueueInProgress) {
            if (!empty($QueueInProgress['QueueTask']) && $QueueInProgress['QueueTask']['command'] == $parseXmlCommand && $QueueInProgress['QueueTask']['status'] != 3) {
                return true;
            }
        }
        return false;
    }

    /**
     * Used when validating a file upload in CakePHP
     *
     * @param array $file - Passed from $validate to this function containing our filename
     *
     * @return bool - True or False is passed or failed validation
     * @throws Exception
     */
    public function receiveFile($file)
    {
        if (!isset($file['xml']['tmp_name'])) {
            throw new Exception(__("%s feed XML unknown tmp_name.", $this->name), 500);
        }

        if (!is_uploaded_file($file['xml']['tmp_name'])) {
            throw new Exception(__("%s feed xml import possible file upload attack.", $this->name), 500);
        }

       return true;
    }

    /**
     * Returns xml files working directory path
     *
     * @return string
     */
    public function getXmlDirectory()
    {
        return APP . 'tmp' . DS . 'xml' . DS . $this->name . DS;
    }


    /**
     * Updates xml state
     *
     * @param int $id    - xml id
     * @param int $state - xml state
     *
     * @return void
     */
    public function setState($id, $state)
    {
        $this->clear();

        $this->id = $id;

        $this->save(array('state' => $state, 'updated' => time()), false);
    }

    /**
     * Saves xml file from provider push service
     *
     * @return void
     *
     * @throws Exception
     */
    public function saveFile()
    {
        $xml        =   $this->save(array('created' =>  time(), 'updated' => time(), 'state' => 0));
        $directory  =   $this->getXmlDirectory();

        if (!$xml) {
            throw new Exception(__("%s feed cannot save xml entry to database", $this->name), 500);
        }

        if (!file_exists($directory)) {
            mkdir( $directory, 0777, true );
        }

        $this->id = $xml[$this->name]["id"];

        try {

            $gzipFile   =   $directory . $xml[$this->name]["id"] . ".xml.gz";
            $xmlFile    =   $directory . $xml[$this->name]["id"] . ".xml";

            if (!move_uploaded_file($xml[$this->name]['xml']['tmp_name'], $gzipFile) || !is_readable($gzipFile)) {
                throw new Exception(__("%s feed cannot move tmp xml file", $this->name), 500);
            }

            // Open our files (in binary mode)
            $file       =   gzopen($gzipFile, 'rb');
            $out_file   =   fopen($xmlFile, 'wb');

            // Keep repeating until the end of the input file
            while (!gzeof($file)) {
                // Read buffer-size bytes
                // Both fwrite and gzread and binary-safe
                // read 4kb at a time
                fwrite($out_file, gzread($file,  4096));
            }

            // Files are done, close files
            fclose($out_file);
            gzclose($file);

            // Unlink gzip
            unlink($gzipFile);

            $this->setState($xml[$this->name]["id"], 1);
        }
        catch (Exception $e) {

            $this->setState($xml[$this->name]["id"], 3);

            throw new Exception($e->getMessage(), 500);
        }
    }
}