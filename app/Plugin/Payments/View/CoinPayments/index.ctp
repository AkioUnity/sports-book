<!-- Make Deposit START -->

<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __('Make deposit by %s payment provider', $this->name); ?></h5>
        <div class="white-in">

            <div>
                <img src="<?=$this->Html->url('/theme/Design/img/coinpayments.png');?>" alt="" class="payments" />
            </div>
            <br />
            <?php echo $this->element('flash_message'); ?>

            <?php echo $this->Form->create($this->name, array('url' => array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => $this->name, 'action' => 'submitPayment'))); ?>

                <span class="error-message"><?php echo $this->Session->flash(); ?></span>

                <?php echo $this->Form->input('amount', array('label' => __('Amount', true), 'placeholder' => __('Amount', true),  'type' => 'text',  'class' => 'form-inp')); ?>
                <div class="clear"><br /></div>

                <div>
                    <label for="data[CoinPayments][currency]"><?php echo __("Currency"); ?></label>
                    <?php echo $this->Form->input('currency', array('label' => false, 'div' => false, 'type' => 'select', 'options' => $currencies, 'class' => 'select')); ?>
                </div>
                <div class="clear"><br /></div>

                <?php echo $this->Form->input('bonus_code', array('label' => __('Bonus code or leave empty', true), 'placeholder' => __('Bonus code or leave empty', true),  'type' => 'text',  'class' => 'form-inp')); ?>
                <div class="clear"><br /></div>

                <?php echo $this->Form->submit(__('Deposit', true), array('class' => 'btn-silver')); ?>
                <div class="clear"><br /></div>
            </div>
        <?php echo $this->Form->end(); ?>

    </div>
</div>
<!-- Make Deposit END -->