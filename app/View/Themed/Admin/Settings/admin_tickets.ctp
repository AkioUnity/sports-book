<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Tickets %s', $this->Admin->getPluralName()))))); ?>
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

                                        <h4><?php echo __('Setup tickets settings control frontend functionality.'); ?></h4>

                                        <br>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Ticket printing'); ?></td>
                                                <td>
                                                    <?php echo $this->MyForm->input($data['printing']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['printing']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) ticket printing for users and staff.'); ?></span>
                                                </td>
                                            </tr>
                                         <!---    <tr>
                                                <td><?php /*echo __('Ticket preview'); */?></td>
                                                <td>
                                                    <?php echo $this->MyForm->input($data['ticketPreview']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['ticketPreview']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) ticket preview ticket before it is placed.'); ?></span>
                                                </td>
                                            </tr> ---!>
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
