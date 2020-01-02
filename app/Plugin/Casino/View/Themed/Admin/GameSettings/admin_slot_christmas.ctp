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
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_win_occurence']['id'], array('value' => $data['slot_christmas_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage. This is for 5 lines. With less lines, percentage decreases.'); ?></span></td>
                                            </tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_min_bet']['id'], array('value' => $data['slot_christmas_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_max_bet']['id'], array('value' => $data['slot_christmas_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Auto lose bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_auto_lose_threshold']['id'], array('value' => $data['slot_christmas_auto_lose_threshold']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('If bet is equal or lower than this value, make player automatically lose.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Bonus occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_occurence']['id'], array('value' => $data['slot_christmas_bonus_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Bonus occurence. This is for 5 lines. With less lines, percentage decreases.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Maximum hold reels'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_maximum_hold_reels']['id'], array('value' => $data['slot_christmas_maximum_hold_reels']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum possible number of reels to hold.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Lowest bonus occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_occurence_1']['id'], array('value' => $data['slot_christmas_bonus_occurence_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of lowest bonus.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Medium bonus occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_occurence_2']['id'], array('value' => $data['slot_christmas_bonus_occurence_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of medium bonus.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('High bonus occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_occurence_3']['id'], array('value' => $data['slot_christmas_bonus_occurence_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of high bonus.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Lowest bonus multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_1']['id'], array('value' => $data['slot_christmas_bonus_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Lowest bonus multiplier (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Medium bonus multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_2']['id'], array('value' => $data['slot_christmas_bonus_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Medium bonus multiplier (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('High bonus multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_bonus_3']['id'], array('value' => $data['slot_christmas_bonus_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('High bonus multiplier (must have 3 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('First symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_1']['id'], array('value' => $data['slot_christmas_paytable_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for first symbol (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Second symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_2']['id'], array('value' => $data['slot_christmas_paytable_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for second symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Third symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_3']['id'], array('value' => $data['slot_christmas_paytable_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for third symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fourth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_4']['id'], array('value' => $data['slot_christmas_paytable_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for fourth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fifth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_5']['id'], array('value' => $data['slot_christmas_paytable_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for fifth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Sixth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_6']['id'], array('value' => $data['slot_christmas_paytable_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for sixth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Seventh symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_7']['id'], array('value' => $data['slot_christmas_paytable_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for seventh symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Eight symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_paytable_8']['id'], array('value' => $data['slot_christmas_paytable_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for eight symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_show_credits']['id'], array('value' => $data['slot_christmas_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_fullscreen']['id'], array('value' => $data['slot_christmas_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_christmas_check_orientation']['id'], array('value' => $data['slot_christmas_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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