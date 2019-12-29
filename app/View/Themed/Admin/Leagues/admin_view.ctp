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
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <h2><?php echo $model['League']['name'] . ' ' . __('Events', true); ?></h2>
                                        <?php if (!empty($data)): ?>

                                            <table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                                                    <th><?php echo $this->Paginator->sort('name'); ?></th>
                                                    <th><?php echo $this->Paginator->sort('start_date'); ?></th>
                                                    <th><?php echo $this->Paginator->sort('active'); ?></th>
                                                    <th><?php echo __('Actions'); ?> </th>
                                                </tr>

                                                <?php
                                                $i = 1;
                                                foreach ($data as $row):
                                                    $class = null;
                                                    if ($i++ % 2 == 0) {
                                                        $class = ' alt';
                                                    }
                                                    ?>
                                                    <tr <?php if(strtotime($row['Event']['date']) < time()):?>style="background-color: #f5f5f5;"<?php endif;?>>
                                                        <td class="<?php echo $class; ?>"><?php echo $row['Event']['id']; ?></td>
                                                        <td class="<?php echo $class; ?>">
                                                            <?php echo $this->MyHtml->link($row['Event']['name'], array('language' => $this->language->getLanguage(), 'plugin' => 'events', 'controller' => 'events', 'action' => 'admin_view', $row['Event']['id'])); ?>
                                                        </td>
                                                        <td class="<?php echo $class; ?>"><?php echo $row['Event']['date']; ?></td>
                                                        <td class="<?php echo $class; ?>">
                                                            <?php if($row['Event']['active'] == 1): ?>
                                                                <?php echo __('Yes'); ?>
                                                            <?php else: ?>
                                                                <?php echo __('No'); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="actions <?php echo $class; ?>">
                                                            <?php echo $this->MyHtml->link(__('Edit', true), array('language' => $this->language->getLanguage(), 'plugin' => 'events', 'controller' => 'events', 'action' => 'admin_edit', $row['Event']['id']), array('class' => 'btn btn-mini btn-mini')); ?>
                                                            <?php echo $this->MyHtml->link(__('Delete', true), array('language' => $this->language->getLanguage(), 'plugin' => 'events', 'controller' => 'events', 'action' => 'admin_delete', $row['Event']['id']), array('class' => 'btn btn-mini btn-primary')); ?>
                                                        </td>
                                                    </tr>

                                                <?php endforeach; ?>
                                            </table>

                                            <?php echo $this->element('paginator'); ?>

                                        <?php else: ?>

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

