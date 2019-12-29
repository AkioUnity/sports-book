<!-- Make Deposit START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __('Make deposit by %s payment provider', $this->name); ?></h5>
        <div class="white-in">

            <div>
                <img src="<?=$this->Html->url('/theme/Design/img/cash.png');?>" alt="" class="payments" />
            </div>
            <br />
            <?php echo $this->element('flash_message'); ?>

            <div class="error-message"><?=__("We're sorry. You cannot request/update credit. Please read your agreement terms.");?></div>
            <br />
            <div>
                <a class="btn-silver" href="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'deposits', 'action' => 'index')); ?>"><?=__("Back");?></a>
            </div>
        </div>
    </div>
</div>
<!-- Make Deposit END -->