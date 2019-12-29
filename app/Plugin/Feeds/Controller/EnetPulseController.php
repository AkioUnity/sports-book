<?php

App::uses('CakeEvent', 'Event');
App::uses('Queue', 'Queue.Lib');
App::uses('FeedController', 'Feeds.Controller');
App::import('Plugin/WebSocket/Lib/Network/Http', 'WebSocket', array('file'=>'WebSocket.php'));
App::import('Vendor', 'EnetPulse', array('file' => 'EnetPulse/EnetPulseXml.php'));


class EnetPulseController extends FeedsAppController implements FeedController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'EnetPulse';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'Feeds.EnetPulse',
        1   =>  'Country',
        2   =>  'Sport',
        3   =>  'League',
        4   =>  'Event',
        5   =>  'Bet',
        6   =>  'BetPart',
        7   =>  'Setting',
        8   =>  'Currency',
        9   =>  'Ticket'
    );

    /**
     * Receive file
     *
     * @return void
     */
    public function receive_file()
    {
       try {
            $this->request->data['EnetPulse'] = $_FILES;

            $this->EnetPulse->set($this->request->data);

            if (!$this->EnetPulse->validates()) {
                throw new Exception(__("%s XML file does not meets validation rules. Import Aborting.", $this->name), 500);
            }

            try {

                $this->EnetPulse->saveFile();

                CakeLog::notice('Xml file successful received and ready to process.', $this->name);

                $parseXmlCommand = 'Feeds.EnetPulse parseXml';

                if (!$this->EnetPulse->parseFeedTaskInProgress($parseXmlCommand)) {
                    Queue::add($parseXmlCommand, 'shell', array('priority' => 1));
                }

                echo 'XML_RECEIVED_OK';

            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 500);
            }
        }
        catch (Exception $e) {

            CakeLog::critical($e->getMessage(), $this->name);

            echo 'XML_RECEIVED_FAILED';
        }
        exit;
    }
}