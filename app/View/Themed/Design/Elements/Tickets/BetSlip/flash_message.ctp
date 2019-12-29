<?php if(($error = $this->Session->flash('user_flash_message_BetSlip_Error_Events_Already_Started')) != null): ?>
    <div class="error-log">
        <?php if (isset($error) AND !empty($error)): ?>
            <ul class="started-events-list">
                <?php foreach ($error AS $Bet): ?>
                    <?php if (isset($Bet["Event"]) AND !empty($Bet["Event"])): ?>
                        <li><?php echo __("%s", $Bet["Event"]["name"]); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>