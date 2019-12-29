<div class="sep">
    <div class="tgl-blue tgl-round opened">
        <?php echo __($Bet['type']); ?>
        <?php if(in_array(strtolower($Bet['type']), array('under/over'))): ?><?php echo $Bet['BetPart'][0]["BetPart"]["line"]; ?><?php endif;?>
    </div>
    <div class="tgl-blue-content open-cell">
        <table class="tgl-small-table">
            <tr>
                <?php foreach($Bet['BetPart'] AS $i => $betPart): ?>
                    <?php if (isset($betPart["BetPart"]["suspended"]) && $betPart["BetPart"]["suspended"] == 1): ?>
                        <td id="" class="">
                            <div class="lmb lock"></div>
                        </td>
                    <?php else: ?>
                    <td id="<?php echo $betPart["BetPart"]['id']; ?>" class="addBet">
                        <span class="right"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                        <?php echo $betPart['BetPart']['name']; ?>
                        <span class="clear"></span>
                    </td>
                    <?php endif ;?>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
</div>