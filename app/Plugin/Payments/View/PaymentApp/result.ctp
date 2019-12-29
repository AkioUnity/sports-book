<!-- Make Deposit START -->
<div class="mid-cent">
    <h3><?php echo __('Make Deposit'); ?></h3>

    <div class="cent-txt txt-pad">
        <div class="error-message"><?php echo $this->Session->flash(); ?></div>

        <?php echo $this->MyHtml->link(__('Back'), array('action' => 'index'), array('class' => 'button')); ?>

        <div class="clear"></div>
    </div>
</div>
<!-- Make Deposit END -->