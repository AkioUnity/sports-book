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

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td><?php echo __('Lowest stake'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'] != "" ? $settings['minBet']['value'] : __("Unlimited"), 'label' => false, 'disabled' => 'disabled')); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lowest amount of money that user must have to place a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest stake'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'] != "" ? $settings['maxBet']['value'] : __("Unlimited"), 'label' => false, 'disabled' => 'disabled')); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest amount of money that user can use place a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest winning amount'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'] != "" ? $settings['maxWin']['value'] : __("Unlimited"), 'label' => false, 'disabled' => 'disabled')); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest amount of money that can be won in one ticket."); ?></span></td>
                                            </tr>
                                        </table>

                                        <br />
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