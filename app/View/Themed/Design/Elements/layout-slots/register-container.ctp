<div class="reg-container" >
    <a class="reg_close" href="#" >
        <span class=" icon--close">
            <i class="fas fa-times"></i>
        </span>
    </a>

    <div class="user-avater">
        <img src="/theme/Design/img/logo.png" alt="">
    </div>

    <div class="login-head">
        <h3><?php echo __('New to Wizabet?') ?></h3>
        <p style="margin-bottom:15px;"><?php echo __("Sign up now, it's easy!") ?></p>
    </div>
    <div class="reg_inner">
        <div id="reg-form">
               <?php echo $this->MyForm->create('User', array('url' => array('plugin' => false,  'controller' => 'users', 'action' => 'register'))); ?>

                    <?php if ($this->MyForm->error('email')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Email', true); ?><span><?php echo $this->MyForm->error('email', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="email" class="label-txt"><?php echo __('Email', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('email', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('username')): ?>
                    <label for="a1" class="label-txt error-label"><?php echo __('Username', true); ?><span><?php echo $this->MyForm->error('username', array('div' => false)); ?></span></label>
                    <?php else: ?>
                    <label for="username" class="label-txt"><?php echo __('Username', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('username', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('password')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Password', true); ?><span><?php echo $this->MyForm->error('password', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="password" class="label-txt"><?php echo __('Password', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('password', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('password_confirm')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Confirm password', true); ?><span><?php echo $this->MyForm->error('password_confirm', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="password_confirm" class="label-txt"><?php echo __('Confirm password', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('password_confirm', array('label' => false, 'type' => 'password', 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>
                    <?php if ($this->MyForm->error('first_name')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('First name', true); ?><span><?php echo $this->MyForm->error('first_name', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="first_name" class="label-txt"><?php echo __('First name', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('first_name', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('last_name')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Last name', true); ?><span><?php echo $this->MyForm->error('last_name', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="last_name" class="label-txt"><?php echo __('Last name', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('last_name', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('date_of_birth')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Date of birth', true); ?><span><?php echo $this->MyForm->error('date_of_birth', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        
                    <label for="date_of_birth" class="label-txt"><?php echo __('Date of birth', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->day('date_of_birth', array('label' => false, 'div' => false, 'empty' => 'DD', 'class' => 'sm3', 'error' => false)); ?>
                    <?php echo $this->MyForm->month('date_of_birth', array('label' => false, 'empty' => 'MM', 'class' => 'sm3', 'div' => false, 'error' => false)); ?>
                    <?php echo $this->MyForm->year('date_of_birth', 1920, date('Y'), array('label' => false, 'empty' =>'YY', 'class' => 'sm3', 'div' => false, 'error' => false)); ?>
                    <?php if ($this->MyForm->error('zip_code')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Zip code', true); ?><span><?php echo $this->MyForm->error('zip_code', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="zip_code" class="label-txt"><?php echo __('Zip code', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('zip_code', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('city')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('City', true); ?><span><?php echo $this->MyForm->error('city', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="city" class="label-txt"><?php echo __('City', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('city', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('address1')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Address', true); ?><span><?php echo $this->MyForm->error('address1', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="address1" class="label-txt"><?php echo __('Address', true); ?></label>
                    <?php endif; ?>

                    <input type="hidden" name="address1" value="-">

                    <?php echo $this->MyForm->input('address1', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('mobile_number')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Mobile number', true); ?><span><?php echo $this->MyForm->error('mobile_number', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="mobile_number" class="label-txt"><?php echo __('Mobile number', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('mobile_number', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('personal_question')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Personal question', true); ?><span><?php echo $this->MyForm->error('personal_question', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="personal_question" class="label-txt"><?php echo __('Personal question', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('personal_question', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <?php if ($this->MyForm->error('personal_answer')): ?>
                        <label for="a1" class="label-txt error-label"><?php echo __('Personal answer', true); ?><span><?php echo $this->MyForm->error('personal_answer', array('div' => false)); ?></span></label>
                    <?php else: ?>
                        <label for="personal_answer" class="label-txt"><?php echo __('Personal answer', true); ?></label>
                    <?php endif; ?>
                    <?php echo $this->MyForm->input('personal_answer', array('label' => false, 'div' => false, 'class' => 'form-inp', 'error' => false)); ?>

                    <div class="white-in rem-box">
                        <?php echo $this->MyForm->input('agree', array('label' => false, 'type' => 'checkbox', 'div' => false, 'error' => false, 'id' => 'data[User][agree]')); ?>
                        <label for="data[User][agree]"><span></span></label>
                        <div class="rem-txt"> <?php echo __('I am over 18 years of age and have read and accepted', true); ?> <a target="_blank" href="/<?=Configure::read('Config.language');?>/pages/responsible-gambling"><?php echo __('responsible gambling and conditions', true); ?></a></div>
                        <div class="clear"></div>
                    </div>
                    <button type="submit" class="btn-regs"><?php echo __("Register"); ?></button>
                    <div class="clear"></div>

            <?php echo $this->MyForm->end(); ?>
            <div class="clear"></div>
        </div>
    </div>

</div>