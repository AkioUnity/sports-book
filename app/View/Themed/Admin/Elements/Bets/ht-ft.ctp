<?php echo $this->MyForm->create($model, array('url' => array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_add', $event_id, $bet_type))); ?>
        <div class="input text"><label for="BetBetName">Bet Name</label><input name="data[Bet][bet_name]" value="HT/FT" disabled="disabled" type="text" id="BetBetName"></div>                                                    <table id="table_liquid" class="picksTable" cellpadding="0" cellspacing="0">
            <tbody><tr>
                <th>Name</th>
                <th>Odd</th>
            </tr>
            <tr>
                <td>
                    <input class="input-big" type="hidden" name="data[Bet][name]" maxlength="255" id="BetPartName" value="HT/FT">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][0][name]" maxlength="255" id="BetPartName" value="1/1" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][0][name]" maxlength="255" id="BetPartName" value="1/1">
                    <input class="input-big" type="hidden" name="data[BetPart][0][line]" maxlength="255" id="BetPartName" value="1/1">
                    <input class="input-big" type="hidden" name="data[BetPart][0][order_id]" maxlength="255" id="BetPartName" value="1">
                </td>
                <td class="">
                    <input name="data[BetPart][0][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][1][name]" maxlength="255" id="BetPartName" value="1/2" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][1][name]" maxlength="255" id="BetPartName" value="1/2">
                    <input class="input-big" type="hidden" name="data[BetPart][1][line]" maxlength="255" id="BetPartName" value="1/2">
                    <input class="input-big" type="hidden" name="data[BetPart][1][order_id]" maxlength="255" id="BetPartName" value="6">
                </td>
                <td class="">
                    <input name="data[BetPart][1][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][2][name]" maxlength="255" id="BetPartName" value="1/X" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][2][name]" maxlength="255" id="BetPartName" value="1/X">
                    <input class="input-big" type="hidden" name="data[BetPart][2][line]" maxlength="255" id="BetPartName" value="1/X">
                    <input class="input-big" type="hidden" name="data[BetPart][2][order_id]" maxlength="255" id="BetPartName" value="4">
                </td>
                <td class="">
                    <input name="data[BetPart][2][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][3][name]" maxlength="255" id="BetPartName" value="2/1" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][3][name]" maxlength="255" id="BetPartName" value="2/1">
                    <input class="input-big" type="hidden" name="data[BetPart][3][line]" maxlength="255" id="BetPartName" value="2/1">
                    <input class="input-big" type="hidden" name="data[BetPart][3][order_id]" maxlength="255" id="BetPartName" value="2">
                </td>
                <td class="">
                    <input name="data[BetPart][3][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][4][name]" maxlength="255" id="BetPartName" value="2/2" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][4][name]" maxlength="255" id="BetPartName" value="2/2">
                    <input class="input-big" type="hidden" name="data[BetPart][4][line]" maxlength="255" id="BetPartName" value="2/2">
                    <input class="input-big" type="hidden" name="data[BetPart][4][order_id]" maxlength="255" id="BetPartName" value="8">
                </td>
                <td class="">
                    <input name="data[BetPart][4][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][5][name]" maxlength="255" id="BetPartName" value="2/X" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][5][name]" maxlength="255" id="BetPartName" value="2/X">
                    <input class="input-big" type="hidden" name="data[BetPart][5][line]" maxlength="255" id="BetPartName" value="2/X">
                    <input class="input-big" type="hidden" name="data[BetPart][5][order_id]" maxlength="255" id="BetPartName" value="5">
                </td>
                <td class="">
                    <input name="data[BetPart][5][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][6][name]" maxlength="255" id="BetPartName" value="X/1" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][6][name]" maxlength="255" id="BetPartName" value="X/1">
                    <input class="input-big" type="hidden" name="data[BetPart][6][line]" maxlength="255" id="BetPartName" value="X/1">
                    <input class="input-big" type="hidden" name="data[BetPart][6][order_id]" maxlength="255" id="BetPartName" value="3">
                </td>
                <td class="">
                    <input name="data[BetPart][6][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][7][name]" maxlength="255" id="BetPartName" value="X/2" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][7][name]" maxlength="255" id="BetPartName" value="X/2">
                    <input class="input-big" type="hidden" name="data[BetPart][7][line]" maxlength="255" id="BetPartName" value="X/2">
                    <input class="input-big" type="hidden" name="data[BetPart][7][order_id]" maxlength="255" id="BetPartName" value="7">
                </td>
                <td class="">
                    <input name="data[BetPart][7][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            <tr>
                <td class="">
                    <input class="input-big" type="text" name="data[BetPart][8][name]" maxlength="255" id="BetPartName" value="X/X" disabled="disabled">
                    <input class="input-big" type="hidden" name="data[BetPart][8][name]" maxlength="255" id="BetPartName" value="X/X">
                    <input class="input-big" type="hidden" name="data[BetPart][8][line]" maxlength="255" id="BetPartName" value="X/X">
                    <input class="input-big" type="hidden" name="data[BetPart][8][order_id]" maxlength="255" id="BetPartName" value="9">
                </td>
                <td class="">
                    <input name="data[BetPart][8][odd]" type="text" maxlength="255" id="BetPartOdd">
                </td>
            </tr>


            </tbody></table>
        <div class="submit"><input class="btn" style="margin-top: 15px;" type="submit" value="Create"></div>
<?php echo $this->MyForm->end(); ?>