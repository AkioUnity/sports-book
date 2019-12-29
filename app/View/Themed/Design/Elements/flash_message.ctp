<?php if(($information = $this->Session->flash('user_flash_message_info')) != null): ?>
    <div class="error-log"><?php echo $information; ?></div>
<?php endif; ?>
<?php if(($success = $this->Session->flash('user_flash_message_success')) != null): ?>
    <div class="error-log"><?php echo $success; ?></div>
<?php endif; ?>

<?php if(($error = $this->Session->flash('user_flash_message_error')) != null): ?>
    <div class="error-log"><?php echo $error; ?></div>
<?php endif; ?>