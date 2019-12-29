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
                                        <?php echo $this->element('flash_message'); ?>

                                        <?php
                                        $options = array(
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );

                                        echo $this->MyForm->create('Setting', $options);
                                        $yesNoOptions = array('1' => __('Yes'), '0' => __('No'));
                                        ?>

                                        <table class="items">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Deposit fee percentage'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['deposit_funding_percentage']['id'], array('value' => $data['deposit_funding_percentage']['value'])); ?>%</td>
                                            </tr>
                                        </table>

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