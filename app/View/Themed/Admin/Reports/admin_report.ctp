<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                <?php echo $this->Admin->getPluralName();?>
            </h3>
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
                                        <?php echo $this->element('reports_form'); ?>

                                        <?php if (!empty($data)): ?>
                                            <?php foreach ($data as $report): ?>
                                                <table class="table table-bordered table-striped">
                                                    <tr>
                                                        <?php foreach ($report['header'] as $title): ?>
                                                            <th><?php echo $title; ?></th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <?php foreach ($report['data'] as $row): ?>
                                                        <tr>
                                                            <?php foreach ($row as $field): ?>
                                                                <td><?php echo $field; ?></td>
                                                            <?php endforeach; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>

                                            <?php endforeach; ?>

                                            <?php echo $this->MyForm->create('Download'); ?>
                                            <?php echo $this->MyForm->input('download', array('value' => '1', 'type' => 'hidden')); ?>
                                            <?php echo $this->MyForm->input('from', array('type' => 'hidden')); ?>
                                            <?php echo $this->MyForm->input('to', array('type' => 'hidden')); ?>
                                            <?php echo $this->MyForm->submit(__('Download', true), array('class' => 'btn btn-danger', 'div' => false)); ?>
                                            <?php echo $this->MyForm->end(); ?>

                                        <?php elseif (isset($data)): ?>
                                            <?php echo __('No data in this period'); ?>
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