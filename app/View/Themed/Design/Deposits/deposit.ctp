<!-- Make Deposit START -->
<div class="mid-cent">
    <h3><?php echo __('Make Deposit'); ?></h3>

    <div class="cent-txt txt-pad">

        <?php echo $this->element('flash_message'); ?>

        <?php echo $this->MyForm->create('Deposit', array('url' => array('plugin' => false, 'action' => 'preview'))); ?>

            <?php echo $this->MyForm->input('amount', array('label' => __('Amount', true), 'placeholder' => __('Amount', true), 'value' => Configure::read('Settings.minDeposit'),  'type' => 'text',  'class' => 'inp6')); ?>
            <div class="clear"></div>

            <?php echo $this->MyForm->input('bonus_code', array('label' => __('Bonus code or leave empty', true), 'placeholder' => __('Bonus code or leave empty', true), 'class' => 'inp6')); ?>
            <div class="clear"></div>

            <?php echo $this->MyForm->submit(__('Deposit', true), array('class' => 'btn-form')); ?>
            <div class="clear"></div>

        <?php echo $this->MyForm->end(); ?>
        <div class="clear"></div>
    </div>
</div>
<!-- Make Deposit END -->