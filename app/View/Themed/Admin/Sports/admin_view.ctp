<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Sports'), 2 => __('List %s', __('Leagues')))))); ?>
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
                                        <?php echo $this->MyHtml->link(__('Add League', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'leagues', 'action' => 'admin_add', $model['Sport']['id']), array('class' => 'btn btn-danger')); ?>
                                        <h2><?php echo $model['Sport']['name'] . ' ' . __('Leagues', true); ?></h2>
                                        <?php if (!empty($data)): ?>

                                            <table class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th><?php echo $this->Paginator->sort('id'); ?></th>
<!--                                                    <th>--><?php //echo $this->Paginator->sort('country'); ?><!--</th>-->
                                                    <th><?php echo $this->Paginator->sort('name'); ?></th>
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
                                                    <tr>
                                                        <td class="<?php echo $class; ?>"><?php echo $row['League']['id']; ?></td>
<!--                                                        <td class="--><?php //echo $class; ?><!--">--><?php //echo $countries[$row['Country']['id']]["Country"]["name"]; ?><!--</td>-->
                                                        <td class="<?php echo $class; ?>">
                                                            <a href="/<?=$this->language->getLanguage()?>/admin/leagues/view/<?=$row['League']['id']?>"><?php echo $row['League']['name']; ?></a>
                                                        </td>
                                                        <td class="<?php echo $class; ?>">
                                                            <?php if($row['League']['active'] == 1): ?>
                                                                <?php echo __('Yes'); ?>
                                                            <?php else: ?>
                                                                <?php echo __('No'); ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="actions <?php echo $class; ?>">
                                                            <?php echo $this->MyHtml->link(__('Create event', true), array('language' => $this->language->getLanguage(), 'plugin' => 'events', 'controller' => 'events', 'action' => 'admin_add', $row['Sport']['id'], $row['League']['id']), array('class' => 'btn btn-mini btn-mini')); ?>
                                                            <?php echo $this->MyHtml->link(__('View', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'leagues', 'action' => 'admin_view', $row['League']['id']), array('class' => 'btn btn-mini btn-mini')); ?>
                                                            <?php echo $this->MyHtml->link(__('Edit', true), array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'leagues', 'action' => 'admin_edit', $row['League']['id']), array('class' => 'btn btn-mini btn-primary')); ?>
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

