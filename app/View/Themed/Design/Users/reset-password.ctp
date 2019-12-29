<!-- Password Reset START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-man"></i> <?php echo __('Reset Password'); ?></h5>
        <div class="white-in">
            <?php if(($information = $this->Session->flash('user_flash_message_info')) != null): ?>
                <span class="info-box"><?php echo $information; ?></span>
            <?php endif; ?>
            <?php if(($success = $this->Session->flash('user_flash_message_success')) != null): ?>
                <span class="info-box"><?php echo $success; ?></span>
            <?php endif; ?>

            <?php if(($error = $this->Session->flash('user_flash_message_error')) != null): ?>
                <span class="info-box"><?php echo $error; ?></span>
            <?php endif; ?>

            <?php if (!$success): ?>
                <?php echo $this->MyForm->create('User', array('url' => array('language' => Configure::read('Config.language'), 'plugin' => false, 'action' => 'reset', 'code' => $code, 'step' => 2))); ?>
                <div class="login-top">
                    <div class="form-login">
                        <input class="form-input icon-user-w" name="data[User][email]" placeholder="<?php echo __("Email"); ?>" type="text" />
                        <div class="clear"></div>
                    </div>
                    <div class="form-login">
                        <input class="form-input icon-user-w" name="data[User][password]" placeholder="<?php echo __("Password"); ?>" type="password" />
                        <div class="clear"></div>
                    </div>
                    <div class="form-login">
                        <input class="form-input icon-user-w" name="data[User][password_confirm]" placeholder="<?php echo __("Confirm password"); ?>" type="password" />
                        <div class="clear"></div>
                    </div>
                </div>
                <?php  echo $this->MyForm->input('code', array('value' => $code, 'type' => 'hidden')); ?>
                <div class="login-bottom">
                    <div class="login-btn">
                        <button type="submit" class="btn-silver"><?php echo __("Change password");?></button>
                    </div>
                </div>
                <?php echo $this->MyForm->end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Password Reset END -->