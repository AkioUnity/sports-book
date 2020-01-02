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
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_win_occurence']['id'], array('value' => $data['slot_soccer3d_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage. This is for 20 lines. With less lines, percentage decreases.'); ?></span></td>
                                            </tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_min_bet']['id'], array('value' => $data['slot_soccer3d_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_max_bet']['id'], array('value' => $data['slot_soccer3d_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Auto lose bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_auto_lose_threshold']['id'], array('value' => $data['slot_soccer3d_auto_lose_threshold']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('If bet is equal or lower than this value, make player automatically lose.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Bonus occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_occurence']['id'], array('value' => $data['slot_soccer3d_bonus_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Bonus occurence. This is for 5 lines. With less lines, percentage decreases.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st bonus prize occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_occurence_1']['id'], array('value' => $data['slot_soccer3d_bonus_occurence_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of 1st bonus prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd bonus prize occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_occurence_2']['id'], array('value' => $data['slot_soccer3d_bonus_occurence_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of 2nd bonus prize.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('3rd bonus prize occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_occurence_3']['id'], array('value' => $data['slot_soccer3d_bonus_occurence_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of 3rd bonus prize.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('4th bonus prize occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_occurence_4']['id'], array('value' => $data['slot_soccer3d_bonus_occurence_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of 4th bonus prize.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('5th bonus prize occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_occurence_5']['id'], array('value' => $data['slot_soccer3d_bonus_occurence_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Occurence of 5th bonus prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st bonus prize (multiplier)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_1']['id'], array('value' => $data['slot_soccer3d_bonus_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier of 1st prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd bonus prize (multiplier)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_2']['id'], array('value' => $data['slot_soccer3d_bonus_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier of 2nd prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('3rd bonus prize (multiplier)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_3']['id'], array('value' => $data['slot_soccer3d_bonus_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier of 3rd prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('4th bonus prize (multiplier)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_4']['id'], array('value' => $data['slot_soccer3d_bonus_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier of 4th prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('5th bonus prize (multiplier)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_bonus_5']['id'], array('value' => $data['slot_soccer3d_bonus_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Multiplier of 5th prize.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st free spin occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_freespin_occurence_1']['id'], array('value' => $data['slot_soccer3d_freespin_occurence_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('1st free spin occurence.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd free spin occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_freespin_occurence_2']['id'], array('value' => $data['slot_soccer3d_freespin_occurence_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('2nd free spin occurence.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('3rd free spin occurence'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_freespin_occurence_3']['id'], array('value' => $data['slot_soccer3d_freespin_occurence_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('3rd free spin occurence.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('1st amount of free spins'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_freespin_1']['id'], array('value' => $data['slot_soccer3d_freespin_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('1st prize amount of free spins.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('2nd amount of free spins'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_freespin_2']['id'], array('value' => $data['slot_soccer3d_freespin_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('2nd prize amount of free spins.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('3rd amount of free spins'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_freespin_3']['id'], array('value' => $data['slot_soccer3d_freespin_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('3rd prize amount of free spins.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('First symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_1']['id'], array('value' => $data['slot_soccer3d_paytable_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for first symbol (must have 5 values and be separated by comma).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Second symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_2']['id'], array('value' => $data['slot_soccer3d_paytable_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for second symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Third symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_3']['id'], array('value' => $data['slot_soccer3d_paytable_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for third symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fourth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_4']['id'], array('value' => $data['slot_soccer3d_paytable_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for fourth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fifth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_5']['id'], array('value' => $data['slot_soccer3d_paytable_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for fifth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Sixth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_6']['id'], array('value' => $data['slot_soccer3d_paytable_6']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for sixth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Seventh symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_7']['id'], array('value' => $data['slot_soccer3d_paytable_7']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for seventh symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Eight symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_8']['id'], array('value' => $data['slot_soccer3d_paytable_8']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for eight symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Ninth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_9']['id'], array('value' => $data['slot_soccer3d_paytable_9']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for ninth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Tenth symbol paytable'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_paytable_10']['id'], array('value' => $data['slot_soccer3d_paytable_10']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Paytable for tenth symbol (must have 5 values and be separated by comma)'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_show_credits']['id'], array('value' => $data['slot_soccer3d_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_fullscreen']['id'], array('value' => $data['slot_soccer3d_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['slot_soccer3d_check_orientation']['id'], array('value' => $data['slot_soccer3d_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
                                            </tr>
                                        </table>
                                        <br />
                                        <?php echo $this->MyForm->submit(__('Save', true), array('class' => 'btn')); ?>
                                        <?php echo $this->MyForm->end(); ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="soccer3d10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>