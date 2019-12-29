<?php if(($information = $this->Session->flash('admin_flash_message_info')) != null): ?>
    <div class="alert alert-info">
        <button class="close" data-dismiss="alert">×</button>
        <?php echo $information; ?>
    </div>
<?php endif; ?>

<?php if(($success = $this->Session->flash('admin_flash_message_success')) != null): ?>
    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">×</button>
        <?php echo $success; ?>
    </div>
<?php endif; ?>

<?php if(($error = $this->Session->flash('admin_flash_message_error')) != null): ?>
    <div class="alert alert-error">
        <button class="close" data-dismiss="alert">×</button>
        <?php echo $error; ?>
    </div>
<?php endif; ?>