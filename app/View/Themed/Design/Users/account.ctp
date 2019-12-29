<!-- Account Data START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Account information"); ?></h5>
        <div class="white-in">

            <?php echo $this->element('flash_message'); ?>

            <h1><?php echo __("Please provide full personal information to confirm your identity. Owing to regulations, your access to some products, markets and commission discounts may be restricted until you confirm your identity. You can provide personal documents using our secure email service."); ?></h1>

            <?php echo $this->MyForm->create('User', array('url' => array('language' => Configure::read('Config.language'), 'plugin' => false))); ?>

            <div class="row-form-u">
                <?php if ($this->MyForm->error('username')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('Username', true); ?><span><?php echo $this->MyForm->error('username', array('div' => false)); ?></span></label>
                <?php else: ?>
                    <label for="username" class="label-txt"><?php echo __('Username', true); ?></label>
                <?php endif; ?>
                <?php echo $this->MyForm->input('username', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false, 'value' => $user['username'], 'disabled' => true)); ?>
            </div>

            <div class="row-form-u">
                <?php if ($this->MyForm->error('address1')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('Address', true); ?><span><?php echo $this->MyForm->error('address1', array('div' => false)); ?></span></label>
                <?php else: ?>
                    <label for="address1" class="label-txt"><?php echo __('Address', true); ?></label>
                <?php endif; ?>

                <input type="hidden" name="address1" value="-">
                <input type="hidden" name="address2" value="-">

                <?php echo $this->MyForm->input('address1', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false, 'value' => $user['address1'])); ?>
                <?php echo $this->MyForm->input('address2', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false, 'value' => $user['address2'])); ?>
            </div>

            <div class="row-form-u">
                <?php if ($this->MyForm->error('zip_code')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('Zip code', true); ?><span><?php echo $this->MyForm->error('zip_code', array('div' => false)); ?></span></label>
                <?php else: ?>
                    <label for="zip_code" class="label-txt"><?php echo __('Zip code', true); ?></label>
                <?php endif; ?>
                <?php echo $this->MyForm->input('zip_code', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false, 'value' =>  $user['zip_code'])); ?>
            </div>

            <div class="row-form-u">
                <?php if ($this->MyForm->error('city')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('City', true); ?><span><?php echo $this->MyForm->error('city', array('div' => false)); ?></span></label>
                <?php else: ?>
                    <label for="city" class="label-txt"><?php echo __('City', true); ?></label>
                <?php endif; ?>
                <?php echo $this->MyForm->input('city', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false, 'value' => $user['city'])); ?>

            </div>

            <div class="row-form-u">
                <?php if ($this->MyForm->error('country')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('Country', true); ?><span><?php echo $this->MyForm->error('country', array('div' => false)); ?></span></label>
                <?php else: ?>
                    <label for="country" class="label-txt"><?php echo __('Country', true); ?></label>
                <?php endif; ?>
                <?php  echo $this->MyForm->input('country', array('label' => false, 'div' => false, 'options' => $countries, 'type' => 'select', 'error' => false, 'default' => $user['country'])); ?>
            </div>

            <div class="row-form-u">
                <?php if ($this->MyForm->error('mobile_number')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('Mobile number', true); ?><span><?php echo $this->MyForm->error('mobile_number', array('div' => false)); ?></span></label>
                <?php else: ?>
                    <label for="mobile_number" class="label-txt"><?php echo __('Mobile number', true); ?></label>
                <?php endif; ?>
                <?php echo $this->MyForm->input('mobile_number', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false, 'value' => $user['mobile_number'])); ?>
            </div

            <br />


            <div class="clear"></div>

            <div class="form-nav1">
                <button type="submit" class="btn-regs"><?php echo __("Confirm changes"); ?></button>

                <div class="clear"></div>
            </div>

            <?php echo $this->MyForm->end(); ?>
        </div>
    </div>
</div>



<!-- Account Data END -->