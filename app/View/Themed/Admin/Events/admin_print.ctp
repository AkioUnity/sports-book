<div class="container-fluid">
<!-- BEGIN PAGE HEADER-->
<div class="row-fluid">
    <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName(), 2 => __('%s Printing', $this->Admin->getPluralName()))))); ?>
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

                    <div class="control-group">
                        <label class="control-label"><?php echo __('From'); ?></label>
                        <div class="controls">
                            <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
                                <input id="from" name="data[Sport][from]" class=" m-ctrl-medium date-picker" size="16" type="text" data-date-format="yyyy-mm-dd" value="" />
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"><?php echo __('To'); ?></label>
                        <div class="controls">
                            <div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
                                <input id="to" name="data[Sport][to]" class=" m-ctrl-medium date-picker" size="16" type="text" data-date-format="yyyy-mm-dd" value="" />
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <?php echo $this->MyForm->input('Sport',
                        array(
                            'label'     =>  __('Please select sport below:'),
                            'type'      =>  'select',
                            'options'   =>  $Sports,
                            'id'        =>  'sportSelection'
                        )
                    ); ?>

                    <button type="button" id="getPrintEvents" class="btn"><?php echo __('View Events'); ?></button>
                </div>
            </div>
            <!-- END INLINE TABS PORTLET-->
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
</div>


<script type="text/javascript">
    $('#getPrintEvents').click(function(){
        window.open(
            window.location.href +  '/' + $('#sportSelection').val() + '/' + $('#from').val() + '/' + $('#to').val(),
            '_blank', // <- This is what makes it open in a new window.
            'width=800'
        );
    });
</script>