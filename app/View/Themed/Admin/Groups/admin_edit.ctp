<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Edit %s', $this->Admin->getSingularName()))))); ?>
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

                                        <div id="groups">
                                            <?php echo $this->element('flash_message'); ?>
                                            <?php if (!empty($acos)): ?>
                                                <?php echo $this->MyForm->create('Group', array('url' => array('action' => 'admin_edit', $id))); ?>
                                                <table>
                                                    <?php foreach ($acos as $aco): ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $this->MyForm->input($aco['Aco']['id'], array('type' => 'checkbox', 'label' => $aco['Aco']['alias'])); ?>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty($aco['childs'])): ?>
                                                                    <a href="#" onClick="showActions(<?php echo $aco['Aco']['id']; ?>); return false">actions</a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <?php if (!empty($aco['childs'])): ?>
                                                            <tr id="actions_<?php echo $aco['Aco']['id']; ?>" class="hidden">
                                                                <td></td>
                                                                <td>
                                                                    <?php foreach ($aco['childs'] as $aco): ?>
                                                                        <?php echo $this->MyForm->input($aco['Aco']['id'], array('type' => 'checkbox', 'label' => $aco['Aco']['alias'])); ?>
                                                                        <br />
                                                                    <?php endforeach; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </table>
                                                <?php echo $this->MyForm->submit(__('Save', true)); ?>
                                                <?php echo $this->MyForm->end(); ?>
                                            <?php endif; ?>
                                        </div>
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
    function showActions(id) {
        var element = jQuery('#actions_' + id);

        element.toggle();

        if(element.hasClass('hidden')) {
            element.removeClass('hidden');
        }else{
            element.addClassName('hidden');
        }
    }
</script>