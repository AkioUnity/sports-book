<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Tickets"); ?></h5>
        <div class="white-in">
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <?php if (!empty($tickets)  AND is_array($tickets)): ?>
                    <table class="table-list">
                        <tr>
                            <th><?php echo $this->Paginator->sort('Ticket.date', __('Date')); ?></th>
                            <th><?php echo $this->Paginator->sort('Ticket.id', __('Ticket ID')); ?></th>
                            <th><?php echo $this->Paginator->sort('Ticket.odd', __('Odd')); ?></th>
                            <th><?php echo $this->Paginator->sort('Ticket.amount', __('Amount')); ?></th>
                            <th><?php echo $this->Paginator->sort('Ticket.service_fee', __('Service Fee')); ?></th>
                            <th><?php echo $this->Paginator->sort('Ticket.return', __('Winning')); ?></th>
                            <?php if(CakeSession::read('Auth.User.group_id') == Group::OPERATOR_GROUP): ?>
                                <th><?php echo $this->Paginator->sort('Ticket.paid', __('Paid')); ?></th>
                            <?php endif; ?>
                            <th style="width: 100px;"><?php echo $this->Paginator->sort('Ticket.status', __('Status')); ?></th>
                            <th colspan="2">&nbsp;</th>
                        </tr>
                        <?php if (!empty($tickets)  AND is_array($tickets)): ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <?php $User   = $ticket['User']; ?>
                                <?php $ticket = $ticket['Ticket']; ?>
                                <tr>
                                    <td><?php echo $this->TimeZone->convertDateTime($ticket['date']); ?></td>
                                    <td><?php echo $ticket['id'] ?></td>
                                    <td><?php echo $this->Beth->convertOdd($ticket['odd']); ?></td>
                                    <td><?php echo sprintf('%s %s', number_format((float)$ticket['amount'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                                    <td><?php echo sprintf("%d %s", $ticket['service_fee'], "%"); ?></td>
                                    <td><?php echo sprintf('%s %s', number_format((float)$ticket['return'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                                    <?php if(CakeSession::read('Auth.User.group_id') == Group::OPERATOR_GROUP): ?>
                                        <td><?php echo $this->Ticket->assignTicketPaid(null, array('Ticket' => $ticket, 'User' => $User)); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo $this->Ticket->getStatus($ticket['status']); ?></td>
                                    <td class="w100">
                                        <a href="<?php echo $this->MyHtml->url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'view', 'ticketId' => $ticket['id'])) ?>" class="silv-small">
                                            <?php echo __('View'); ?>
                                        </a>
                                    </td>
                                    <td class="w100">
                                        <?php if ((Configure::read('Settings.printing')) && ($ticket['printed'] == 0)): ?>
                                            <a href="<?php echo $this->MyHtml->url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'printTicket', $ticket['id']), array('target' => '_blank')) ?>" class="silv-small">
                                                <?php echo __('Print'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                <?php else: ?>
                    <div class="dark-table"><?php echo __("There are no history tickets"); ?></div>
                <?php endif; ?>

                <div class="dark-table">
                    <?php echo $this->element('paginator'); ?>
                    <br />
                    <a href="<?php echo $this->MyHtml->url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'tickets', 'action' => 'index')); ?>" class="silv-small" style="float: right; margin-right: 15px;">
                        <?php echo __('Pending tickets', true); ?>
                    </a>
                    <p><span><?php echo __('Note'); ?>:</span> <?php echo __('To place ticket please use betslip.'); ?></p>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
</div>