<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName())))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN SAMPLE TABLE widget-->
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-cogs"></i><?=__("Tickets");?></h4>
                        <span class="tools">
                        <a href="javascript:;" class="icon-chevron-down"></a>
                        <a href="javascript:;" class="icon-remove"></a>
                        </span>
                    </div>
                    <div class="widget-body">
                        <?php  echo $this->element('Dashboard/tickets');?>
                    </div>
                </div>
                <!-- END SAMPLE TABLE widget-->
            </div>
        </div>
    </div>
    <script type="text/javascript">

        function get_tickets(){
            var tickets = $.ajax({
                type: "GET",
                url: "<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => null, 'admin' => true,  'controller' => 'dashboard', 'action' => 'tickets', 'json')); ?>",
                async: false
            }).success(function(){
                setTimeout(function(){get_tickets();}, 5000);
            }).responseText;

            $('div.widget-body').html(tickets);
        }

        $(document).ready(function(){
            get_tickets();
        });
    </script>
<!-- END PAGE CONTENT-->
