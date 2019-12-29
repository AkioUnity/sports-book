<!-- User Promotional Code START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Deposit information:"); ?></h5>
        <div class="white-in">

            <h1><?php echo __("Please preview deposit information before confirming."); ?></h1>
            <br />
            <?php echo $this->element('flash_message'); ?>
            <br />
            <?php if(!isset($success)): ?>
                <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <table class="table-list">
                    <tr>
                        <th><?php echo __('User'); ?></th>
                        <th><?php echo __('Deposit type'); ?></th>
                        <th><?php echo __('Amount'); ?></th>
                    </tr>
                    <tr>
                        <td><?php echo $this->Session->read('Auth.User.username'); ?></td>
                        <td><?php echo $type; ?></td>
                        <td><?php echo __('%s %s', number_format((float)$amount, intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                    </tr>
                </table>
            </div>
            <div style="text-align: center; padding: 5px;">
            <?php echo $this->MyForm->create('Deposit', array('url' => array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'result'))); ?>

                <?php echo $this->MyForm->input('phone_number', array('label' => false, 'div' => false, 'placeholder' => __('Phone number', true), 'type' => 'text', 'class' => 'form-input')); ?>
                <div class="clear"></div>

                <?php echo $this->MyForm->input('amount', array('type' => 'hidden', 'value' => $amount, 'class' => 'inp6')); ?>
                <div class="clear"></div>

                <?php echo $this->MyForm->input('bonus_code', array('type' => 'hidden', 'value' => $bonusCode, 'class' => 'inp6')); ?>
                <div class="clear"></div>

                <button type="submit" class="btn-blue"><?php echo __("Request Manual Deposit (Credited Upon Verification)");?></button>

            <?php echo $this->MyForm->end(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- User Promotional Code END -->