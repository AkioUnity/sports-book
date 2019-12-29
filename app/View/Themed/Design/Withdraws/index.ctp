<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Withdraw"); ?></h5>
        <div class="white-in">

            <?php echo $this->element('flash_message'); ?>

            <div class="tgl-content table-cnt open-cell slenkama-lentele">
                <?php if (!empty($data)  AND is_array($data)): ?>
                <table class="table-list">
                    <tr>
                        <th><?php echo __('Date'); ?></th>
                        <th><?php echo __('Description'); ?></th>
                        <th><?php echo __('Withdraw ID'); ?></th>
                        <th><?php echo __('Withdraw Account'); ?></th>
                        <th><?php echo __('Status'); ?></th>
                        <th><?php echo __('Amount'); ?></th>
                    </tr>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo @$row['Withdraw']['date']; ?></td>
                            <td><?php echo @$row['Withdraw']['description']; ?></td>
                            <td><?php echo @$row['Withdraw']['id']; ?></td>
                            <td><?php echo @$row['Withdraw']['withdraw_account']; ?></td>
                            <td><?php echo @$row['Withdraw']['status']; ?></td>
                            <td><?php echo sprintf('%s %s', number_format((float)$row['Withdraw']['amount'], intval(Configure::read('Settings.balance_decimal_places')), '.', ''), Configure::read('Settings.currency'));?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php endif; ?>
            </div>

            <div>
                <?php $options['inputDefaults'] = array(); ?>

                <?php echo $this->Form->create('Withdraw', array('url' => array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'withdraws', 'submit'))); ?>
                <div class="forma-box">
                    <p><?php echo __("Payment provider"); ?></p>
					<?php  echo $this->Form->input('payment_provider', array('label' => false, 'div' => false, 'options' => $paymentProviders, 'type' => 'select', 'default' => '', 'class' => 'select')); ?>
                    <div class="clear"></div>
                </div>
                <div class="forma-box">
					<p><?php echo __("Payment account"); ?></p>
					<?php  echo $this->Form->input('account', array('label' => false, 'div' => false, "placeholder" => __('Withdraw account', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>
                <div class="forma-box1">
					<p><?php echo __("Payment amount"); ?></p>
                    <?php  echo $this->Form->input('amount', array('label' => false, 'div' => false, "placeholder" => __('Amount', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                 <button type="submit" class="btn-silver"><?php echo $this->Form->submit(__('Request Manual Withdraw', true), array('class' => 'blue-submit btn-cente')); ?></button>
                <div class="clear"></div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="clear"></div>

        </div>
    </div>
</div>


<script type="text/javascript">
    $('select[name="data[Withdraw][payment_provider]"]').change(function() {
        var form = $('form[id="WithdrawIndexForm"]');
        var provider = $(this).val();
        switch (provider) {
            case 'Manual' :
                form.attr("action", "/withdraws/index/submit");
                break;
            default :
                form.attr("action", "/payments/" + $(this).val() + "/submitWithdraw");
                break;
        }

        $('input[name^="data"]').each(function() {
            $(this).attr('name', 'data' + $(this).attr('id').match(/([A-Z]?[^A-Z]*)/g).slice(0,-1).map(function(namePart, index) {
                namePart = index == 0 ? provider == 'Manual' ? 'Withdraw' : provider : namePart.toLowerCase();
                // This will wrap each element of the dates array with quotes
                return "[" + namePart + "]";
            }).join(""));
        });
    });
</script>