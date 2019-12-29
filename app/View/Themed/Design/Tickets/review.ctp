<div class="mbox">
    <h4><?php echo __('Ticket placed'); ?></h4>
    <div class="dark-table">
        <p><strong><span><?php echo $this->element('flash_message'); ?></span></strong></p>
    </div>
    <?php if (isset($tickets)): ?>
        <?php foreach ($tickets as $ticket): ?>
            <div class="table">
                <table class="table-in1">
                    <tr>
                        <td><?php echo __('Date and time'); ?></td>
                        <td><?php echo __('Ticket id'); ?></td>
                    </tr>
                    <tr class="over-bg">
                        <td><?php echo $this->TimeZone->convertDate($ticket['Ticket']['date'], 'Y-m-d H:i'); ?></td>
                        <td><?php printf('%1$07d', $ticket['Ticket']['id']); ?></td>
                    </tr>
                </table>
            </div>
            <h5 id="tap1" class="taps"><?php __('Ticket'); ?></h5>
            <div class="table">
                <table class="table-in3">
                    <tr>
                        <th><?php echo __('Event ID / Date'); ?></th>
                        <th><?php echo __('Event'); ?></th>
                        <th><?php echo __('Pick'); ?></th>
                        <th><?php echo __('Odd'); ?></th>
                    </tr>
                    <?php foreach ($ticket['TicketPart'] as $i => $ticketPart): ?>
                    <tr <tr class="<?php echo $i %2 == 0 ? "over-bg" : ""; ?>">
                        <td>
                            <?php echo __('ID:'); ?> <?php echo $ticketPart['Event']['id']; ?>
                            <br />
                            <?php echo $this->TimeZone->convertDate($ticketPart['Event']['date'], 'Y-m-d H:i'); ?>
                        </td>
                        <td>
                            <?php if($ticketPart['Event']['feed_type'] == 'OddService'): ?>
                                <?php echo $ticketPart['Event']['name']; ?>
                                <br />
                            <?php endif; ?>
                            <?php echo $ticketPart['Bet']['name']; ?>
                            <br />
                            <?php echo $ticketPart['BetPart']['line']; ?>
                        </td>
                        <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                        <td><?php echo $ticketPart['BetPart']['odd']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="dark-table">
                <div class="sum">
                    <span><?php echo __('Total'); ?>:</span> <strong><?php echo $this->Beth->convertOdd($ticket['Ticket']['odd']); ?></strong><br />
                    <span><?php echo __('Amount'); ?>:</span> <strong><?php echo sprintf('%s %s', number_format((float)$ticket['Ticket']['amount'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency')); ?></strong><br />
                    <span><?php echo __('Return:'); ?></span> <strong><?php echo $this->Beth->convertCurrency($ticket['Ticket']['return']) . ' ' . Configure::read('Settings.currency'); ?></strong>
                </div>
                <p><?php echo __('Your ticket is created successfully'); ?></p>
                <p><?php echo __('Your ticket number is'); ?> <?php echo $ticket['Ticket']['id']; ?></p>
                <br />
                <?php echo $this->MyHtml->link(__('Back'), array('plugin' => false, 'action' => 'index'), array('class' => 'blue-btn left')); ?>
                <?php if (Configure::read('Settings.printing')): ?>
                    <?php echo $this->MyHtml->link(__('Print', true), array('plugin' => false, 'action' => 'printTicket', $ticket['Ticket']['id'] . '.pdf'), array('class' => 'blue-btn left', 'target' => '_blank', 'style' => 'margin-left: 5px;')); ?>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>