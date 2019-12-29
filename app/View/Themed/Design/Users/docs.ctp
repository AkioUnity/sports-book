<!-- User Docs Upload START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Documentation"); ?></h5>
        <div class="white-in">
            <br />
            <div><?php echo  __("Account status: "); ?><?php if(CakeSession::read('Auth.User.verified')): ?><?php echo  __("Verified"); ?><?php else: ?><?php echo  __("Not Verified"); ?><?php endif;?></div>
            <br />
            <?php echo $this->element('flash_message'); ?>
            <?php  echo $this->MyForm->create('User', array('type' => 'file', 'language' => Configure::read('Config.language'), 'url' => array('plugin' => false))); ?>
            <div>
                <label for="data[time_zone]" class="label-txt"><?php echo __("File to upload. Only .jpeg,.png,.pdf is accepted. File must be not larger than 2Mb. "); ?></label>
                <?php echo $this->MyForm->file('file.', array('label' => false, 'div' => false, 'type' => 'file',  'multiple' => 'multiple', 'class' => 'select')); ?>
            </div>
            <div class="clear"></div>
            <div style="margin-top: 15px;">
                <?php echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn-blue', 'div' => false)); ?>
            </div>
            <div class="clear"></div>
            <?php echo $this->MyForm->end(); ?>
        </div>
    </div>
</div>
<!-- User Docs Upload END -->