<div class="container-fluid">
    <!-- Admin/Withdraw/admin_index.ctp-->
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName(), 2 => __('List %s', $this->Admin->getPluralName()))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <!--BEGIN CHARTS -->
                        <?php echo $this->element('charts/pie', array('placeholderClass' => 'withdraw-charts', 'chartsData' => $chartsData));?>
                        <!--BEGIN CHARTS -->
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                        <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <?php echo $this->element('list', array('title'));?>
                                    </div>
                                </div>
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<style type="text/css">
    .withdraw-charts {
        float: left;
        width: 300px;
    }

    #search-form {
        float: left;
        width: 600px;
        margin: 40px 0 0 95px;
    }

    #search-form .search-inputs:nth-child(5) {
        margin-left: 50px;
    }

    #search-form button[type="submit"]
    {
        clear: both;
        position: relative;
        left: 185px;
    }
</style>