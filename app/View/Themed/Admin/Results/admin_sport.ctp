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
                                        <table class="table table-custom"  cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th>
                                                    <?php echo $this->Paginator->sort('id'); ?>
                                                </th>
                                                <th>
                                                    <?php echo $this->Paginator->sort('name'); ?>
                                                </th>
                                            </tr>

                                            <?php
                                            $i = 1;
                                            foreach ($data as $field):
                                                $class = null;
                                                if ($i++ % 2 == 0) {
                                                    $class = ' alt';
                                                }
                                                echo "<tr>";
                                                echo "<td class=\"{$class}\">\n\t\t\t" . $field[$model]['id'] . "</td>";
                                                $t = $this->MyHtml->link($field[$model]['name'], array('action' => $action, $field[$model]['id']));
                                                echo "<td class=\"{$class}\">\n\t\t\t" . $t . "</td>";
                                                echo "</tr>";
                                            endforeach;
                                            ?>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
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