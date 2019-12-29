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
                                            <table class="table table-custom"  cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th>
                                                        <?php echo $this->Paginator->sort('id'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->Paginator->sort('name'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->Paginator->sort('result'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo $this->Paginator->sort('date'); ?>
                                                    </th>
                                                    <th>
                                                        <?php echo __('Actions'); ?>
                                                    </th>
                                                </tr>
                                                <?php
                                                $i = 1;
                                                foreach ($data as $field):
                                                    $class = null;
                                                    if ($i++ % 2 == 0) {
                                                        $class = ' alt';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <?php
                                                        echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['id'] . "</td>";
                                                        $t = $this->MyHtml->link($field[$model]['name'], array('action' => $action, $field[$model]['id']));
                                                        echo "<td class=\"{$class}\">\n\t\t\t" . $t . "</td>";
                                                        echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['result'] . "</td>";
                                                        echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['date'] . "</td>";
                                                        ?>
                                                        <td class="<?php echo $class; ?>">
                                                            <?php echo $this->MyHtml->link(__('Cancel', true), array('action' => 'admin_cancel', $field[$model]['id']), null, __('Are you sure you want to cancel this event?', true)); ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endforeach;
                                                ?>

                                            </table>
                                            <?php echo $this->element('paginator'); ?>
                                        <?php else: ?>
                                            <p><?php echo __('No events in this league'); ?></p>
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