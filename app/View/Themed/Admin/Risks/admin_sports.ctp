<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('List Sports'))))); ?>
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
                                        echo $this->MyForm->create('Sport', $options);
                                        ?>


                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Sport ID'); ?></th>
                                                <th><?php echo __('Sport'); ?></th>
                                                <th><?php echo __('Lowest stake');?></th>
                                                <th><?php echo __('Highest stake'); ?></th>
                                            </tr>
                                            <?php foreach ($data as $row): ?>
                                                <tr>
                                                    <td><?php echo $row['Sport']['id']; ?></td>
                                                    <td><?php echo $this->MyHtml->link($row['Sport']['name'], array('action' => 'admin_leagues', $row['Sport']['id'])); ?></td>
                                                    <td><input name="data[Sport][<?php echo $row['Sport']['id']; ?>][min_bet]" type="text" value="<?php if($row['Sport']['min_bet'] != 0): ?><?php echo $row['Sport']['min_bet']; ?><?php endif; ?>" placeholder="<?php echo __('No limits'); ?>" /></td>
                                                    <td><input name="data[Sport][<?php echo $row['Sport']['id']; ?>][max_bet]" type="text" value="<?php if($row['Sport']['max_bet'] != 0): ?><?php echo $row['Sport']['max_bet']; ?><?php endif; ?>" placeholder="<?php echo __('No limits'); ?>" /></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>

                                        <?php echo $this->element('paginator'); ?>

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