<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> Frequently asked questions</h5>
        <div class="white-in">
            <div class="tgl tgl-first on-top">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor</div>
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <table class="table-list">
                    <tr>
                        <td><?php echo __('Date and time'); ?></td>
                    </tr>
                    <tr class="over-bg">
                        <td><?php echo $this->TimeZone->convertTime(time(), "Y-m-d H:i:s"); ?></td>

                    </tr>
                </table>
                <h4><?php echo __('Ticket Preview'); ?></h4>
                <table class="table-list">
                    <tr>
                        <th><?php echo __('Event ID / Date'); ?></th>
                        <th><?php echo __('Event'); ?></th>
                        <th><?php echo __('Pick'); ?></th>
                        <th><?php echo __('Odd'); ?></th>
                    </tr>

                    <?php foreach ($tickets as $i => $bets): ?>
                    <?php foreach ($bets['Bets'] as $ticketPart): ?>
                        <tr class="<?php echo $i %2 == 0 ? "over-bg" : ""; ?>">
                            <td><?php echo __("ID"); ?>: <?php echo $ticketPart['Event']['id']; ?>
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


                <div class="dark-table">
                    <div class="sum">
                        <span><?php echo __('Total:'); ?></span> <strong><?php echo $this->Beth->convertOdd($bets['Ticket']['odd']); ?></strong><br />
                        <span><?php echo __('Amount:'); ?></span> <strong><?php echo $bets['Ticket']['stake']; ?> <?php echo Configure::read('Settings.currency'); ?></strong><br />
                        <span><?php echo __('Return:'); ?></span> <strong><?php echo $this->Beth->convertCurrency($bets['Ticket']['winning']) . ' ' . Configure::read('Settings.currency'); ?></strong>
                    </div>
                    <br />
                    <?php echo $this->MyHtml->link(__('Back', true), '/', array('class' => 'blue-btn left')); ?>
                    <div class="clear"></div>
                </div>

                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>