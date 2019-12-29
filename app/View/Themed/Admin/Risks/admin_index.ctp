<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Risk Management Settings'))))); ?>
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

                                    <div class="tab-content">

                                        <?php echo $this->element('flash_message'); ?>

                                        <h4><?php echo __('Setup risk management to settings to secure your sportbook profit and reduce risks.'); ?></h4></br>

                                        <?php
                                        $options = array(
                                            'url' => array(
                                                'language' => $this->language->getLanguage(),
                                                'plugin' => null,
                                                'controller' => 'risks'
                                            ),
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->MyForm->create('Setting', $options);
                                        ?>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td><?php echo __('Lock betting till event starts'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['betBeforeEventStartDate']['id'], array('value' => $settings['betBeforeEventStartDate']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lock betting for certain minutes till event start date. 0 stands for no lock."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Lowest stake'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lowest amount of money that user must have to place a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest stake'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest amount of money that user can use place a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest winning amount'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest amount of money that can be won in one ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Lowest number of events in one ticket'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['minBetsCount']['id'], array('value' => $settings['minBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lowest number of events that can be enetered into a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest number of events in one ticket'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxBetsCount']['id'], array('value' => $settings['maxBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest number of events that can be entered a ticket."); ?></span></td>
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