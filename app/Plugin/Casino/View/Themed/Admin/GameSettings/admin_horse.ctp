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
                                                <td><?php echo $this->MyForm->input($data['horse_win_occurence']['id'], array('value' => $data['horse_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Lowers probability to win (100% - fair random play).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_min_bet']['id'], array('value' => $data['horse_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum possible bet.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_max_bet']['id'], array('value' => $data['horse_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum possible bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Horse names'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_names']['id'], array('value' => $data['horse_names']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Names of horses from first to eigth (must have 8 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Horse win odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_win']['id'], array('value' => $data['horse_odd_win']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Each horse winning odds from first to eigth (must have 8 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Horse place odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_place']['id'], array('value' => $data['horse_odd_place']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Each horse place odds from first to eigth (must have 8 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Horse show odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_show']['id'], array('value' => $data['horse_odd_show']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Each horse show odds from first to eigth (must have 8 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('First horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_1']['id'], array('value' => $data['horse_odd_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 1x2,1x3,1x4,1x5,1x6,1x7,1x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Second horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_2']['id'], array('value' => $data['horse_odd_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 2x1,2x3,2x4,2x5,2x6,2x7,2x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Third horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_3']['id'], array('value' => $data['horse_odd_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 3x1,3x2,3x4,3x5,3x6,3x7,3x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Fourth horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_4']['id'], array('value' => $data['horse_odd_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 4x1,4x2,4x3,4x5,4x6,4x7,4x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Fifth horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_5']['id'], array('value' => $data['horse_odd_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 5x1,5x2,5x3,5x4,5x6,5x7,5x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Sixth horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_6']['id'], array('value' => $data['horse_odd_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 6x1,6x2,6x3,6x4,6x5,6x7,6x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Seventh horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_7']['id'], array('value' => $data['horse_odd_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 7x1,7x2,7x3,7x4,7x5,7x6,7x8 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Eigth horse multi odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_odd_8']['id'], array('value' => $data['horse_odd_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('In format 8x1,8x2,8x3,8x4,8x5,8x6,8x7 (must have 7 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_show_credits']['id'], array('value' => $data['horse_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_fullscreen']['id'], array('value' => $data['horse_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['horse_check_orientation']['id'], array('value' => $data['horse_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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