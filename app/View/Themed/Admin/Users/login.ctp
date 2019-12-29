<div id="users" class="login">
    <h3><?php echo __('Login'); ?></h3>

    <?php
    echo $this->Session->flash();
    echo $this->Session->flash('auth');

    echo $this->MyForm->create('User', array('action' => 'admin_login'));

    echo $this->MyForm->input('username', array('label' => __('Username', true), 'class' => 'regi'));

    echo $this->MyForm->input('password', array('label' => __('Password', true), 'class' => 'regi'));
    ?>
    <div class="lefted">
        <?php echo $this->MyForm->submit(__('Login', true), array('class' => 'button')); ?>
    </div>
    <?php
    echo $this->MyForm->end();
    ?>

    <div class="lefted">
        <?php echo $this->MyHtml->link(__('Register now!', true), '#', array('class' => 'button', 'onclick' =>'registrationForm()')); ?>
        <?php echo $this->MyHtml->link(__('Forgotten your password?', true), array('action' => 'admin_reset'), array('class' => 'button')); ?>
    </div>
</div>