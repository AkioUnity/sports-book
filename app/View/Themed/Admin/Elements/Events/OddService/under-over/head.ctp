<?php if($bettingTypeCount == 1): ?>
    <th>
        <table class="print-head">
            <tr>
                <td><?php echo __('Under / Over'); ?> ( <?php echo isset($betData['BetPart'][0]['BetPart']['line']) ? $betData['BetPart'][0]['BetPart']['line'] : '-'; ?> )</td>
            </tr>
        </table>
    </th>
<?php endif; ?>