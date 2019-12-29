<div id="users" class="confirm">
    <h3><?php echo __('Confirmation'); ?></h3>
    <?php if (isset($success)): ?>
        <p><?php echo __('Email has been confirmed. You can now login.'); ?></p>
    <?php else: ?>
        <p><?php echo __('Incorect confirmation code'); ?></p>
    <?php endif; ?>
</div>