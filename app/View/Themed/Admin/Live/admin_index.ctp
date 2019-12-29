<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Edit %s', $this->Admin->getSingularName()))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">

        <?php echo $this->element('flash_message'); ?>
        <?php foreach ($data AS $leagues): ?>
            <?php foreach ($leagues["data"] AS $League): ?>
            <?php foreach ($League["Event"] AS $Event): ?>
                <form action="">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="widget">
                                <div class="widget-title">
                                    <h4><i class="icon-user"></i> <?=$sports[$League["sport_id"]];?> / <?=$League["name"];?> / <?=$Event["name"];?></h4>
                                    <span class="tools">
                                        <a href="javascript:;" class="icon-chevron-down"></a>
                                        </span>
                                </div>
                                <div class="widget-body">
                                    <div>
                                        <label for="suspend">
                                            <?php echo __("Suspend event"); ?>:
                                            <input name="suspend_event[]" data-id="<?=$Event["id"];?>" type="checkbox" <?php if($Event["active"] == Event::EVENT_INACTIVE_STATE):?>checked="checked"<?php endif;?> type="checkbox">
                                        </label>
                                        <div>
                                            <div style="float: left;"><?php echo __("Sport name: %s", $sports[$League["sport_id"]]); ?></div>
                                            <div style="float: right;"><?php echo __("Current time: %s min", round($Event["duration"] / 60)); ?></div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div>
                                            <div style="float: left;"><?php echo __("League name: %s", $League["name"]); ?></div>
                                            <div style="float: right;"><?php echo __("Current score: %s", $Event["result"]); ?></div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div><?php echo __("Event name: %s", $Event["name"]); ?></div>
                                        <div>
                                            <?php foreach ($Event["Bet"] as $bet): ?>

                                                <h3><?php echo __('ID: %d Betting type: %s', $bet["Bet"]['id'], $bet["Bet"]['type']); ?></h3>


                                                <table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <th><?php echo __('Name'); ?></th>
                                                        <th><?php echo __('Odd'); ?></th>
                                                        <th style="width: 50px;"><?php echo __('Suspend Odd'); ?></th>
                                                    </tr>

                                                    <?php
                                                    $i = 1;
                                                    foreach ($bet['BetPart'] as $betPart):
                                                        $class = null;
                                                        if ($i++ % 2 == 0) {
                                                            $class = ' alt';
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="<?php echo $class; ?>"><?php echo $betPart['name']; ?></td>
                                                            <td class="<?php echo $class; ?>">
                                                                <input name="update_odd[]" data-id="<?=$betPart["id"];?>" type="text" value="<?=$betPart['odd']; ?>">
                                                            </td>
                                                            <td class="<?php echo $class; ?>">
                                                                <input name="suspend_odd[]" data-id="<?=$betPart["id"];?>" type="checkbox" <?php if($betPart["suspended"] == 1):?>checked="checked"<?php endif;?>>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <!--<tr>
                                                        <td colspan="2" class="<?php /*echo $class;*/?>"><?php /*echo __("Suspend all");*/?></td>
                                                        <td class="<?php /*echo $class; */?>">
                                                            <input name="suspend_all[]" type="checkbox">
                                                        </td>
                                                    </tr>-->
                                                </table>
<!--                                                --><?php //break ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        //set initial state.
        var update = "/<?=Configure::read('Config.language');?>/admin/BetParts/update";

        $('input[name="suspend_event[]"]').change(function() {
            $.post( update, { "type": "suspend_event", Event : { id : $(this).attr('data-id'), value: $(this).is(':checked') } } );
        });

        $('input[name="suspend_odd[]"]').change(function() {
            $.post( update, { "type": "suspend", BetPart : { id : $(this).attr('data-id'), value: $(this).is(':checked') } } );
        });

        $(document.body).on('keyup', 'input[name="update_odd[]"]', function(){
            var input = $(this);
            delay(function() {
                $.post( update, { "type": "odd", BetPart : { id : input.attr('data-id'), value: input.val() } } );
                console.log(input.val(), input.attr('data-id'));
            }, 1000 );
        });

        var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();
    });
</script>