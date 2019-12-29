<?php if($bettingTypeCount == 1): ?>
<th style="width: 115px;">
    <table>
        <tr>
            <td><?php echo __('Handicap'); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                if (preg_match_all('/\w\(.*\)/', $betData['name'], $matches)): ?>
                    <?php echo current($matches[0]); ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    </table>
</th>
<?php endif; ?>