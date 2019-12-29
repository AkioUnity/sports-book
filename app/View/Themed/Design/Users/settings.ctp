<!-- User Settings START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Settings"); ?></h5>
        <div class="white-in">
            <br />
            <?php echo $this->element('flash_message'); ?>
            <?php  echo $this->MyForm->create('User', array('language' => Configure::read('Config.language'), 'url' => array('plugin' => false))); ?>
            <div>
                <label for="data[odds_type]" class="label-txt"><?php echo __("Odds Type"); ?></label>
                <?php echo $this->MyForm->input('odds_type', array('label' => false, 'div' => false, 'type' => 'select', 'options' => $odd_types, 'default' => CakeSession::read("Auth.User.odds_type"), 'id' => 'data[odds_type]')); ?>
            </div>
            <div class="clear"></div>
            <div>
                <label for="data[time_zone]" class="label-txt"><?php echo __("Time zone"); ?></label>
                <?php echo $this->MyForm->input('time_zone', array('label' => false, 'div' => false, 'type' => 'select', 'options' => $this->TimeZone->getTimeZones(), 'class' => 'select')); ?>
            </div>
            <div class="clear"></div>
            <div>
                <label for="data[language_id]" class="label-txt"><?php echo __("Language"); ?></label>
                <?php echo $this->MyForm->input('language_id', array('label' => false, 'div' => false, 'type' => 'select', 'options' => $locales, 'class' => 'select')); ?>
            </div>
            <div class="clear"></div>
            <div style="margin-top: 15px;">
                <?php echo $this->MyForm->submit(__('Confirm Settings', true), array('class' => 'btn-blue', 'div' => false)); ?>
                <?php echo $this->MyHtml->link(__('Change Password'), array('plugin' => false, 'action' => 'reset'), array('class' => 'btn-silver')); ?>
            </div>

            <div class="clear"></div>
            <?php echo $this->MyForm->end(); ?>
        </div>
    </div>
</div>
<!-- User Settings END -->