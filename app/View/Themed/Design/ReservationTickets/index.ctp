<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Reservation Tickets"); ?></h5>
        <div class="white-in">
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                    <?php if (!empty($tickets)  AND is_array($tickets)): ?>
                    <table class="table-list">
                    <tr>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.id', __('Reservation Ticket ID')); ?></th>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.date', __('Date')); ?></th>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.odd', __('Odd')); ?></th>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.amount', __('Amount')); ?></th>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.service_fee', __('Service Fee')); ?></th>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.return', __('Winning')); ?></th>
                        <th><?php echo $this->Paginator->sort('ReservationTicket.placed', __('Ticket placed')); ?></th>
                        <th colspan="2">&nbsp;</th>
                    </tr>
                    <?php if (!empty($tickets)  AND is_array($tickets)): ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <?php $ticket = $ticket['ReservationTicket']; ?>
                        <tr>
                            <td><center><?php echo $ticket['id'] ?></center></td>
                            <td><center><?php echo $this->TimeZone->convertDateTime($ticket['date']); ?></center></td>
                            <td><center><?php echo $this->Beth->convertOdd($ticket['odd']); ?></center></td>
                            <td><center><?php echo sprintf('%s %s', number_format((float)$ticket['amount'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></center></td>
                            <td><center><?php echo sprintf("%d %s", $ticket['service_fee'], "%"); ?></center></td>
                            <td><center><?php echo sprintf('%s %s', number_format((float)$ticket['return'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></center></td>
                            <td><center><?php echo $ticket['placed'] == 0 ? __("No") : __("Yes");?></center></td>
                            <td class="w100">
                                <a href="<?php echo $this->MyHtml->url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'view', 'ticketId' => $ticket['id'])) ?>" class="blue-small">
                                    <?php echo __('View'); ?>
                                </a>
                                <?php if($this->MyHtml->checkAcl(array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'printTicket', $ticket['id']))): ?>
                                <a href="<?php echo $this->MyHtml->url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'printTicket', $ticket['id'])) ?>" class="blue-small">
                                    <?php echo __('Print'); ?>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>
                    <?php else: ?>
                        <div class="dark-table"><?php echo __("There are no pending tickets"); ?></div>
                    <?php endif; ?>

                    <div class="dark-table">
                        <?php echo $this->element('paginator'); ?>
                        <br />
                        <p><span><?php echo __('Note'); ?>:</span> <?php echo __('To place ticket please use betslip.'); ?></p>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
            </div>

        </div>
    </div>
</div>