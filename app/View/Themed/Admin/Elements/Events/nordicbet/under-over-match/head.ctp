<?php $skipMatches = array('0.5', '1.5', '3.5', '4.5'); ?>
<?php if (preg_match_all('/\d+(\.\d+)?/', $betData['name'], $matches) AND isset($matches[0][0]) AND (!in_array($matches[0][0], $skipMatches))): ?>
    <?php if($bettingTypeCount == 1): ?>
    <th>
        <table>
            <tr>
                <td><?php echo __('Under / Over'); ?></td>
            </tr>
            <tr>
                <td>
                    <?php echo current($matches[0]); ?>
                </td>
            </tr>
        </table>
    </th>
    <?php endif; ?>
<?php endif; ?>