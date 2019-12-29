<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __('Reservation Ticket information'); ?><?php echo __('Ticket Ticket ID'); ?>: <span><?php echo $ticket['ReservationTicket']['id']; ?></h5>
        <div class="white-in">
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
            <table class="table-list">
                <tr>
                    <th><?php echo __('Sport'); ?></th>
                    <th><?php echo __('Event'); ?></th>
                    <th><?php echo __('Betting type'); ?></th>
                    <th><?php echo __('Pick'); ?></th>
                    <th><?php echo __('Odd'); ?></th>
                    <th><?php echo __('Result'); ?></th>
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
                        <td>
                            <?php if($ticketPart['Event']['result'] == ''): ?>
                                -
                            <?php else: ?>
                                <?php echo $ticketPart['Event']['result']; ?>
                            <?php endif; ?>
                        </td>
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
		<div class="buttonmove">
					<button type="button" class="btn-blue" onclick="goBack()"><?php echo __('Back'); ?></button>
				<script>
					function goBack() {
						window.history.back();
				}				
			</script>
			
		</div>
		</div>
    </div>
</div>