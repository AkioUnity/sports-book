<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Payment gateways'))))); ?>
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
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );

                                        echo $this->MyForm->create('Setting', $options);
                                        $yesNoOptions = array('1' => __('Yes'), '0' => __('No'));

                                        ?>

                                        <?php echo __('Please set payment gateways below:'); ?>

                                        <br>
                                        <br>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th width="300px"><?php echo __('Payments'); ?></th>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>


                                            <tr>
                                                <th style="text-align: center;" rowspan="2"><?php echo $this->Html->image('banks/etranzact.png', array('width' => '75px')); ?></th>
                                                <td><?php echo 'eTranzact'; ?></td>
                                                <td><?php echo $this->MyForm->input($data['eTranzactStatus']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['eTranzactStatus']['value'])); ?></td>
                                            </tr>
                                            <?php /*
                                            <tr>
                                                <td><?php echo __('UMF seller'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['D_UmfSeller']['id'], array('value' => $data['D_UmfSeller']['value'])); ?></td>
                                            </tr>
                                            */ ?>
                                        </table>
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