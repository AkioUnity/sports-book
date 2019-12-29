<div class="bets add">
    <?php echo $this->element('flash_message'); ?>

    <h2><?php echo __('Add bet', true); ?></h2>
    <?php
    echo $this->MyForm->create('Bet', array('url' => array($this->params['pass'][0])));
    echo $this->MyForm->input('name');
    echo $this->MyForm->input('type');
    ?>

    <table id="table_liquid" class="picksTable" cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo __('Name'); ?></th>
            <th><?php echo __('Odd'); ?></th>
        </tr>
        <tr>
            <td class=""><input class="input-big" type="text" name="data[BetPart][0][name]" type="text" maxlength="255" id="BetPartName"></td>
            <td class=""><input name="data[BetPart][0][odd]" type="text" maxlength="255" id="BetPartOdd"></td>                    
        </tr>
        <tr>
            <td class=""><input class="input-big" type="text" name="data[BetPart][1][name]" type="text" maxlength="255" id="BetPartName"></td>
            <td class=""><input name="data[BetPart][1][odd]" type="text" maxlength="255" id="BetPartOdd"></td>                    
        </tr>
        <tr>
            <td colspan="2"><a href="" id="addPickButton">ADD</a></td>                
        </tr>
    </table>
    <?php
    echo $this->MyForm->submit(__('Submit', true), array('class' => 'button'));
    echo $this->MyForm->end();
    ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#addPickButton').bind('click', addPick);
    });
    var i = 2;
    function addPick() {
        var a = '<tr><td class=""><input class="input-big" type="text" name="data[BetPart]['+i+'][name]" type="text" maxlength="255" id="BetPartName"></td><td class=""><input name="data[BetPart]['+i+'][odd]" type="text" maxlength="255" id="BetPartOdd"></td>                            </tr>';        
        jQuery('.picksTable tr:last').before(a);
        i++;
        return false;
    }
</script>