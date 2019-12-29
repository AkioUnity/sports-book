<div class="menu">
    <a class="logo" href="/<?php echo Configure::read('Config.language'); ?>"><img src="/theme/Redesign/img/logo.png?t=1"></a>
    <?php if (Configure::read('Settings.registration') == 1 AND !$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>
        <?php echo $this->MyHtml->link(__('SIGN UP NOW!', true), array('plugin' => false, 'controller' => 'users', 'action' => 'register'), array('class' => 'sign')); ?>
    <?php endif; ?>
    <ul>
        <li class="active"><a href="/"><?php echo __("Sports"); ?></a></li>
    </ul>
    <div class="clear"></div>
</div>