<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Create %s', $this->Admin->getSingularName()))))); ?>
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
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php if (!empty($data)): ?>

                                            <?php echo $this->MyForm->create('Result'); ?>

                                            <?php foreach ($data as $event): ?>

                                                <h2><?php echo $event['Event']['name']; ?> <?php echo __('Results'); ?></h2>
                                                <?php echo $this->MyForm->input('Result', array('label' => __('Result', true), 'value' => $event['Event']['result'], 'name' => "data[{$event['Event']['id']}][Event][result]", 'type' => 'text', 'class' => 'input-short')); ?>
                                                <?php echo $this->MyForm->input('Result', array('value' => $event['Event']['id'], 'name' => "data[{$event['Event']['id']}][Event][id]", 'type' => 'hidden')); ?>

                                                <?php foreach ($event['Bet'] as $bet): ?>
                                                    <h4><?php echo $bet['Bet']['name']; ?></h4>
                                                    <table class="table table-hover"  cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <th><?php echo __('Pick'); ?></th>
                                                        </tr>

                                                        <?php
                                                        $i = 1;
                                                        foreach ($bet['BetPart'] as $betPart):
                                                            $betPart = $betPart['BetPart'];
                                                            $class = null;
                                                            if ($i++ % 2 == 0) {
                                                                $class = ' alt';
                                                            }
                                                            //$attributes['value'] = false;
                                                            $selected = false;
                                                            if ($bet['Bet']['pick'] == $betPart['id']) {
                                                                $selected = true;
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td class="<?php echo $class; ?>"><?php echo $this->MyForm->input('Result_' . $betPart['id'], array('name' => "data[{$event['Event']['id']}][Result][{$betPart['id']}]", 'value' => 1, 'type' => 'checkbox', 'label' => $betPart['name'], 'checked' => $selected)); ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </table>
                                                <?php endforeach; ?>

                                            <?php endforeach; ?>
                                            <?php echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn btn-info')); ?>
                                            <?php echo $this->MyForm->end(); ?>

                                            <?php echo $this->element('paginator'); ?>

                                        <?php endif; ?>
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