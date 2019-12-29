<h3><?php echo __('Settings'); ?></h3>

<div id="account">
    <?php echo $this->element('flash_message'); ?>
    <?php
    echo $this->MyForm->create('User', array(
        'action' => 'admin_settings',
        'inputDefaults' => array(
            'label' => false,
            'div' => false,
            'class' => 'regi',
            # define error defaults for the form    
            'error' => array(
                'wrap' => 'span',
                'class' => 'my-error-class'
            )
        )
    ));
    ?>
    <table class="items">
<!--
        <tr>
            <td><label><?php echo __('Odds type'); ?></label></td>
            <td><?php echo $this->MyForm->input('odds_type', array('type' => 'select', 'options' => $this->Beth->getOddsTypes()));  ?></td>
        </tr>
-->
        <tr>
            <td><label><?php echo __('Time zone'); ?></label></td>
            <td><?php echo $this->MyForm->input('time_zone', array('type' => 'select', 'options' =>  $this->TimeZone->getTimeZones())); ?></td>
        </tr>

        <tr>
            <td><label><?php echo __('Language'); ?></label></td>            
            <td><?php echo $this->MyForm->input('language_id', array('type' => 'select', 'options' => $locales)); ?></td>
        </tr>

    </table>

    <?php echo $this->MyForm->submit(__('Submit', true), array('class' => 'button')); ?>
    <?php echo $this->MyForm->end(); ?>

</div>