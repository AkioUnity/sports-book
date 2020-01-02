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
                                                <td><?php echo $this->MyForm->input($data['high_low_win_occurence']['id'], array('value' => $data['high_low_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurrence percentage (100 = always win).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Max possible winings'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_max_possible_winings']['id'], array('value' => $data['high_low_max_possible_winings']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum possible winnings on single game.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Max possible wining bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_auto_lose_threshold']['id'], array('value' => $data['high_low_auto_lose_threshold']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum bet amount for player to automatically lose.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('First fiche value'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_fiches_value_1']['id'], array('value' => $data['high_low_fiches_value_1']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Value of the first fiche in-game.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Second fiche value'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_fiches_value_2']['id'], array('value' => $data['high_low_fiches_value_2']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Value of the second fiche in-game.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Third fiche value'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_fiches_value_3']['id'], array('value' => $data['high_low_fiches_value_3']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Value of the third fiche in-game.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fourth fiche value'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_fiches_value_4']['id'], array('value' => $data['high_low_fiches_value_4']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Value of the fourth fiche in-game.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fifth fiche value'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_fiches_value_5']['id'], array('value' => $data['high_low_fiches_value_5']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Value of the fifth fiche in-game.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Card turn speed (ms)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_card_turn_speed']['id'], array('value' => $data['high_low_card_turn_speed']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Time speed to completely turn a card (in ms)'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Win/loose text speed (ms)'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_show_text_speed']['id'], array('value' => $data['high_low_show_text_speed']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Time speed duration of win/lose text (in ms).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show game credits'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_show_credits']['id'], array('value' => $data['high_low_show_credits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this value to 0 if you do not want to show credits button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_fullscreen']['id'], array('value' => $data['high_low_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['high_low_check_orientation']['id'], array('value' => $data['high_low_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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