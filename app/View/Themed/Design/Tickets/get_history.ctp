<?php // TODO?? ?>
<h3>
    <?php echo __('Ticket History'); ?>
    <span class="clear"></span>
</h3>
<div class="side-inside pad5">
    <?php if(isset($lastTickets) AND is_array($lastTickets) AND !empty($lastTickets)): ?>
        <?php foreach($lastTickets AS $lastTicket): ?>
            <div class="ticket">
                <div class="title">
                        <span class="date">
                            <?php echo __('Date'); ?>
                            <?php echo $lastTicket['Ticket']['date']; ?>
                        </span>
                </div>
                <div class="container">
                    <span><?php echo __('Ticket ID:'); ?></span>
                    <span><?php echo $lastTicket['Ticket']['id']; ?></span>

                    <?php if(!$lastTicket['Ticket']['printed']): ?>
                        <?php echo $this->MyHtml->link(__('Print', true), array('plugin' => null, 'controller' => 'tickets', 'action' => 'printTicket', $lastTicket['Ticket']['id'] . '.pdf'), array('target' => '_blank')); ?>
                    <?php endif; ?>

                    <?php if($lastTicket['Ticket']['isCancellable'] == true): ?>

                        <?php if($lastTicket['Ticket']['status'] != Ticket::TICKET_STATUS_CANCELLED): ?>
                            <?php echo $this->MyHtml->link(__('Cancel', true), array('plugin' => null, 'admin' => true, 'controller' => 'tickets', 'action' => 'admin_cancel', $lastTicket['Ticket']['id']), array(), __('Are you sure about to cancel this ticket?')); ?>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if($lastTicket['Ticket']['status'] == Ticket::TICKET_STATUS_CANCELLED): ?>
                        <span class="canceled"><?php echo __('Cancelled'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div style="clear"></div>
    <?php else: ?>
        <div class="no-results">
            <?php echo __('No tickets activity available'); ?>
        </div>
    <?php endif; ?>
</div>