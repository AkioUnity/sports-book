<!-- Make Deposit START -->
<div class="mid-cent">
    <h3><?php echo __('Make Deposit'); ?></h3>

    <div class="cent-txt txt-pad">

        <span class="error-message"><?php echo $this->Session->flash(); ?></span>

        <?php echo $this->Form->create($this->name, array('url' => array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => $this->name, 'action' => 'submitPayment'))); ?>

        <?php echo $this->Form->input('amount', array('label' => __('Amount', true), 'placeholder' => __('Amount', true), 'value' => Configure::read('Settings.minDeposit'),  'type' => 'text',  'class' => 'inp6')); ?>
        <div class="clear"></div>

        <?php echo $this->Form->input('bonus_code', array('label' => __('Bonus code or leave empty', true), 'placeholder' => __('Bonus code or leave empty', true), 'class' => 'inp6')); ?>
        <div class="clear"></div>

        <?php echo $this->Form->submit(__('Deposit', true), array('class' => 'btn-form')); ?>
        <div class="clear"></div>

        <?php echo $this->Form->end(); ?>
        <div class="clear"></div>
    </div>
</div>
<!-- Make Deposit END -->