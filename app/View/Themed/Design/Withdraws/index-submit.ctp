<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Withdraw submit"); ?></h5>
        <div class="white-in">

            <?php echo $this->element('flash_message'); ?>

            <div>
                <?php $options['inputDefaults'] = array(); ?>

                <?php echo $this->Form->create('Withdraw', array('url' => array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'withdraws', 'index-submit'))); ?>

                <div class="forma-box">
                    <p><?php echo __("Payment amount"); ?></p>
                    <?php  echo $this->Form->input('amount', array('label' => false, 'div' => false, "placeholder" => __('Amount', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                <div class="forma-box">
                    <p><?php echo __("Bank"); ?></p>
                    <?php  echo $this->Form->input('bank', array('label' => false, 'div' => false, "placeholder" => __('Bank', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                <div class="forma-box">
                    <p><?php echo __("Payment account holder"); ?></p>
                    <?php  echo $this->Form->input('account_holder', array('label' => false, 'div' => false, "placeholder" => __('Payment account holder', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                <div class="forma-box">
                    <p><?php echo __("Payment account number"); ?></p>
                    <?php  echo $this->Form->input('account', array('label' => false, 'div' => false, "placeholder" => __('Payment account number', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                <div class="forma-box">
                    <p><?php echo __("Card number"); ?></p>
                    <?php  echo $this->Form->input('card_no', array('label' => false, 'div' => false, "placeholder" => __('Card number', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                <div class="forma-box">
                    <p><?php echo __("Other notes"); ?></p>
                    <?php  echo $this->Form->input('notes', array('label' => false, 'div' => false, "placeholder" => __('Other notes', true), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
                    <div class="clear"></div>
                </div>

                <button type="submit" class="btn-silver"><?php echo $this->Form->submit(__('Request Manual Withdraw', true), array('class' => 'blue-submit btn-cente')); ?></button>
                <div class="clear"></div>

                <?php echo $this->MyForm->input('payment_provider', array('type' => 'hidden', 'value' => 'manual')); ?>

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