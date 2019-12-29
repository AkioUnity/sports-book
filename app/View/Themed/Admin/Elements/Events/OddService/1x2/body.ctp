<td>
    <table class="print-body">
        <tr>
            <?php foreach($BetPart AS $index => $part): ?>
                <td <?php if($index == 1): ?>class="border right left";<?php endif;?>><?php echo $part['BetPart']['odd']; ?></td>
            <?php endforeach; ?>
        </tr>
    </table>
</td>