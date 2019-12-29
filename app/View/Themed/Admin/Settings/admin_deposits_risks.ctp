<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Deposits %s', $this->Admin->getPluralName()))))); ?>
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

                                        <h4><?php echo __('Setup deposit settings to secure your sportbook profit and reduce risks.'); ?></h4>

                                        
                                        <br>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow automated deposits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['deposits']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['deposits']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow manual deposits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['D_Manual']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['D_Manual']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow bitcoin deposits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['deposits_bitcoin']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['deposits_bitcoin']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow NovinPal deposits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['deposits_novinpal']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['deposits_novinpal']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow Paypal deposits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['deposits_paypal']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['deposits_paypal']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow Skrill deposits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['deposits_skrill']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['deposits_skrill']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Lowest amount for deposit'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['minDeposit']['id'], array('value' => $data['minDeposit']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest amount for deposit'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['maxDeposit']['id'], array('value' => $data['maxDeposit']['value'])); ?></td>
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

