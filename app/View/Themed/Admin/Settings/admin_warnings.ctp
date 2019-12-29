<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Warnings %s', $this->Admin->getPluralName()))))); ?>
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
                        <?php echo $this->element('search');?>
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
                                        echo $this->MyForm->create('Setting', $options);
                                        $yesNoOptions = array('1' => __('Yes'), '0' => __('No'));
                                        $timezones = $this->TimeZone->getTimeZones();
                                        ?>

                                        <br>

                                        <?php __('Warning settings would help you to filter warning screen with unnecessary alerts. Please consider what is crucial for you sportsbook and use this function to secure your profit.'); ?>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Deposit alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bigDeposit']['id'], array('value' => $data['bigDeposit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('System will alert once user deposited same or higher amount of money his/her account.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Withdraw alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bigWithdraw']['id'], array('value' => $data['bigWithdraw']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('System will alert once user ask for withdraw same or higher amount of money from his/her account.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Stake alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bigStake']['id'], array('value' => $data['bigStake']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('System will alert once user stake a ticket would be with same or higher amount of money.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Odds alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bigOdd']['id'], array('value' => $data['bigOdd']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('System will alert once odd will reach or would be higher then set in warning settings'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Won amount alert'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bigWinning']['id'], array('value' => $data['bigWinning']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('System will alert staff once user will won ticket with same or higher amount.'); ?></span></td>
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