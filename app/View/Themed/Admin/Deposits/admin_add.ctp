<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Fund %s', $this->Admin->getSingularName()))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <?php echo $this->element('flash_message'); ?>
    <div id="page" class="dashboard">
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php echo "<h3>" . __('Fund user') . ' <b>' . $user["User"]["username"] . "</b></h3>" ?>
                                    <?php echo __('To fund just enter amount into the input field and press submit. For example if you want to fund just credit user with 100 ') .   Configure::read('Settings.currency') . __(' just enter that amount into field and press submit'); ?>
                                    <br />
                                    <br />
                                    <div class="tab-content">
                                        <?php
                                        echo $this->MyForm->create(null, array('url' => array($user["User"]["id"])));
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