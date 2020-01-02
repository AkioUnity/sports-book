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
                                                <td><?php echo __('Win factor (%)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_win_occurence']['id'], array('value' => $data['greyhound_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Lowers probability to win (100% - fair random play).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_min_bet']['id'], array('value' => $data['greyhound_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum possible bet.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_max_bet']['id'], array('value' => $data['greyhound_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum possible bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Greyhound names'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_names']['id'], array('value' => $data['greyhound_names']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Names of dogs from first to sixth (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Greyhound win odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_win']['id'], array('value' => $data['greyhound_odd_win']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Each dog winning odds from first to sixth (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Greyhound place odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_place']['id'], array('value' => $data['greyhound_odd_place']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Each dog place odds from first to sixth (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Greyhound show odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_show']['id'], array('value' => $data['greyhound_odd_show']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Each dog show odds from first to sixth (must have 6 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('First dog multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_1']['id'], array('value' => $data['greyhound_odd_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 1x2,1x3,1x4,1x5,1x6 (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Second dog multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_2']['id'], array('value' => $data['greyhound_odd_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 2x1,2x3,2x4,2x5,2x6 (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Third dog multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_3']['id'], array('value' => $data['greyhound_odd_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 3x1,3x2,3x4,3x5,3x6 (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Fourth dog multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_4']['id'], array('value' => $data['greyhound_odd_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 4x1,4x2,4x3,4x5,4x6 (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Fifth dog multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_5']['id'], array('value' => $data['greyhound_odd_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 5x1,5x2,5x3,5x4,5x6 (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Sixth dog multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_odd_6']['id'], array('value' => $data['greyhound_odd_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 6x1,6x2,6x3,6x4,6x5 (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_show_credits']['id'], array('value' => $data['greyhound_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_fullscreen']['id'], array('value' => $data['greyhound_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['greyhound_check_orientation']['id'], array('value' => $data['greyhound_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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