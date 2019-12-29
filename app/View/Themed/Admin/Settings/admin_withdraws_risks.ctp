<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Withdraw %s', $this->Admin->getPluralName()))))); ?>
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

                                        <h4><?php echo __('Setup withdraw settings to secure your sportbook profit and reduce risks.'); ?></h4>

                                        <br>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Settings'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Lowest amount for withdraw request'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['minWithdraw']['id'], array('value' => $data['minWithdraw']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest amount for withdraw request'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['maxWithdraw']['id'], array('value' => $data['maxWithdraw']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow withdraws'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['withdraws']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['withdraws']['value'])); ?></td>
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



