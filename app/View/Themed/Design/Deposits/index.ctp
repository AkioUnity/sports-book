<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Deposit"); ?></h5>
        <div class="white-in">
            <?php echo $this->element('flash_message'); ?>
            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <table class="table-list">
                    <tr>
                        <th><?php echo $this->Paginator->sort('Deposit.date', __('Date')); ?></th>
                        <th><?php echo $this->Paginator->sort('Deposit.description', __('Description')); ?></th>
                        <th><?php echo $this->Paginator->sort('Deposit.amount', __('Amount')); ?></th>
                        <th><?php echo $this->Paginator->sort('Deposit.status', __('Status')); ?></th>
                    </tr>
                    <?php if (!empty($data)  AND is_array($data)): ?>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?php echo $row['Deposit']['date']; ?></td>
                                <td><?php echo $row['Deposit']['description']; ?></td>
                                <td><?php echo __('%s %s', number_format((float)$row['Deposit']['amount'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                                <td><?php echo $row['Deposit']['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
                <div>
                    <?php echo $this->element('paginator'); ?>
                </div>
            </div>

            <?php if(Configure::read('Settings.deposits') != 0 || Configure::read('Settings.D_Manual') != 0): ?>
            <?php echo $this->MyForm->create('Deposit', array('url' => array('plugin' => false, 'action' => 'preview'))); ?>

            <div class="forma-box">
			
						<table class="paymenttable">
                            <?php if(Configure::read('Settings.deposits_bitcoin') != 0): ?>
                            <tr>
                                <td><img src="<?=$this->Html->url('/theme/Design/img/bitcoin.png');?>" alt="" class="payments" /></td>
                                <td><input id="BlockChain" name="imp1" value="BlockChain" type="radio" /> <label for="BlockChain"><span></span><?php echo  __('Bitcoin Payment'); ?></label></td>
                            </tr>
                            <?php endif; ?>

                            <?php if(Configure::read('Settings.deposits') != 0 && 1 == 0): ?>
                            <tr>
                                <td><img src="<?=$this->Html->url('/theme/Design/img/lunacoin.png');?>" alt="" class="payments" /></td>
                                <td><input id="LunaWallet" name="imp1" value="LunaWallet" type="radio" /> <label for="LunaWallet"><span></span><?php echo  __('Luna Wallet Payment'); ?></label></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(Configure::read('Settings.D_Manual') != 0): ?>
                            <tr>
                                <td><img src="<?=$this->Html->url('/theme/Design/img/cash.png');?>" alt="" class="payments" /></td>
                                <td><input id="0" name="imp1" value="0" type="radio" checked="checked" /> <label for="0"><span></span><?php echo  __('Cash'); ?></label></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(Configure::read('Settings.deposits_novinpal') != 0): ?>
                            <tr>
                                <td><img src="<?=$this->Html->url('/theme/Design/img/NovinPal.png');?>" alt="" class="payments" /></td>
                                <td><input id="Novinpal" name="imp1" value="Novinpal" type="radio" /> <label for="Novinpal"><span></span><?php echo  __('Dargah Bank درگاه بانکی‌'); ?></label></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(Configure::read('Settings.deposits_paypal') != 0): ?>
                            <tr>
                                <td><img src="<?=$this->Html->url('/theme/Design/img/paypal.png');?>" alt="" class="payments" /></td>
                                <td><input id="Paypal" name="imp1" value="Paypal" type="radio" /> <label for="Paypal"><span></span><?php echo  __('PayPal Payment'); ?></label></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(Configure::read('Settings.deposits_skrill') != 0): ?>
                            <tr>
                                <td><img src="<?=$this->Html->url('/theme/Design/img/skrill.png');?>" alt="" class="payments" /></td>
                                <td><input id="Skrill" name="imp1" value="Skrill" type="radio" /> <label for="Skrill"><span></span><?php echo  __('Skrill Payment'); ?></label></td>
                            </tr>
                            <?php endif; ?>
                            <?php if(Configure::read('Settings.deposits_coinpayments') != 0): ?>
                                <tr>
                                    <td><img src="<?=$this->Html->url('/theme/Design/img/coinpayments.png');?>" alt="" class="payments" /></td>
                                    <td><input id="CoinPayments" name="imp1" value="CoinPayments" type="radio" /> <label for="CoinPayments"><span></span><?php echo  __('Coin Payments'); ?></label></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(Configure::read('Settings.deposits_wecashup') != 0): ?>
                                <tr>
                                    <td><img src="<?=$this->Html->url('/theme/Design/img/wecashup.png');?>" alt="" class="payments" /></td>
                                    <td><input id="WeCashUp" name="imp1" value="WeCashUp" type="radio" /> <label for="WeCashUp"><span></span><?php echo  __('WeCashUp'); ?></label></td>
                                </tr>
                            <?php endif; ?>
						</table>
                <div class="clear"></div>
            </div>

            <div class="forma-box">
                
                <p><?php echo __('Amount', true); ?></p>
				<?php echo $this->MyForm->input('amount', array('label' => false, 'div' => false, 'placeholder' => __('Amount', true), 'value' => Configure::read('Settings.minDeposit'),  'type' => 'text',  'class' => 'form-input')); ?>
                <div class="clear"></div>
            </div>
            <div class="forma-box">

                <p><?php echo __('Bonus code', true); ?></p>
                <?php echo $this->MyForm->input('bonus_code', array('label' => false, 'div' => false, 'placeholder' => __('Bonus code or leave empty', true), 'class' => 'form-input')); ?>
                <div class="clear"></div>
            </div>
            <button type="submit" class="btn-silver"><?php echo __("Make Deposit");?></button>
            <div class="clear"></div>
            <?php echo $this->MyForm->end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>