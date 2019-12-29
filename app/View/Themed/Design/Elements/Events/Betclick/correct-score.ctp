<div class="sep">
    <div class="tgl-blue tgl-round opened">
        <?php echo __($Bet['type']); ?>
    </div>
    <div class="tgl-blue-content open-cell">
        <table class="tgl-small-table">
            <?php $total = count($Bet['BetPart']); ?>
            <?php $even = $total % 2; ?>
            <?php foreach($Bet['BetPart'] AS $i => $betPart): ?>
                <?php if($i % 2 == 0 ):?>
                <tr>
                <?php endif;?>
                    <?php if (isset($betPart["BetPart"]["suspended"]) && $betPart["BetPart"]["suspended"] == 1): ?>
                        <td <?php if($even == 1 && $i == ($total - 1)):?>colspan="2"<?php endif;?> id="" class="">
                            <div class="lmb lock"></div>
                        </td>
                    <?php else: ?>
                        <td <?php if($even == 1 && $i == ($total - 1)):?>colspan="2"<?php endif;?> id="<?php echo $betPart["BetPart"]['id']; ?>" class="addBet">
                            <span class="right"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                            <?php echo $betPart['BetPart']['name']; ?>
                            <?php switch(strtolower($Bet['type'])):
                                case 'number of goals':?>
                                    <?php echo __('goals'); ?>
                                    <?php break; ?>
                                <?php endswitch; ?>
                            <span class="clear"></span>
                        </td>
                <?php endif;?>
                <?php if($i % 2 == 1 ):?>
                </tr>
                <?php endif;?>
            <?php endforeach; ?>
        </table>
    </div>
</div>