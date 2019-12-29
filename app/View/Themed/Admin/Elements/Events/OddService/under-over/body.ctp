<?php //$skipMatches = array('0.5', '1.5', '3.5', '4.5'); ?>
<?php //if (preg_match_all('/\d+(\.\d+)?/', $betData['name'], $matches) AND isset($matches[0][0]) AND (!in_array($matches[0][0], $skipMatches))): ?>
    <?php if($bettingTypeCount == 1): ?>
    <td>
        <table class="print-body">
            <tr>
                <?php foreach($BetPart AS $index => $part): ?>
                    <td <?php if($index == 1): ?>class="border left";<?php endif;?>><?php echo $part['BetPart']['odd']; ?></td>
                <?php endforeach; ?>
            </tr>
        </table>
    </td>
    <?php endif; ?>
<?php //endif; ?>