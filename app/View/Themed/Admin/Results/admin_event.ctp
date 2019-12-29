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
                                            <h5><?php echo $model['Sport']['name']; ?> - <?php echo $model['League']['name']; ?></h5>
                                            <h2><?php echo $model['Event']['name']; ?> <?php echo __('Results'); ?></h2>

                                            <?php echo $this->MyForm->create('Result', array('url' => array($this->params['pass'][0]))); ?>

                                            <?php echo $this->MyForm->input('Result', array('label' => __('Result', true), 'value' => $model['Event']['result'], 'name' => 'data[Event][result]', 'type' => 'text', 'class' => 'input-short')); ?>
                                            <?php echo $this->MyForm->input('Result', array('value' => $model['Event']['id'], 'name' => 'data[Event][id]', 'type' => 'hidden')); ?>
                                            <?php foreach ($data as $bet): ?>
                                                <?php
                                                if ($bet['Bet']['pick'] == 'asd')
                                                    continue;
                                                ?>
                                                <h4><?php echo $bet['Bet']['name']; ?> <?php if(in_array(strtolower($bet['Bet']['type']), array('under/over'))): ?><?php echo $bet['BetPart'][0]["line"]; ?><?php endif;?></h4>
                                                <table class="table table-custom"  cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <th><?php echo __('Pick'); ?></th>
                                                    </tr>

                                                    <?php
                                                    $i = 1;
                                                    foreach ($bet['BetPart'] as $betPart):
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
                                                            <td class="">
                                                                <?php echo $this->MyForm->input($betPart['id'], array('value' => 1, 'type' => 'checkbox', 'label' => $betPart['name'], 'checked' => $selected, 'class' => 'checkbox')); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            <?php endforeach; ?>
                                            <?php echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn btn-info')); ?>
                                            <?php echo $this->MyForm->end(); ?>

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