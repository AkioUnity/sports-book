<!-- Make Deposit START -->

<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __('Make deposit by %s payment provider', $this->name); ?></h5>
        <div class="white-in">

            <div>
                <img src="<?=$this->Html->url('/theme/Design/img/NovinPal.png');?>" alt="" class="payments" />
            </div>
            <br />
            <?php echo $this->element('flash_message'); ?>

            <div class="cent-txt txt-pad">

                <span class="error-message"><?php echo $this->Session->flash(); ?></span>

                <?php echo $this->Form->create($this->name, array('url' => array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => $this->name, 'action' => 'submitPayment'))); ?>

                <?php echo $this->Form->input('amount', array('label' => __('Amount', true), 'placeholder' => __('Amount', true), 'value' => Configure::read('Settings.minDeposit'),  'type' => 'text',  'class' => 'form-input')); ?>
                <div class="clear"></div>

                <?php echo $this->Form->input('bonus_code', array('label' => __('Bonus code or leave empty', true), 'placeholder' => __('Bonus code or leave empty', true), 'class' => 'form-input')); ?>
                <div class="clear"></div>

                <?php echo $this->Form->submit(__('Deposit', true), array('class' => 'btn-silver')); ?>
                <div class="clear"></div>

                <?php echo $this->Form->end(); ?>
                <div class="clear"></div>
            </div>
        </div>

    </div>
</div>
<!-- Make Deposit END -->