<!-- Make Deposit Result START -->
<div class="mid-cent">
    <h3><?php echo __('Make Deposit'); ?></h3>
    <div class="cent-txt txt-pad" style="padding-top: 0;">
        <span class="error-message" >
            <?php echo $this->element('flash_message'); ?>
        </span>
        <?php echo $this->MyHtml->link(__('Back'), array('plugin' => false, 'action' => 'index'), array('class' => 'button')); ?>
    </div>
</div>
<!-- Make Deposit Result END -->