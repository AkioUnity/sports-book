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

            <?php echo $this->Form->create($this->name, array('method' => 'post', 'url' => array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => $this->name, 'action' => 'index', 'credit', $payment["CreditPayment"]["id"]))); ?>

            <span class="error-message"><?php echo $this->Session->flash(); ?></span>

            <?php echo $this->Form->input('amount', array('label' => __('Deposit amount', true), 'placeholder' => __('Amount', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('interest_rate', array('label' => __('Interest rate', true), 'placeholder' => __('Interest rate', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('net_salary', array('label' => __('Net salary', true), 'placeholder' => __('Net salary', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('assets', array('label' => __('Assets', true), 'placeholder' => __('Assets', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('long_term_debt', array('label' => __('Long term debt', true), 'placeholder' => __('Long term debt', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('short_term_debt', array('label' => __('Short term debt', true), 'placeholder' => __('Short term debt', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('cash_in_bank', array('label' => __('Cash in bank', true), 'placeholder' => __('Cash in bank', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('increase_salary', array('label' => __('Increase salary', true), 'placeholder' => __('Increase salary', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <div class="rem-box">
                <?php echo $this->Form->input('agree1', array('label' => false, 'type' => 'checkbox', 'div' => false, 'error' => false, 'id' => 'data[CreditPayment][agree1]')); ?>
                <label for="data[CreditPayment][agree1]"><span></span></label>
                <div class="rem-txt"> <?php echo __('Agree 1', true); ?> <a target="_blank" href="/<?=Configure::read('Config.language');?>/pages/responsible-gambling"><?php echo __('responsible gambling and conditions', true); ?></a></div>
                <div class="clear"><br /></div>
            </div>

            <div class="rem-box">
                <?php echo $this->Form->input('agree2', array('label' => false, 'type' => 'checkbox', 'div' => false, 'error' => false, 'id' => 'data[CreditPayment][agree2]')); ?>
                <label for="data[CreditPayment][agree2]"><span></span></label>
                <div class="rem-txt"> <?php echo __('Agree 2', true); ?> <a target="_blank" href="/<?=Configure::read('Config.language');?>/pages/responsible-gambling"><?php echo __('responsible gambling and conditions', true); ?></a></div>
                <div class="clear"><br /></div>
            </div>

            <div class="rem-box">
                <?php echo $this->Form->input('agree3', array('label' => false, 'type' => 'checkbox', 'div' => false, 'error' => false, 'id' => 'data[CreditPayment][agree3]')); ?>
                <label for="data[CreditPayment][agree3]"><span></span></label>
                <div class="rem-txt"> <?php echo __('Agree 3', true); ?> <a target="_blank" href="/<?=Configure::read('Config.language');?>/pages/responsible-gambling"><?php echo __('responsible gambling and conditions', true); ?></a></div>
                <div class="clear"><br /></div>
            </div>

            <div class="rem-box">
                <?php echo $this->Form->input('agree4', array('label' => false, 'type' => 'checkbox', 'div' => false, 'error' => false, 'id' => 'data[CreditPayment][agree4]')); ?>
                <label for="data[CreditPayment][agree4]"><span></span></label>
                <div class="rem-txt"> <?php echo __('Agree 4', true); ?> <a target="_blank" href="/<?=Configure::read('Config.language');?>/pages/responsible-gambling"><?php echo __('responsible gambling and conditions', true); ?></a></div>
                <div class="clear"><br /></div>
            </div>

            <?php echo $this->Form->submit(__('Deposit', true), array('class' => 'btn-silver')); ?>
            <div class="clear"><br /></div>

            <style type="text/css">
                .input.text.error { border: none; }
                div.error-message { color: red; margin-left: 2px; margin-top: 7px; }
                .rem-txt { width: 95%; }
            </style>
        </div>
        <?php echo $this->Form->end(); ?>

    </div>
</div>
<!-- Make Deposit END -->