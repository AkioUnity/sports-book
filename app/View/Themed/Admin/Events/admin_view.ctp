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
                <!-- BEGIN INLINE TABS PORTLET  admin_view.ctp-->
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php if (!empty($event)): ?>
                                            <h2><?php echo $event['Event']['import_id']; ?> <?php echo $event['Event']['name']; ?></h2>

                                            <button type='button' class='btn' style='float: left; margin-right: 15px;' onclick="window.location.href='<?=$referer;?>'"><?=__("Go Back");?></button>
                                            <?php echo $this->MyHtml->link(__("Add betting types to event"), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_add', $event['Event']["id"], 0), array('class' => isset($action['class']) ? $action['class'] : 'btn btn-danger', 'aco' => false)); ?>

                                            <?php if (!empty($data)): ?>

                                                <?php foreach ($data as $bet): ?>

                                                    <h3><?php echo __('ID: %d Betting type: %s', $bet['Bet']['id'], $bet['Bet']['name']); ?></h3>


                                                    <table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <th><?php echo __('Name'); ?></th>
                                                            <th><?php echo __('Odd'); ?></th>
                                                            <th><?php echo __('Tickets'); ?></th>
                                                            <th><?php echo __('Staked'); ?></th>
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
                                                                <td class="<?php echo $class; ?>"><?php echo $betPart['odd']; ?></td>
                                                                <td class="<?php echo $class; ?>"><?php echo $betPart['tickets_count']; ?></td>
                                                                <td <?php if($betPart['tickets_stake'] > 0):?>style="color:red;" <?php endif;?> class="<?php echo $class; ?>"><?php echo $betPart['tickets_stake']; ?><?php echo Configure::read('Settings.currency'); ?></td>

                                                            </tr>

                                                        <?php endforeach; ?>
                                                    </table>
                                                    <?php echo $this->MyHtml->link(__('Edit', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_edit', $bet['Bet']['id']), array('class' => 'btn btn-primary', 'style' => 'margin-top: 15px;')); ?>
                                                    <?php echo $this->MyHtml->link(__('Delete', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_delete', $bet['Bet']['id']), array('class' => 'btn btn-danger', 'style' => 'margin-top: 15px;')); ?>

                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php echo __('Can not find event'); ?>
                                        <?php endif; ?>
                                    </div>
                                    <button type='button' class='btn' style='float: left; margin-right: 15px; margin-top: 15px;' onclick="window.location.href='<?=$referer;?>'"><?=__("Go Back");?></button>
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

