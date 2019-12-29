
<div class="blue-box">
    <div class="blue-in">
    <h5><i class="header-icon icon-faqw"></i> <?php echo __('Promotional Code'); ?></h5>
    <div class="white-in">
        <p><?php echo __('Please add promotional code below to get additional money or other benefit.'); ?></p>
        <br />
        <?php echo $this->element('flash_message'); ?>
    

    <div>

        <?php if(!isset($success)): ?>
        <?php echo $this->MyForm->create('User', array('url' => array('language' => Configure::read('Config.language'), 'plugin' => false))); ?>

        <div class="forma-box2">
			<p><?php echo __("Promotional Code"); ?></p>
			<?php  echo $this->MyForm->input('bonus_code', array('label' => false, 'div' => false, 'placeholder' =>  __('Enter promotion code'), 'type' => 'text', 'default' => '', 'class' => 'form-input')); ?>
            <div class="clear"></div>
        </div>
        <?php echo $this->Form->submit(__('Submit', true), array('class' => 'btn-silver')); ?>
        <div class="clear"></div>
        <?php echo $this->Form->end(); ?>

        <?php endif; ?>
		</div>
    </div>
	</div>
    <div class="clear"></div>
</div>
