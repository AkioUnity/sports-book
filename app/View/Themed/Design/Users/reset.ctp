<!-- Password Reset START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-man"></i> <?php echo __('Reset Password'); ?></h5>
        <div class="white-in">
            <br />
            <p><?php echo __('Fill in email details below. Password reset link sent to your entered email. You will be able to create a new password which you can use to login.'); ?></p>
            <br />
            <?php if(($information = $this->Session->flash('user_flash_message_info')) != null): ?>
                <span class="info-box"><?php echo $information; ?></span>
            <?php endif; ?>
            <?php if(($success = $this->Session->flash('user_flash_message_success')) != null): ?>
                <span class="info-box"><?php echo $success; ?></span>
            <?php endif; ?>

            <?php if(($error = $this->Session->flash('user_flash_message_error')) != null): ?>
                <span class="info-box"><?php echo $error; ?></span>
            <?php endif; ?>

            <?php echo $this->MyForm->create('User', array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'reset')); ?>
            <div class="login-top">
                <div class="form-login">
                    <input class="form-input icon-user-w" name="data[User][email]" placeholder="<?php echo __("Email"); ?>" type="text" />
                    <div class="clear"></div>
                </div>
                <div style="margin: 0 auto; width: 53%;" class="g-recaptcha" data-sitekey="6LfBLjAUAAAAAEgbxtJi-GYp1iEZcnKZHMWLXddg"></div>
            </div>
            <div class="login-bottom">
                <div class="login-btn">
                    <button type="submit" class="btn-silver"><?php echo __("Change");?></button>
                </div>
            </div>
            <?php echo $this->MyForm->end(); ?>
        </div>
    </div>
</div>
<!-- Password Reset END -->