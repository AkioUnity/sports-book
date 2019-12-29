<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Add %s', $this->Admin->getSingularName()))))); ?>
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
                                        <?php echo $this->MyForm->input('event', array("value" => $event_id, 'disabled' => 'disabled')); ?>
                                        <?php  echo $this->MyForm->input('bet_type_select', array('options' => $bet_types, 'default' => $bet_type, 'type' => 'select', 'class' => 'inp11', 'style' => 'width: 320px;')); ?>
                                        <?php echo $this->element('Bets/' . $this->Beth->betToPath($bet_types[$bet_type]));?>
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
<script type="text/javascript">
    $('select[name="data[bet_type_select]"]').change(function(){
        window.location = "<?php echo $this->MyForm->url(array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'Bets', 'action' => 'admin_add', $event_id)); ?>/" + $(this).val();
    });
</script>