<?php if (preg_match_all('/\d+(\.\d+)?/', $betData['name'], $matches) AND isset($matches[0][0]) AND ($matches[0][0] != '0.5' AND $matches[0][0] != '1.5')): ?>
<td>
    <table class="print-body">
        <tr>
            <?php foreach($BetPart AS $part): ?>
                <td><?php echo $part['BetPart']['odd']; ?></td>
            <?php endforeach; ?>
        </tr>
    </table>
</td>
<?php endif; ?>