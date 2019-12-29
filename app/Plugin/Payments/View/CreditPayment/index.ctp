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

            <?php echo $this->Form->create($this->name, array('url' => array('language' => Configure::read('Config.language'), 'plugin' => 'payments', 'controller' => $this->name, 'action' => 'submitPayment'))); ?>

            <span class="error-message"><?php echo $this->Session->flash(); ?></span>

            <?php echo $this->Form->input('net_salary', array('label' => '<div class="float-left">'.__('Net salary').'</div>' . '<img alt="18_mini" height="24" src="/theme/Design/img/information.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="" width="24" data-original-title="'. __("Question mark") .'">', 'placeholder' => __('Net salary', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false)); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('assets', array('label' => '<div class="float-left">'.__('Assets').'</div>' . '<img alt="18_mini" height="24" src="/theme/Design/img/information.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="" width="24" data-original-title="'. __("Question mark") .'">', 'placeholder' => __('Assets', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false, 'disabled' => 'disabled')); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('long_term_debt', array('label' => '<div class="float-left">'.__('Long term debt').'</div>' . '<img alt="18_mini" height="24" src="/theme/Design/img/information.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="" width="24" data-original-title="'. __("Question mark") .'">', 'placeholder' => __('Long term debt', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false)); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('short_term_debt', array('label' => '<div class="float-left">'.__('Short term debt').'</div>' . '<img alt="18_mini" height="24" src="/theme/Design/img/information.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="" width="24" data-original-title="'. __("Question mark") .'">', 'placeholder' => __('Short term debt', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false)); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('cash_in_bank', array('label' => '<div class="float-left">'.__('Cash in bank').'</div>' . '<img alt="18_mini" height="24" src="/theme/Design/img/information.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="" width="24" data-original-title="'. __("Question mark") .'">', 'placeholder' => __('Cash in bank', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false)); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->input('increase_salary', array('label' => '<div class="float-left">'.__('Increase salary').'</div>' . '<img alt="18_mini" height="24" src="/theme/Design/img/information.png" class="toltips" data-toggle="tooltip" data-html="true" data-placement="top" title="" width="24" data-original-title="'. __("Question mark") .'">', 'placeholder' => __('Increase salary', true),  'type' => 'text',  'class' => 'form-inp', 'required' => false)); ?>
            <div class="clear"><br /></div>

            <?php echo $this->Form->submit(__('Apply', true), array('class' => 'btn-silver')); ?>
            <div class="clear"><br /></div>

            <style type="text/css">
                .input.text.error { border: none; }
                div.error-message { color: red; margin-left: 2px; margin-top: 7px; }
                .input.text div.float-left { float: left; margin-top: 4px; margin-right: 10px; }
                .input.text img { margin-bottom: 5px; }
            </style>
        </div>
        <?php echo $this->Form->end(); ?>

    </div>
</div>
<!-- Make Deposit END -->