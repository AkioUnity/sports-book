<div id="mails" class="contact">
    <h3><?php echo __('Contact support team'); ?></h3>

    <p class="MsoNormal" style="margin-bottom: 0.0001pt;"><span style="font-size: 8.0pt; font-family: 'Verdana','sans-serif';">If you have any problem, do not hesitate to contact the customer service using form below:</span></p>
       
    <?php echo $this->element('flash_message'); ?>
    <?php echo $this->MyForm->create('Mail', array('inputDefaults' => array('div' => false, 'label' => false))); ?>
    <table class="default-table contact">
        <tr>
            <td><?php echo __('Subject'); ?></td>
            <td><?php echo $this->MyForm->input('subject', array('class' => 'regi')); ?></td>
        </tr>
        <tr>
            <td><?php echo __('Name'); ?></td>
            <td>
                <?php echo $this->MyForm->input('name', array('class' => 'regi')); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo __('Email'); ?></td>
            <td><?php echo $this->MyForm->input('email', array('class' => 'regi')); ?></td>
        </tr>
        <tr>
            <td><?php echo __('Message'); ?></td>
            <td><?php echo $this->MyForm->input('content', array('class' => 'regi', 'type' => 'textarea')); ?></td>
        </tr>
    </table>
    <div class="centered">
        <?php echo $this->MyForm->submit(__('Send', true), array('class' => 'button')); ?>
    </div>
    <?php echo $this->MyForm->end(); ?>
</div>