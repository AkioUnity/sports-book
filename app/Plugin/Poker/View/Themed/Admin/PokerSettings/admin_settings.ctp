<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Poker %s', $this->Admin->getPluralName()))))); ?>
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
                                        echo $this->MyForm->create('PokerSetting', $options);
                                        $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                        $onOffOptions = array('1' => 'On', '0' => 'Off');
                                        $kickOptions = array('3' => '3 mins', '5' => '5 mins', '7' => '7 mins', '10' => '10 mins');
                                        $moveOptions = array('10' => 'Turbo', '15' => 'Fast', '20' => 'Normal', '27' => 'Slow');
                                        $showdownOptions = array('3' => '3 secs', '5' => '5 secs', '7' => '7 secs', '10' => '10 secs');
                                        $sitOutOptions = array('0' => 'None', '10' => '10 secs', '15' => '15 secs', '20' => '20 secs', '25' => '25 secs');
                                        $disconnectOptions = array('15' => '15 secs', '30' => '30 secs', '60' => '60 secs', '90' => '90 secs', '120' => '120 secs');
                                        $timezones = $this->TimeZone->getTimeZones();
                                        ?>

                                        <br>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Browser Page Title'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['TITLE']['id'], array('value' => $data['TITLE']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('This title will appear in your web browsers page title.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('IP Check'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['IPCHECK']['id'], array('type' => 'select', 'options' => $onOffOptions, 'value' => $data['IPCHECK']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Prevent multiple players with identical IP addesses playing at the same table."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Kick Timer'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['KICKTIMER']['id'], array('type' => 'select', 'options' => $kickOptions, 'value' => $data['KICKTIMER']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Controls kicking players repeatedly failing to take their turn."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Move Timer'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['MOVETIMER']['id'], array('type' => 'select', 'options' => $moveOptions, 'value' => $data['MOVETIMER']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Controls the time a player has to make their move."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Showdown Timer'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['SHOWDOWN']['id'], array('type' => 'select', 'options' => $showdownOptions, 'value' => $data['SHOWDOWN']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Controls the time a showdown hand will be displayed for."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Sit Out Timer'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['WAITIMER']['id'], array('type' => 'select', 'options' => $sitOutOptions, 'value' => $data['WAITIMER']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Controls the length of stay on the sit out page."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Disconnect Timer'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['DISCONNECT']['id'], array('type' => 'select', 'options' => $disconnectOptions, 'value' => $data['DISCONNECT']['Xvalue'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Controls the time before kicking disconnected players."); ?></span></td>
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