<?php if(!isset($show) OR $show == false): ?>
    <div class="container-fluid">
        <!-- admin_operator_daily.ctp-->
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
                            <div class="row-fluid">
                                <div class="span12">
                                    <!--BEGIN TABS-->
                                    <div class="table table-custom">
                                        <div class="tab-content">
                                            <h2><?php echo __('Operator report'); ?></h2>
                                            <?php echo $this->element('reports_form', array('users' => array('label' => __('Please select operator(s)'), 'data' => $users))); ?>
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
<?php else: ?>
    <?php echo $this->element('operator_daily'); ?>
<?php endif; ?>