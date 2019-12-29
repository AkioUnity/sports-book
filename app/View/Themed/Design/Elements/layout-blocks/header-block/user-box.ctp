<?php if (!$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>
    <div id="login-form">
        <?php echo $this->MyForm->create('User', array('url' => array('plugin' => false,  'controller' => 'users', 'action' => 'login'))); ?>


            <div class="form-inputs1">
                <input class="login-input icon-user" name="data[User][username]" placeholder="Username" type="text" /><br />
            </div>
            <div class="form-inputs2">
                <input class="login-input icon-pass" name="data[User][password]" placeholder="Password" type="password" /><br />
                <?php echo $this->MyHtml->link(__('Reset password!', true), array('plugin' => false, 'controller' => 'users', 'action' => 'reset')); ?>
            </div>
            <div class="form-submit">
                <button type="submit" class="btn-silver">Login</button>
                <?php if (Configure::read('Settings.registration') == 1): ?>
                    <?php $href = $this->MyHtml->url( array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'users', 'action' => 'register'), array('class' => 'btn-blue')); ?>
                    <button type="button" onclick="window.location.href='<?=$href;?>';" class="btn-blue">
                        <?php echo __("Register"); ?>
                    </button>
                <?php endif; ?>
            </div>
            <div class="clear"></div>

        <?php echo $this->MyForm->end(); ?>
    </div>
<?php else: ?>
    <div id="loged">
        <div class="usr-pl"><?php echo $this->MyHtml->link(CakeSession::read('Auth.User.username'), array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'tickets', 'action' => 'index')); ?></div>
        <div class="usr-mo"><?php echo sprintf("%s %s", Configure::read('Settings.currency'), number_format((float)CakeSession::read('Auth.User.balance'), intval(Configure::read('Settings.balance_decimal_places')), '.', ''))?></div>
        <?php echo $this->MyHtml->link(__('My account', true), array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'tickets', 'action' => 'index'), array("class" => "usr-btn")); ?>
        <?php echo $this->MyHtml->link(__('Log out', true), array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'users', 'action' => 'logout'), array("class" => "usr-btn")); ?>
        <div class="clear"></div>
    </div>
<?php endif;