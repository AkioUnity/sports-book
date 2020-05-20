<style>
.reverse {
font-size: 80%;
}
</style>

<!-- <div class="deposit">
    <div class="clear"></div>
        <div class="inline-cell">
            <h4><?php echo __('Methods of FIAT funding'); ?></h4>
            <div class="img-responsive">
		        <a href="/<?=Configure::read('Config.language');?>/pages/payment-methods"><img class="img-responsive" src="/theme/Design/img/payments1.png" alt=""></a>
            </div>
        </div>
		        <div class="inline-cell">
            <h4><?php echo __('Methods of over 300 Crypto paymetn gateways'); ?></h4>
            <div class="img-responsive">
		        <a href="/<?=Configure::read('Config.language');?>/pages/coins-methods"><img class="img-responsive" src="/theme/Design/img/coins.png" alt=""></a>
            </div>
        </div>
    <div class="clear"></div>
</div>-->
<div class="footer-menu">
    <div class="container">
        <div class="col3">
            <h4><?php echo __('Support'); ?></h4>
            <ul class="support">
                <!-- <li><i class="fa fa-skype"></i> <span class="reverse">wissemneifer</span></li> -->
                <li><i class="fa fa-envelope-o"></i> <span class=""><a href="mailto:support@planet1x2.com">support@planet1x2.com </a></span></li>
                <!-- <li><i class="fa fa-phone"></i> <span class="reverse">+352 621 177 744 Mobile|Whatsapp</span></li> -->
                <li><i class="fa fa-clock-o"></i>  <span class="">Support 24/7</span></li>
            </ul>

        </div>
        <div class="col3">
            <h4><?php echo __('HELP'); ?></h4>
            <ul class="support">
                <li><a href="/<?=Configure::read('Config.language');?>/pages/payment-methods"><?php echo __('Payment Methods'); ?></a></li>
                <li><a href="/<?=Configure::read('Config.language');?>/pages/betting-rules"><?php echo __('Betting Rules'); ?></a></li>
                <li><a href="/<?=Configure::read('Config.language');?>/pages/responsible-gambling"><?php echo __('Responsible Gambling'); ?></a></li>
            </ul>
        </div>
        <div class="col3">
            <h4><?php echo __('Become a partner'); ?></h4>
            <ul class="support">

                <li><i class="fa fa-envelope-o"></i> <span class="reverse">
                    <a href="mailto:partner@planet1x2.com">partner@planet1x2.com</a></span></li>

            </ul>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="footer-txt">
    <div class="container">
        <?php echo Configure::read('Settings.defaultTitle'); ?> <?php echo __('online betting platform offers you the best odds in the market on football betting, and tennis betting as well as dozens of other sports and special events. While sports betting is the key of sportsbook daily supply, we also offer a wide range of odds on some of the world\'s major political and entertainment events.'); ?>
    </div>
</div>
<div class="copyright">
    <div class="container">
        <?php echo Configure::read('Settings.defaultTitle'); ?> <?php echo __('2018 all rights reserved. Gambling under 18 is forbiden.'); ?> <img alt="18_mini" height="24" src="/theme/Design/img/18.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="<?php echo __('Participation in our sportbook gaming offer is only allowed to persons 18 years of age or older. In order to prevent any abuse, please keep your access data (user ID, password, question-answer combination) in a safe place.'); ?>" width="24" />
    </div>
</div>