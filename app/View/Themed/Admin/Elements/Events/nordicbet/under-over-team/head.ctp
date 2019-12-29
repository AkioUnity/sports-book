<?php if (preg_match_all('/\d+(\.\d+)?/', $betData['name'], $matches) AND isset($matches[0][0]) AND ($matches[0][0] != '0.5' AND $matches[0][0] != '1.5')): ?>
    <th>
    <table>
        <tr>
            <td><?php echo __('Under / Over'); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                if (preg_match_all('/\d+(\.\d+)?/', $betData['name'], $matches)): ?>
                    <?php echo current($matches[0]); ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    </table>
</th>
<?php endif; ?>