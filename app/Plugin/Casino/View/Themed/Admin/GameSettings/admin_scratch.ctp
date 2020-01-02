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
                                                <td><?php echo $this->MyForm->input($data['scratch_win_occurence']['id'], array('value' => $data['scratch_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Win in 1 row (%)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_win_percentage_1_rows']['id'], array('value' => $data['scratch_win_percentage_1_rows']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Percentage of winning in 1 row (default 70%).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Win in 2 rows (%)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_win_percentage_2_rows']['id'], array('value' => $data['scratch_win_percentage_2_rows']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Percentage of winning in 2 rows (default 25%).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Win in 3 rows (%)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_win_percentage_3_rows']['id'], array('value' => $data['scratch_win_percentage_3_rows']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Percentage of winning in 3 rows (default 5%).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st bet size'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_bet_1']['id'], array('value' => $data['scratch_bet_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('First bet size in the game'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd bet size'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_bet_2']['id'], array('value' => $data['scratch_bet_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Second bet size in the game'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('3rd bet size'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_bet_3']['id'], array('value' => $data['scratch_bet_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Third bet size in the game'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_1']['id'], array('value' => $data['scratch_prize_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for first combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_2']['id'], array('value' => $data['scratch_prize_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for second combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('3rd combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_3']['id'], array('value' => $data['scratch_prize_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for third combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('4th combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_4']['id'], array('value' => $data['scratch_prize_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for fourth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('5th combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_5']['id'], array('value' => $data['scratch_prize_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for fifth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('6th combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_6']['id'], array('value' => $data['scratch_prize_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for sixth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('7th combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_7']['id'], array('value' => $data['scratch_prize_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for seventh combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('8th combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_8']['id'], array('value' => $data['scratch_prize_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for eighth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('9th combo multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_9']['id'], array('value' => $data['scratch_prize_9']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier for ninth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_1']['id'], array('value' => $data['scratch_prize_probability_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with first combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_2']['id'], array('value' => $data['scratch_prize_probability_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with second combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('3rd prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_3']['id'], array('value' => $data['scratch_prize_probability_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with third combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('4th prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_4']['id'], array('value' => $data['scratch_prize_probability_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with fourth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('5th prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_5']['id'], array('value' => $data['scratch_prize_probability_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with fifth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('6th prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_6']['id'], array('value' => $data['scratch_prize_probability_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with sixth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('7th prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_7']['id'], array('value' => $data['scratch_prize_probability_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with seventh combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('8th prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_8']['id'], array('value' => $data['scratch_prize_probability_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with eighth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('9th prize probability'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_prize_probability_9']['id'], array('value' => $data['scratch_prize_probability_9']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Probability to win with ninth combo'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_show_credits']['id'], array('value' => $data['scratch_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_fullscreen']['id'], array('value' => $data['scratch_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['scratch_check_orientation']['id'], array('value' => $data['scratch_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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