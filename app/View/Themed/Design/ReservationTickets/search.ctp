<div class="blue-box">
    <div class="blue-in">
        <?php if (!empty($ticket)): ?>
        <h5>
            <i class="header-icon icon-faqw"></i> <?php echo __('Reservation Ticket information'); ?>
            <?php echo __('Ticket Ticket ID'); ?>: <span><?php echo $ticket['ReservationTicket']['id']; ?>
            <?php echo $ticket["ReservationTicket"]['placed'] == 0 ? __("Ticket not placed yet") : __("Ticket placed");?>
        </h5>
        <div class="white-in">
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <table class="table-list">
                    <tr>
                        <th><?php echo __('Sport'); ?></th>
                        <th><?php echo __('Event'); ?></th>
                        <th><?php echo __('Betting type'); ?></th>
                        <th><?php echo __('Pick'); ?></th>
                        <th><?php echo __('Odd'); ?></th>
                    </tr>

                    <?php foreach ($ticket['ReservationTicketPart'] as $i => $ticketPart): ?>
                        <tr <?php if( $i % 2 == 0): ?>class="over-bg"<?php endif; ?>>
                            <td><?php echo $ticketPart['Sport']['name']; ?></td>
                            <td>
                                <?php if(Configure::read('Settings.feedType') != 'OddService'): ?>
                                    <?php echo $ticketPart['Bet']['name']; ?>
                                <?php else: ?>
                                    <?php echo $ticketPart['Event']['name']; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo $ticketPart['Bet']['name']; ?>
                                <?php echo $ticketPart['BetPart']['line']; ?>
                            </td>
                            <td><?php echo $ticketPart['BetPart']['name']; ?></td>
                            <td><?php echo $this->Beth->convertOdd($ticketPart['odd']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="dark-table">

                    <div class="sum">
                        <span><?php echo __("Total");?>:</span>
                        <strong><?php echo __('%s %s', $this->Beth->convertOdd($ticket['ReservationTicket']['odd']),  Configure::read('Settings.currency')); ?></strong>
                        <br />
                        <span><?php echo __("Amount");?>:</span>
                        <strong><?php echo __('%s %s', $this->Beth->calculatePercentageGiven($ticket['ReservationTicket']['amount'], Configure::read('Settings.service_fee'), intval(Configure::read('Settings.balance_decimal_places'))), Configure::read('Settings.currency')); ?></strong>
                        <br />
                        <span><?php echo __("Return");?>:</span>
                        <strong><?php echo $this->Beth->convertCurrency($ticket['ReservationTicket']['return']) . ' ' .  Configure::read('Settings.currency'); ?></strong>
                    </div>


                    <div class="clear"></div>
                </div>

            </div>
            <div style="margin-left: 0;" class="buttonmove">
                <?php if(!$ticket["ReservationTicket"]["placed"]):?>
                <button type="button" class="btn-blue" onclick="goPlaceTicket()"><?php echo __('Place ticket'); ?></button>
                <?php else: ?>
                <button type="button" disabled="disabled" class="btn-blue"><?php echo __('Ticket already placed'); ?></button>
                <?php endif; ?>
                <button type="button" style="float: right;" class="btn-blue" onclick="goBack()"><?php echo __('Back'); ?></button>
                <script>
                    function goBack() {
                        window.history.back();
                    }
                    function goPlaceTicket() {
                        window.location.href = window.location.pathname + '?id=<?=$ticket['ReservationTicket']['id'];?>&placeTicket=1';
                    }
                </script>

            </div>
        </div>
        <?php else: ?>
        <h5><i class="header-icon icon-faqw"></i> <?php echo __('Reservation Ticket information'); ?><?php echo __(' Not Found'); ?></h5>
        <div class="white-in">
            <?php echo __("Ticket not found"); ?>
        </div>
        <?php endif;?>
    </div>
</div>