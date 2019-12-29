<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-man"></i> <?php echo __('Login'); ?></h5>
        <div class="white-in">
             <?php echo $this->MyForm->create('User', array('url' => array('plugin' => false,  'controller' => 'users', 'action' => 'login'))); ?>
                <div class="login-top">
                    <?php if(isset($request_pass_params[0]) && $request_pass_params[0] == "confirmation"): ?>
                        <span class="info-box">
                            <?php echo __("Registration successful. Please check email for confirmation mail."); ?>
                        </span>
                    <?php endif; ?>
                    <?php if(isset($request_pass_params[0]) && $request_pass_params[0] == "confirmed"): ?>
                        <span class="info-box">
                            <?php echo __("Account confirmation successful. You can sign in now."); ?>
                        </span>
                    <?php endif; ?>
                    <?php if(isset($request_pass_params[0]) && $request_pass_params[0] == "invalid_code"): ?>
                        <span class="info-box">
                            <?php echo __("Not valid confirmation code."); ?>
                        </span>
                    <?php endif; ?>

                    <?php if(($information = $this->Session->flash('user_flash_message_info')) != null): ?>
                        <span class="info-box"><?php echo $information; ?></span>
                    <?php endif; ?>
                    <?php if(($success = $this->Session->flash('user_flash_message_success')) != null): ?>
                        <span class="info-box"><?php echo $success; ?></span>
                    <?php endif; ?>

                    <?php if(($error = $this->Session->flash('user_flash_message_error')) != null): ?>
                        <span class="info-box"><?php echo $error; ?></span>
                    <?php endif; ?>

                    <div class="form-login">
                        <input class="form-input icon-user-w" name="data[User][username]" placeholder="Username" type="text" />
                        <div class="clear"></div>
                        <input class="form-input icon-pass-w" name="data[User][password]" placeholder="Password" type="password" />
                        <div class="clear"></div>
                        <input id="remember" name="data[User][remember]" value="1" type="checkbox" /> <label for="remember"><span></span><?=__('Remember me');?></label>
                        <?php echo $this->MyHtml->link(__('Forgot password?', true), array('plugin' => false, 'controller' => 'users', 'action' => 'reset'), array("class" => "forg")); ?>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="login-bottom">
                    <div class="login-btn">
					<button type="submit" class="btn-silver"><?php echo __("Login");?></button>
                        <?php if (Configure::read('Settings.registration') == 1): ?>
                            <?php echo $this->MyHtml->link(__('Register', true), array('plugin' => false, 'controller' => 'users', 'action' => 'register'), array('class' => 'btn-blue')); ?>
                        <?php endif; ?>
                        
                    </div>
                </div>
            <?php echo $this->MyForm->end(); ?>
        </div>
    </div>
</div>