<!-- Make Deposit START -->

<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __('Make deposit by %s payment provider', $this->name); ?></h5>
        <div class="white-in">

            <div>
                <img src="<?=$this->Html->url('/theme/Design/img/wecashup.png');?>" alt="" class="payments" />
            </div>
            <br />
            <?php echo $this->element('flash_message'); ?>

            <form action="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => 'WeCashUp', 'action' => 'callback'), true); ?>" method="POST">
                <script async src="https://www.wecashup.cloud/live/2-form/js/MobileMoney.js" class="wecashup_button"
                        data-receiver-uid="<?=$config['merchant_uid'];?>"
                        data-receiver-public-key="<?=$config['merchant_public_key'];?>"
                        data-transaction-receiver-total-amount="<?=$amount;?>"
                        data-transaction-receiver-currency="USD"
                        data-name="<?=sprintf('%s', Configure::read('Settings.websiteName'));?>"
                        data-transaction-receiver-reference="<?=CakeSession::read('Auth.User.id'); ?>"
                        data-transaction-sender-reference="<?=CakeSession::read('Auth.User.id'); ?>"
                        data-style="1"
                        data-image="https://www.wecashup.cloud/live/2-form/img/home.png"
                        data-cash="true"
                        data-telecom="true"
                        data-m-wallet="false"
                        data-split="false"
                        data-sender-lang="en"
                        data-sender-phonenumber="+237683871872">
                </script>
            </form>

            </div>
    </div>
</div>
<!-- Make Deposit END -->