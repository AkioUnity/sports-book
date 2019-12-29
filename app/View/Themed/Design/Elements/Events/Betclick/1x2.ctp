<div class="sep">
    <div class="tgl-blue tgl-round opened">
        <?php echo __($Bet['type']); ?>
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
                        <span class="right">
                            <?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?>
                        </span>
                        <?php echo $betPart['BetPart']['name']; ?>
                        <?php switch(strtolower($Bet['type'])):
                            case 'total goals':?>
                                <?php echo __('goals'); ?>
                            <?php break; ?>
                        <?php endswitch; ?>
                        <span class="clear"></span>
                    </td>
                <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
</div>