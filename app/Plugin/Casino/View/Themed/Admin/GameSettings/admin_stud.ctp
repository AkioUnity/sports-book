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
                                                <td><?php echo $this->MyForm->input($data['stud_win_occurence']['id'], array('value' => $data['stud_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage.'); ?></span></td>
                                            </tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_min_bet']['id'], array('value' => $data['stud_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_max_bet']['id'], array('value' => $data['stud_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Auto lose bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_auto_lose_threshold']['id'], array('value' => $data['stud_auto_lose_threshold']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('If bet is equal or lower than this value, make player automatically lose.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Royal flush multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_royal_flush']['id'], array('value' => $data['stud_payout_royal_flush']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets royal flush (default is 100).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Straight flush multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_straight_flush']['id'], array('value' => $data['stud_payout_straight_flush']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets straight flush (default is 50).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Four of a kind multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_four_of_a_kind']['id'], array('value' => $data['stud_payout_four_of_a_kind']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets four of a kind (default is 20).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Full house multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_full_house']['id'], array('value' => $data['stud_payout_full_house']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets full house (default is 7).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Flush multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_flush']['id'], array('value' => $data['stud_payout_flush']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets flush (default is 5).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Straight multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_straight']['id'], array('value' => $data['stud_payout_straight']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets straight (default is 4).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('three of a kind multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_three_of_a_kind']['id'], array('value' => $data['stud_payout_three_of_a_kind']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets three of a kind (default is 3).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Two pair multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_two_pair']['id'], array('value' => $data['stud_payout_two_pair']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets two pair (default is 2).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('One pair or lower multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_payout_one_pair_or_less']['id'], array('value' => $data['stud_payout_one_pair_or_less']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when user gets one pair or lower (default is 1).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show hand time'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_time_show_hand']['id'], array('value' => $data['stud_time_show_hand']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Time (in milliseconds) of showing last hand.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_show_credits']['id'], array('value' => $data['stud_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_fullscreen']['id'], array('value' => $data['stud_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['stud_check_orientation']['id'], array('value' => $data['stud_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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