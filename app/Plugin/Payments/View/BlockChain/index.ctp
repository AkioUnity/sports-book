<!-- Make Deposit START -->
<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-man"></i> <?php echo __('Make deposit by %s payment provider', $this->name); ?></h5>
        <div class="white-in">
            <br />
            <p><?php echo $this->element('flash_message'); ?></p>
            <br />
            <?php if (!empty($data)  AND is_array($data)): ?>
            <?php endif ?>

            <div class="form-dark">
                <?php echo sprintf("Send payment to this address to deposit your balance: %s", $address); ?>
            </div>
        </div>
    </div>
</div>
<!-- Make Deposit END -->