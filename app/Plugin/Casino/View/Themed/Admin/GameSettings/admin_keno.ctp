<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Game %s', $this->Admin->getPluralName()))))); ?>
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
                                        echo $this->MyForm->create('GameSetting', $options);
                                        $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                        $timezones = $this->TimeZone->getTimeZones();
                                        ?>

                                        <br>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Win occurence (%)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_win_occurence']['id'], array('value' => $data['keno_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage, starting with 2 balls (must have 9 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 2 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_2']['id'], array('value' => $data['keno_pays_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 2 balls (must have 2 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 3 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_3']['id'], array('value' => $data['keno_pays_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 3 balls (must have 2 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 4 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_4']['id'], array('value' => $data['keno_pays_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 4 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 5 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_5']['id'], array('value' => $data['keno_pays_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 5 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 6 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_6']['id'], array('value' => $data['keno_pays_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 6 balls (must have 4 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 7 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_7']['id'], array('value' => $data['keno_pays_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 7 balls (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 8 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_8']['id'], array('value' => $data['keno_pays_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 8 balls (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 9 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_9']['id'], array('value' => $data['keno_pays_9']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 9 balls (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 10 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_pays_10']['id'], array('value' => $data['keno_pays_10']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 10 balls (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 2 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_2']['id'], array('value' => $data['keno_occurence_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 2 balls (must have 2 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 3 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_3']['id'], array('value' => $data['keno_occurence_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 3 balls (must have 2 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 4 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_4']['id'], array('value' => $data['keno_occurence_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 4 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 5 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_5']['id'], array('value' => $data['keno_occurence_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 5 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 6 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_6']['id'], array('value' => $data['keno_occurence_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 6 balls (must have 4 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 7 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_7']['id'], array('value' => $data['keno_occurence_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 7 balls (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 8 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_8']['id'], array('value' => $data['keno_occurence_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 8 balls (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 9 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_9']['id'], array('value' => $data['keno_occurence_9']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 9 balls (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Occurence for 10 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_occurence_10']['id'], array('value' => $data['keno_occurence_10']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence for 10 balls (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_show_credits']['id'], array('value' => $data['keno_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_fullscreen']['id'], array('value' => $data['keno_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['keno_check_orientation']['id'], array('value' => $data['keno_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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