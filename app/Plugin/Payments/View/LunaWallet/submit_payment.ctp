<!-- Make Deposit START -->
<div class="mbox">
    <h4><?php echo __('Make deposit by %s payment provider', $this->name); ?></h4>
    <div class="dark-table">
        <p>
            <?php echo $this->element('flash_message'); ?>
        </p>
    </div>
    <?php if (!empty($data)  AND is_array($data)): ?>
    <?php endif ?>

    <div class="form-dark">
        <?php echo sprintf("Send payment to this address to deposit your balance: %s", $address); ?>
    </div>
</div>
<!-- Make Deposit END -->