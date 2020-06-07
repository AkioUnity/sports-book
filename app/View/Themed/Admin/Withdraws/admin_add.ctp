<div class="container-fluid">
    <!-- admin_add.ctp-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Charge'))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
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
                                    <h3><?php echo __('Charge user %s', '<b>'.$userName.'</b>'); ?></h3>
                                    <?php echo __('To charge enter amount and money will be reduced from user account. For example if you want to charge just credit user with 200 ') .   Configure::read('Settings.currency') . __(' USD just enter that amount into field and press submit'); ?>
                                    <br />
                                    <br />
                                    <div class="tab-content">
                                        <?php
                                        echo $this->MyForm->create('Withdraw', array('url' => array($user["User"]["id"])));
                                        echo $this->MyForm->input('amount');
                                        echo $this->MyForm->submit(__('Submit', true), array('class' => 'btn'));
                                        echo $this->MyForm->end();
                                        ?>
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