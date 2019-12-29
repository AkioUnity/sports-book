<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Create %s', $this->Admin->getPluralName()))))); ?>
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
                                    <?php echo $this->element('tabs');?>
                                    <div class="tab-content">

                                        <?php echo $this->element('flash_message'); ?>

                                        <?php
                                        $options = array(
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->MyForm->create('PokerTable', $options);
                                        $typeOptions = array('s' => 'Sit \'n Go', 't' => 'Tournament');
                                        $minOptions = array('0' => '0.00 $', '100' => '1.00 $', '250' => '2.50 $', '500' => '5.00 $', '1000' => '10.00 $', '2500' => '25.00 $', '5000' => '50.00 $', '10000' => '100.00 $', '25000' => '250.00 $', '50000' => '500.00 $');
                                        $maxOptions = array('1000' => '10.00 $', '2500' => '25.00 $', '5000' => '50.00 $', '10000' => '100.00 $', '25000' => '250.00 $', '50000' => '500.00 $', '100000' => '1000.00 $');
                                        ?>

                                        <br>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Table Name'); ?></td>
                                                <td><?php echo $this->MyForm->input('name', array('value' => '')); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Name of the table.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Table Type'); ?></td>
                                                <td><?php echo $this->MyForm->input('type', array('type' => 'select', 'options' => $typeOptions)); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Choose between Sit 'n Go or Tournament.'"); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Minimum Buyin'); ?></td>
                                                <td><?php echo $this->MyForm->input('min', array('type' => 'select', 'options' => $minOptions)); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Minimum possible buyin (doesn't apply to tournament)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Maximum Buyin'); ?></td>
                                                <td><?php echo $this->MyForm->input('max', array('type' => 'select', 'options' => $maxOptions)); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum possible buyin."); ?></span></td>
                                            </tr>
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