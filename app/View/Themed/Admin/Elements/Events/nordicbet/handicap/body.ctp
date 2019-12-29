<?php if($bettingTypeCount == 1): ?>
<td style="width: 115px;">
    <table class="print-body">
        <tr>
            <?php foreach($BetPart AS $part): ?>
                <td><?php echo $part['BetPart']['odd']; ?></td>
            <?php endforeach; ?>
        </tr>
    </table>
</td>
<?php endif; ?>