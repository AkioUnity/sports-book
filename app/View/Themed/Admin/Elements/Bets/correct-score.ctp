<?php echo $this->MyForm->create($model, array('url' => array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_add', $event_id, $bet_type))); ?>
    <div class="input text"><label for="BetBetName">Bet Name</label><input name="data[Bet][bet_name]" value="Correct Score" disabled="disabled" type="text" id="BetBetName"></div>                                                    <table id="table_liquid" class="picksTable" cellpadding="0" cellspacing="0">
    <tbody><tr>
        <th>Name</th>
        <th>Odd</th>
    </tr>
    <tr>
        <td>
            <input class="input-big" type="hidden" name="data[Bet][name]" maxlength="255" id="BetPartName" value="Correct Score">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][0][name]" maxlength="255" id="BetPartName" value="0-0" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][0][name]" maxlength="255" id="BetPartName" value="0-0">
            <input class="input-big" type="hidden" name="data[BetPart][0][line]" maxlength="255" id="BetPartName" value="0-0">
            <input class="input-big" type="hidden" name="data[BetPart][0][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][0][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][1][name]" maxlength="255" id="BetPartName" value="0-1" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][1][name]" maxlength="255" id="BetPartName" value="0-1">
            <input class="input-big" type="hidden" name="data[BetPart][1][line]" maxlength="255" id="BetPartName" value="0-1">
            <input class="input-big" type="hidden" name="data[BetPart][1][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][1][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][2][name]" maxlength="255" id="BetPartName" value="0-2" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][2][name]" maxlength="255" id="BetPartName" value="0-2">
            <input class="input-big" type="hidden" name="data[BetPart][2][line]" maxlength="255" id="BetPartName" value="0-2">
            <input class="input-big" type="hidden" name="data[BetPart][2][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][2][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][3][name]" maxlength="255" id="BetPartName" value="0-3" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][3][name]" maxlength="255" id="BetPartName" value="0-3">
            <input class="input-big" type="hidden" name="data[BetPart][3][line]" maxlength="255" id="BetPartName" value="0-3">
            <input class="input-big" type="hidden" name="data[BetPart][3][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][3][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][4][name]" maxlength="255" id="BetPartName" value="0-4" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][4][name]" maxlength="255" id="BetPartName" value="0-4">
            <input class="input-big" type="hidden" name="data[BetPart][4][line]" maxlength="255" id="BetPartName" value="0-4">
            <input class="input-big" type="hidden" name="data[BetPart][4][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][4][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][5][name]" maxlength="255" id="BetPartName" value="1-0" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][5][name]" maxlength="255" id="BetPartName" value="1-0">
            <input class="input-big" type="hidden" name="data[BetPart][5][line]" maxlength="255" id="BetPartName" value="1-0">
            <input class="input-big" type="hidden" name="data[BetPart][5][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][5][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][6][name]" maxlength="255" id="BetPartName" value="1-1" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][6][name]" maxlength="255" id="BetPartName" value="1-1">
            <input class="input-big" type="hidden" name="data[BetPart][6][line]" maxlength="255" id="BetPartName" value="1-1">
            <input class="input-big" type="hidden" name="data[BetPart][6][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][6][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][7][name]" maxlength="255" id="BetPartName" value="1-2" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][7][name]" maxlength="255" id="BetPartName" value="1-2">
            <input class="input-big" type="hidden" name="data[BetPart][7][line]" maxlength="255" id="BetPartName" value="1-2">
            <input class="input-big" type="hidden" name="data[BetPart][7][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][7][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][8][name]" maxlength="255" id="BetPartName" value="1-3" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][8][name]" maxlength="255" id="BetPartName" value="1-3">
            <input class="input-big" type="hidden" name="data[BetPart][8][line]" maxlength="255" id="BetPartName" value="1-3">
            <input class="input-big" type="hidden" name="data[BetPart][8][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][8][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][9][name]" maxlength="255" id="BetPartName" value="1-4" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][9][name]" maxlength="255" id="BetPartName" value="1-4">
            <input class="input-big" type="hidden" name="data[BetPart][9][line]" maxlength="255" id="BetPartName" value="1-4">
            <input class="input-big" type="hidden" name="data[BetPart][9][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][9][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][10][name]" maxlength="255" id="BetPartName" value="2-0" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][10][name]" maxlength="255" id="BetPartName" value="2-0">
            <input class="input-big" type="hidden" name="data[BetPart][10][line]" maxlength="255" id="BetPartName" value="2-0">
            <input class="input-big" type="hidden" name="data[BetPart][10][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][10][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][11][name]" maxlength="255" id="BetPartName" value="2-1" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][11][name]" maxlength="255" id="BetPartName" value="2-1">
            <input class="input-big" type="hidden" name="data[BetPart][11][line]" maxlength="255" id="BetPartName" value="2-1">
            <input class="input-big" type="hidden" name="data[BetPart][11][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][11][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][12][name]" maxlength="255" id="BetPartName" value="2-2" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][12][name]" maxlength="255" id="BetPartName" value="2-2">
            <input class="input-big" type="hidden" name="data[BetPart][12][line]" maxlength="255" id="BetPartName" value="2-2">
            <input class="input-big" type="hidden" name="data[BetPart][12][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][12][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][13][name]" maxlength="255" id="BetPartName" value="2-3" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][13][name]" maxlength="255" id="BetPartName" value="2-3">
            <input class="input-big" type="hidden" name="data[BetPart][13][line]" maxlength="255" id="BetPartName" value="2-3">
            <input class="input-big" type="hidden" name="data[BetPart][13][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][13][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][14][name]" maxlength="255" id="BetPartName" value="2-4" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][14][name]" maxlength="255" id="BetPartName" value="2-4">
            <input class="input-big" type="hidden" name="data[BetPart][14][line]" maxlength="255" id="BetPartName" value="2-4">
            <input class="input-big" type="hidden" name="data[BetPart][14][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][14][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][15][name]" maxlength="255" id="BetPartName" value="3-0" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][15][name]" maxlength="255" id="BetPartName" value="3-0">
            <input class="input-big" type="hidden" name="data[BetPart][15][line]" maxlength="255" id="BetPartName" value="3-0">
            <input class="input-big" type="hidden" name="data[BetPart][15][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][15][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][16][name]" maxlength="255" id="BetPartName" value="3-1" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][16][name]" maxlength="255" id="BetPartName" value="3-1">
            <input class="input-big" type="hidden" name="data[BetPart][16][line]" maxlength="255" id="BetPartName" value="3-1">
            <input class="input-big" type="hidden" name="data[BetPart][16][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][16][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][17][name]" maxlength="255" id="BetPartName" value="3-2" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][17][name]" maxlength="255" id="BetPartName" value="3-2">
            <input class="input-big" type="hidden" name="data[BetPart][17][line]" maxlength="255" id="BetPartName" value="3-2">
            <input class="input-big" type="hidden" name="data[BetPart][17][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][17][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][18][name]" maxlength="255" id="BetPartName" value="3-3" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][18][name]" maxlength="255" id="BetPartName" value="3-3">
            <input class="input-big" type="hidden" name="data[BetPart][18][line]" maxlength="255" id="BetPartName" value="3-3">
            <input class="input-big" type="hidden" name="data[BetPart][18][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][18][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][19][name]" maxlength="255" id="BetPartName" value="3-4" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][19][name]" maxlength="255" id="BetPartName" value="3-4">
            <input class="input-big" type="hidden" name="data[BetPart][19][line]" maxlength="255" id="BetPartName" value="3-4">
            <input class="input-big" type="hidden" name="data[BetPart][19][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][19][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][20][name]" maxlength="255" id="BetPartName" value="4-0" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][20][name]" maxlength="255" id="BetPartName" value="4-0">
            <input class="input-big" type="hidden" name="data[BetPart][20][line]" maxlength="255" id="BetPartName" value="4-0">
            <input class="input-big" type="hidden" name="data[BetPart][20][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][20][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][21][name]" maxlength="255" id="BetPartName" value="4-1" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][21][name]" maxlength="255" id="BetPartName" value="4-1">
            <input class="input-big" type="hidden" name="data[BetPart][21][line]" maxlength="255" id="BetPartName" value="4-1">
            <input class="input-big" type="hidden" name="data[BetPart][21][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][21][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][22][name]" maxlength="255" id="BetPartName" value="4-2" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][22][name]" maxlength="255" id="BetPartName" value="4-2">
            <input class="input-big" type="hidden" name="data[BetPart][22][line]" maxlength="255" id="BetPartName" value="4-2">
            <input class="input-big" type="hidden" name="data[BetPart][22][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][22][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][23][name]" maxlength="255" id="BetPartName" value="4-3" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][23][name]" maxlength="255" id="BetPartName" value="4-3">
            <input class="input-big" type="hidden" name="data[BetPart][23][line]" maxlength="255" id="BetPartName" value="4-3">
            <input class="input-big" type="hidden" name="data[BetPart][23][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][23][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][24][name]" maxlength="255" id="BetPartName" value="4-4" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][24][name]" maxlength="255" id="BetPartName" value="4-4">
            <input class="input-big" type="hidden" name="data[BetPart][24][line]" maxlength="255" id="BetPartName" value="4-4">
            <input class="input-big" type="hidden" name="data[BetPart][24][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][24][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    <tr>
        <td class="">
            <input class="input-big" type="text" name="data[BetPart][25][name]" maxlength="255" id="BetPartName" value="Any other score" disabled="disabled">
            <input class="input-big" type="hidden" name="data[BetPart][25][name]" maxlength="255" id="BetPartName" value="Any other score">
            <input class="input-big" type="hidden" name="data[BetPart][25][line]" maxlength="255" id="BetPartName" value="Any other score">
            <input class="input-big" type="hidden" name="data[BetPart][25][order_id]" maxlength="255" id="BetPartName" value="1">
        </td>
        <td class="">
            <input name="data[BetPart][25][odd]" type="text" maxlength="255" id="BetPartOdd">
        </td>
    </tr>


    </tbody></table>
    <div class="submit"><input class="btn" style="margin-top: 15px;" type="submit" value="Create"></div>
<?php echo $this->MyForm->end(); ?>