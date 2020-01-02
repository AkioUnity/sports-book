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
                                                <td><?php echo $this->MyForm->input($data['bingo_win_occurence']['id'], array('value' => $data['bingo_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage, depending on numbers to extract (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Ball rolling speed'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bingo_time_extraction']['id'], array('value' => $data['bingo_time_extraction']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Speed in miliseconds of ball rolling.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 45 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bingo_paytable_45']['id'], array('value' => $data['bingo_paytable_45']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 45 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 55 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bingo_paytable_55']['id'], array('value' => $data['bingo_paytable_55']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 55 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Paytable for 65 balls'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bingo_paytable_65']['id'], array('value' => $data['bingo_paytable_65']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for 65 balls (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bingo_fullscreen']['id'], array('value' => $data['bingo_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bingo_check_orientation']['id'], array('value' => $data['bingo_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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