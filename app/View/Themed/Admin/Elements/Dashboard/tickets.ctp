<table class="table table-striped table-bordered table-advance table-hover">
    <thead>
    <tr>
        <th><i class="icon-briefcase"></i> ID</th>
        <th class="hidden-phone"><i class="icon-money"></i> Odd</th>
        <th class="hidden-phone"><i class="icon-money"></i> Amount</th>
        <th class="hidden-phone"><i class="icon-money"></i> Winning</th>
        <th><i class="icon-2x"></i> Events</th>
    </tr>
    </thead>
    <tbody>
    <?php if(isset($tickets)): ?>
        <?php foreach($tickets AS $ticket): ?>
            <tr>
                <td class="highlight">
                    <div class="success"></div>
                    <a href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'admin' => true,  'controller' => 'tickets', 'action' => 'view', $ticket['Ticket']['id'])); ?>" class="btn mini purple"><i class="icon-edit"></i> <?=$ticket["Ticket"]["id"];?></a>
                </td>
                <td class="hidden-phone"><?php echo __('%s %s', $this->Beth->convertOdd($ticket['Ticket']['odd']),  Configure::read('Settings.currency')); ?></td>
                <td class="hidden-phone"><?php echo __('%s %s', $this->Beth->calculatePercentageGiven($ticket['Ticket']['amount'], Configure::read('Settings.service_fee'), intval(Configure::read('Settings.balance_decimal_places'))), Configure::read('Settings.currency')); ?></td>
                <td class="hidden-phone"><?php echo $this->Beth->convertCurrency($ticket['Ticket']['return']) . ' ' .  Configure::read('Settings.currency'); ?></td>
                <td>
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th><?=__("Event");?></th>
                            <th><?=__("Odd");?></th>
                            <th><?=__("Result");?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ticket['TicketPart'] as $i => $ticketPart): ?>
                            <tr>
                                <td><a href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'admin' => true,  'controller' => 'events', 'action' => 'view', $ticketPart['Event']['id'])); ?>" class="btn mini purple"><i class="icon-edit"></i> <?php echo $ticketPart['Event']['name']; ?></a></td>
                                <td><?php echo $this->Beth->convertOdd($ticketPart['odd']);?></td>
                                <td>
                                    <?php if($ticketPart['Event']['result'] == ''): ?>
                                        <a href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'admin' => true,  'controller' => 'results', 'action' => 'event', $ticketPart['Event']['id'], 0)); ?>" class="btn mini purple"><i class="icon-edit"></i> -</a>
                                    <?php else: ?>
                                        <?php if($ticketPart['status'] != 0): ?>
                                            <?php echo $ticketPart['Event']['result']; ?>
                                        <?php else: ?>
                                            <a href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => false, 'admin' => true,  'controller' => 'results', 'action' => 'event', $ticketPart['Event']['id'], 0)); ?>" class="btn mini purple"><i class="icon-edit"></i> -</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>