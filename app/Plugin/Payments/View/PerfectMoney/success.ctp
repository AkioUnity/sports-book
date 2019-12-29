<!-- Make Deposit START -->
<div class="mid-cent">
    <h3><?php echo __('Make Deposit'); ?></h3>

    <div class="cent-txt txt-pad">

        <span class="success-message"><?php echo __("Your payment has been accepted."); ?></span>

        <div class="clear"></div>

        <?php echo $this->Html->link(__('Back'), array('plugin' => null, 'controller' => 'deposits'), array('class' => 'button')); ?>

        <div class="clear"></div>
    </div>
</div>
<!-- Make Deposit END -->