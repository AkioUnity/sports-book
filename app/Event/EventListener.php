<?php
 /**
 * Event Listener
 *
 * Holds Event Listener Methods
 *
 * @package    App.Events
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('CakeEventListener', 'Event');

class EventListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Model.Event.insertEvent'       =>  'onInsertEvent',
            'Feed.Results.insertResults'    =>  'onInsertResults'
        );
    }

    /**
     * Insert Event
     * Event fired after bet event insertion
     *
     *  * Updates ticket score
     *
     * @param CakeEvent $event
     */
    public function onInsertEvent(CakeEvent $event)
    {
        try{ }
        catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }

    public function onInsertResults(CakeEvent $event)
    {
        try{ }
        catch (Exception $e) {
            CakeLog::write('events', var_export($e->getMessage(), true));
        }
    }
}