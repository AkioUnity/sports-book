<?php echo $this->MyForm->create($model, array('url' => array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_add', $event_id, $bet_type))); ?>

<?php echo $this->MyForm->input('bet_name', array("value" => $bet_types[$bet_type], 'disabled' => 'disabled')); ?>
    <table id="table_liquid" class="picksTable" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo __('Name'); ?></th>
            <th><?php echo __('Odd'); ?></th>
        </tr>
        <tr>
            <td>
                <input class="input-big" type="hidden" name="data[Bet][name]" type="text" maxlength="255" id="BetPartName" value="12">
            </td>
        </tr>
        <tr>
            <td class="">
                <input class="input-big" type="text" name="data[BetPart][0][name]" type="text" maxlength="255" id="BetPartName" value="1" disabled="disabled">
                <input class="input-big" type="hidden" name="data[BetPart][0][name]" type="text" maxlength="255" id="BetPartName" value="1">
                <input class="input-big" type="hidden" name="data[BetPart][0][line]" type="text" maxlength="255" id="BetPartName" value="1">
                <input class="input-big" type="hidden" name="data[BetPart][0][order_id]" type="text" maxlength="255" id="BetPartName" value="1">
            </td>
            <td class="">
                <input name="data[BetPart][0][odd]" type="text" maxlength="255" id="BetPartOdd">
            </td>
        </tr>
        <tr>
            <td class="">
                <input class="input-big" type="text" name="data[BetPart][1][name]" type="text" maxlength="255" id="BetPartName" value="2" disabled="disabled">
                <input class="input-big" type="hidden" name="data[BetPart][1][name]" type="text" maxlength="255" id="BetPartName"  value="2">
                <input class="input-big" type="hidden" name="data[BetPart][1][line]" type="text" maxlength="255" id="BetPartName"  value="2">
                <input class="input-big" type="hidden" name="data[BetPart][1][order_id]" type="text" maxlength="255" id="BetPartName"  value="2">
            </td>
            <td class="">
                <input name="data[BetPart][1][odd]" type="text" maxlength="255" id="BetPartOdd">
            </td>
        </tr>
    </table>
<?php echo $this->MyForm->submit(__('Create', true), array('class' => 'btn', 'style' => 'margin-top: 15px;')); ?>

<?php echo $this->MyForm->end(); ?>