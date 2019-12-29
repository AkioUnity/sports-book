<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('View %s', $this->Admin->getSingularName()))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">

                                        <?php if (isset($ticket)): ?>
                                            <?php echo $this->element('flash_message'); ?>
                                            <h1>Ticket overview</h1><br>
                                            <table class="table table-custom" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th><?php echo __('Ticket Number'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('User'); ?></th>
                                                    <th><?php echo __('Type'); ?></th>
                                                    <th><?php echo __('Odd'); ?></th>
                                                    <th><?php echo __('Stake'); ?></th>
                                                    <th><?php echo __('Service Fee'); ?></th>
                                                    <th><?php echo __('Return'); ?></th>
                                                    <th><?php echo __('Status'); ?></th>
                                                    <th><?php echo __('Actions'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td><?php echo $ticket['Ticket']['id']; ?></td>
                                                    <td><?php echo $ticket['Ticket']['date']; ?></td>
                                                    <td><?php echo sprintf('<a href="/eng/admin/users/statistics/%d">%s</a>', $ticket['User']['id'], $ticket['User']['username']); ?></td>
                                                    <td><?php echo Ticket::assignTicketType($ticket['Ticket']['type']); ?></td>
                                                    <td><?php echo $this->Beth->convertOdd($ticket['Ticket']['odd']); ?></td>
                                                    <td><?php echo sprintf('%s %s', number_format((float)$ticket['Ticket']['amount'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                                                    <td><?php echo sprintf("%d %s", $ticket['Ticket']['service_fee'], "%"); ?></td>
                                                    <td><?php echo sprintf('%s %s', number_format((float)$ticket['Ticket']['return'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                                                    <td><?php echo $this->Ticket->getStatus($ticket['Ticket']['status']); ?></td>
                                                    <td>
                                                        <?php if(CakeSession::read('Auth.User.group_id') == Group::OPERATOR_GROUP): ?>
                                                            <?php if($ticket['Ticket']['status'] == Ticket::TICKET_STATUS_PENDING ): ?>
                                                                <?php echo $this->MyHtml->link(__('Print', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'admin' => '', 'controller' => 'tickets', 'action' => 'printTicket', $ticket['Ticket']['id']), array('target' => '_blank', 'class' => 'btn btn-mini')); ?>
                                                                <?php if ($this->Ticket->isCancellable($ticket['Ticket']['id'])): ?>
                                                                    <?php echo $this->MyHtml->link(__('Cancel', true), array('action' => 'admin_cancel', $ticket['Ticket']['id']), array('class' => 'btn btn-mini btn-primary'), __('Ticket will be cancelled. Are you sure you want to cancel?')); ?>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <?php echo __('No actions are available'); ?>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php echo $this->MyHtml->link(__('Print', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'admin' => '', 'controller' => 'tickets', 'action' => 'printTicket', $ticket['Ticket']['id']), array('target' => '_blank', 'class' => 'btn btn-mini')); ?>
                                                            <?php echo $this->MyHtml->link(__('Delete', true), array('action' => 'admin_delete', $ticket['Ticket']['id']), array('class' => 'btn btn-mini btn-danger'), __('Ticket will be deleted. Are you sure you want to delete?')); ?>
                                                            <?php if ($ticket['Ticket']['status'] != Ticket::TICKET_STATUS_CANCELLED): ?>
                                                                <?php echo $this->MyHtml->link(__('Cancel', true), array('action' => 'admin_cancel', $ticket['Ticket']['id']), array('class' => 'btn btn-mini btn-primary'), __('Ticket will be cancelled. Are you sure you want to cancel?')); ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
<!--                                                        --><?php //echo $this->MyHtml->link(__('Renew ticket events data', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'admin' => true, 'controller' => 'tickets', 'action' => 'admin_updateEvents', $ticket['Ticket']['id']), array('class' => 'btn btn-mini btn-warning'), __('All ticket event information will be updated. Are you sure you want to proceed?')); ?>
                                                    </td>
                                                </tr>
                                            </table>

                                            <br>
                                            <h2><?php echo __('Ticket details'); ?></h2>
                                            <table class="table table-custom" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th><?php echo __('Event ID'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('Event'); ?></th>
                                                    <th><?php echo __('Result'); ?></th>
                                                    <th><?php echo __('Pick'); ?></th>
                                                    <th><?php echo __('Odd'); ?></th>
                                                    <th><?php echo __('Status'); ?></th>
                                                    <th><?php echo __('Actions'); ?></th>
                                                </tr>
                                                <?php foreach ($ticket['TicketPart'] as $ticketPart): ?>
                                                    <tr>
                                                        <td><?php echo $ticketPart['Event']['id']; ?></td>
                                                        <td><?php echo $ticketPart['Event']['date']; ?></td>
                                                        <td>
                                                            <?php echo $this->MyHtml->link($ticketPart['Event']['name'], array('language' => $this->language->getLanguage(), 'plugin' => 'events', 'admin' => true, 'controller' => 'events', 'action' => 'admin_view', $ticketPart['Event']['id'])); ?>
                                                            ( <?php echo $ticketPart['Bet']['name']; ?> <?php echo $ticketPart['BetPart']['line'] . ' '; ?>)
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if (!empty($ticketPart['Event']['result'])) {
                                                                echo $ticketPart['Event']['result'];
                                                            }else{
                                                                if(CakeSession::read('Auth.User.group_id') == Group::OPERATOR_GROUP) {
                                                                    echo '---';
                                                                }else{
                                                                    echo $this->MyHtml->link(__('Insert Result', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'results', 'action' => 'event', $ticketPart['Event']['id']), array('class' => 'btn btn-mini btn-warning'));
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                                                        <td><?php echo $this->Beth->convertOdd($ticketPart['odd']); ?></td>
                                                        <td><?php echo $this->Ticket->getStatus($ticketPart['status']); ?></td>
                                                        <td>
                                                            <?php echo $this->MyHtml->link(__('View event', true), array('language' => $this->language->getLanguage(), 'plugin' => 'events', 'admin' => true, 'controller' => 'events', 'action' => 'admin_view', $ticketPart['Event']['id']), array('class' => 'btn btn-mini')); ?>
                                                            <?php echo $this->MyHtml->link(__('Enter result', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'admin' => true, 'controller' => 'results', 'action' => 'admin_event', $ticketPart['Event']['id'], 0), array('class' => 'btn btn-mini btn-inverse')); ?>
                                                            <?php echo $this->MyHtml->link(__('Cancel event', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'admin' => true, 'controller' => 'results', 'action' => 'admin_cancel', $ticketPart['Event']['id']), array('class' => 'btn btn-mini btn-danger', __('Event will be cancelled. Are you sure you want to cancel?'))); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>