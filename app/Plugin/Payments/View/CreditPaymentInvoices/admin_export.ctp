<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName(), 2 => __('List %s', $this->Admin->getPluralName()))))); ?>
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
                        <!--BEGIN CHARTS -->
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <div class="hidden-phone">
                                            <?php

                                            if(!isset($search_fields) || !is_array($search_fields))
                                                return;

                                            echo $this->MyForm->create($model, array('type' => 'get', 'url' => array('plugin' => 'payments'), 'id' => 'search-form'));

                                            foreach($search_fields AS $i => $field)
                                            {
                                                if(!is_array($field)) {
                                                    $search_fields[$i] = array($field);
                                                }

                                                $class = isset($field['class']) ? $field['class'] : null;

                                                $search_fields[$i]['div'] = array('class' => 'search-inputs '. $class .'' );
                                                $search_fields[$i]['required'] = false;
                                            }

                                            echo $this->MyForm->inputs($search_fields, null, array('fieldset' => false, 'legend' => false));
                                            echo $this->MyForm->button(__('Export', true), array('type' => 'submit', 'id' => 'search_button', 'class' => 'btn'));
                                            echo $this->MyForm->end();
                                            ?>
                                        </div>
                                        <style type="text/css">
                                            form#search-form {
                                                position: relative;
                                                right: 5px;
                                            }

                                            form#search-form .search-inputs {
                                                float: left;
                                                padding-left: 5px;;

                                            }

                                            form#search-form #search_button {
                                                float: left;
                                                margin: 25px 0 10px 10px;
                                            }
                                        </style>
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