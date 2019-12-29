<div id="users" class="password">
    <h3><?php echo __('Change password'); ?></h3>

    <?php
    echo $this->Session->flash();
    if (!isset($success)) {
        echo $this->MyForm->create('User', array('action' => 'admin_password',
            'inputDefaults' => array('type' => 'password')
                )
        );

        echo $this->MyForm->input('password', array('label' => __('Old password', true), 'class' => 'regi'));

        echo $this->MyForm->input('new_password', array('label' => __('New password', true), 'class' => 'regi'));

        echo $this->MyForm->input('new_password_confirm', array('label' => __('Confirm password', true), 'class' => 'regi'));
        ?>
        <div class="centered">
            <?php echo $this->MyHtml->spanLink(__('Change Password', true), '#', array('class' => 'button-blue', 'onClick' => "jQuery('#UserPasswordForm').submit()")); ?>
        </div>
        <?php
        echo $this->MyForm->end();
    }
    ?>
</div>