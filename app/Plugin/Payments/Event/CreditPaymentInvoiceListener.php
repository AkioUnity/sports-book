<?php

App::uses('CakeEventListener', 'Event');

class CreditPaymentInvoiceListener implements CakeEventListener
{
    /**
     * Returns a list of events this object is implementing.
     * When the class is registered each individual method will be associated with the respective event.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return array(
            'Model.CreditPaymentInvoice.add'    =>  'onAdd',
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
           // @TODO: email
           $notification = $event->subject()->User->Notification;
           $notification->add($event->data["user_id"], sprintf("Invoice ID %d was sent to your email address.", $event->data["id"]));
        }  catch (Exception $e) {

        }
    }
}