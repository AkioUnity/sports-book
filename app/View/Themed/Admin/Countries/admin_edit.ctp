<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName(), 2 => __('List %s', $this->Admin->getPluralName()))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <div class="bets add">
                                            <?php echo $this->element('flash_message'); ?>
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
                                                <?php $i = 0; ?>
                                                <?php foreach ($data['BetPart'] as $betPart): ?>

                                                    <tr>
                                                        <td class="">
                                                            <input value="<?php echo $betPart['id']; ?>" type="hidden" name="data[BetPart][<?php echo $i; ?>][id]" />
                                                            <input value="<?php echo $betPart['name']; ?>" class="input-big" type="text" name="data[BetPart][<?php echo $i; ?>][name]" type="text" maxlength="255" />
                                                        </td>
                                                        <td class=""><input value="<?php echo $betPart['odd']; ?>" name="data[BetPart][<?php echo $i; ?>][odd]" type="text" maxlength="255" /></td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                <?php endforeach; ?>
                                            </table>
                                            <div class="actions">

                                            </div>
                                            <?php
                                            echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn'));
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
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>