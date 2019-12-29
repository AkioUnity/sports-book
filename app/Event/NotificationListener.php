<?php

App::uses('CakeEventListener', 'Event');

class NotificationListener  implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents() {
        return array(
            'Model.Notification.add'    =>  'onAdd'
        );
    }

    /**
     * Update user balance
     * Event fired after after new created withdraw
     *
     * @param CakeEvent $event CakeEvent Object
     *
     * @return void
     */
    public function onAdd(CakeEvent $event)
    {
        try {
            $user = $event->subject()->User->getItem($event->data["user_id"]);

            $user['User']['notifications'] = 1;

            $event->subject()->User->save($user, false);
        } catch (Exception $exception) {

        }
    }
}