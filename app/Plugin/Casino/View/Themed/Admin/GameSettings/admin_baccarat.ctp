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
                                                <td><?php echo $this->MyForm->input($data['baccarat_win_occurence']['id'], array('value' => $data['baccarat_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurence percentage. This influences TIE outcome just a little because of massive TIE multiplier.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_min_bet']['id'], array('value' => $data['baccarat_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_max_bet']['id'], array('value' => $data['baccarat_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Tie multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_multiplier_tie']['id'], array('value' => $data['baccarat_multiplier_tie']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when game is a tie (default is 8).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Banker multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_multiplier_banker']['id'], array('value' => $data['baccarat_multiplier_banker']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when game is won by banker (default is 1.95).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Player multiplier'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_multiplier_player']['id'], array('value' => $data['baccarat_multiplier_player']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win multiplier when game is won by player (default is 2).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Auto lose bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_auto_lose_threshold']['id'], array('value' => $data['baccarat_auto_lose_threshold']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('If bet is equal or lower than this value, make player automatically lose.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Show hand time'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_time_show_hand']['id'], array('value' => $data['baccarat_time_show_hand']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Time (in milliseconds) of showing last hand.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_fullscreen']['id'], array('value' => $data['baccarat_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['baccarat_check_orientation']['id'], array('value' => $data['baccarat_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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