<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('List Leagues'))))); ?>
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
                                    <div class="tab-content">

                                        <?php
                                        $options = array(
                                            'url' => array(
                                                'language' => $this->language->getLanguage(),
                                                'plugin' => null,
                                                'controller' => 'risks'
                                            ),
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->MyForm->create('League', $options);
                                        ?>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('League ID'); ?></th>
                                                <th><?php echo __('Sport'); ?></th>
                                                <th><?php echo __('League'); ?></th>
                                                <th><?php echo __('Lowest stake');?></th>
                                                <th><?php echo __('Highest stake'); ?></th>
                                            </tr>
                                            <?php foreach ($data as $row): ?>
                                                <tr>
                                                    <td><?php echo $row['League']['id']; ?></td>
                                                    <td><?php echo $sports[$row['Sport']['id']]; ?></td>
                                                    <td><?php echo $row['League']['name']; ?></td>

                                                    <td><input name="data[League][<?php echo $row['League']['id']; ?>][min_bet]" type="text" value="<?php if($row['League']['min_bet'] != 0): ?><?php echo $row['League']['min_bet']; ?><?php endif; ?>" placeholder="<?php echo __('No limits'); ?>" /></td>
                                                    <td><input name="data[League][<?php echo $row['League']['id']; ?>][max_bet]" type="text" value="<?php if($row['League']['max_bet'] != 0): ?><?php echo $row['League']['max_bet']; ?><?php endif; ?>" placeholder="<?php echo __('No limits'); ?>" /></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>

                                        <?php echo $this->element('paginator'); ?>

                                        <br />

                                        <?php echo $this->MyForm->submit(__('Save', true), array('class' => 'btn')); ?>

                                        <?php echo $this->MyForm->end(); ?>
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