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
                                                <td><?php echo $this->MyForm->input($data['roulette_win_occurence']['id'], array('value' => $data['roulette_win_occurence']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Win occurrence percentage (100 = always win).'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Max possible winings'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_casino_cash']['id'], array('value' => $data['roulette_casino_cash']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum possible winnings on single game.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Max possible wining bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_auto_lose_threshold']['id'], array('value' => $data['roulette_auto_lose_threshold']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum bet amount for player to automatically lose.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Minimum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_min_bet']['id'], array('value' => $data['roulette_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Minimum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Maximum bet'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_max_bet']['id'], array('value' => $data['roulette_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Maximum allowed bet.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Bet time'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_time_bet']['id'], array('value' => $data['roulette_time_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Waiting time for player betting in ms (0 - no waiting time).'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Time to show win message'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_time_winner']['id'], array('value' => $data['roulette_time_winner']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Amount of time to show win message in ms.'); ?></span></td>
											<tr>
                                                <td><?php echo __('Fullscreen mode'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_fullscreen']['id'], array('value' => $data['roulette_fullscreen']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set this to 0 if you do not want to show fullscreen button.'); ?></span></td>
                                            </tr>
											<tr>
                                                <td><?php echo __('Orientation alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['roulette_check_orientation']['id'], array('value' => $data['roulette_check_orientation']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Set to 0 if you do not want to show orientation alert on mobile devices.'); ?></span></td>
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