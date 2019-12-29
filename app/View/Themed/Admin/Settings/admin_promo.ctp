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

                                    <div class="tab-content">

                                        <?php echo $this->element('flash_message'); ?>

                                        <?php
                                        $options = array(
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->MyForm->create('Setting', $options);
                                        $yesNoOptions = array('1' => __('Yes'), '0' => __('No'));
                                        ?>

                                        <h3><?php echo __("Left promotion sidebar"); ?></h3>
                                        <table class="table table-hover" style=" width: 65%">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Left active'); ?></td>
                                                <td>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="transition-value-toggle-button">
                                                                <?php echo $this->MyForm->input($data['left_promo_enabled']['id'], array('type' => 'checkbox', 'class' => 'toggle', 'checked' => $data['left_promo_enabled']['value'])); ?>
                                                            </div>
                                                            <span style="font-size: x-small; font-style: italic; padding-left: 10px; position: relative; bottom: 5px;"><?php echo __("Show (Yes) or hide (No) left promotion sidebar in front end."); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><?php echo __('Left sidebar title'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['left_promo_header']['id'], array('value' => $data['left_promo_header']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enter left sidebar title."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Left sidebar content'); ?></td>
                                                <td><?php echo $this->MyForm->textarea($data['left_promo_body']['id'], array('value' => $data['left_promo_body']['value'], 'id' => 'epic', 'class' => 'span12 ckeditor')); ?></td>
                                            </tr>

                                        </table>
                                        <br /><br />

                                        <h3><?php echo __("Right promotion sidebar"); ?></h3>
                                        <table class="items" style="width: 65%">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Right active'); ?></td>
                                                <td>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="transition-value-toggle-button">
                                                                <?php echo $this->MyForm->input($data['right_promo_enabled']['id'], array('type' => 'checkbox', 'class' => 'toggle', 'checked' => $data['right_promo_enabled']['value'])); ?>
                                                            </div>
                                                            <span style="font-size: x-small; font-style: italic; padding-left: 10px; position: relative; bottom: 5px;"><?php echo __("Show (Yes) or hide (No) right promotion sidebar in front end."); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Right sidebar title'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['right_promo_header']['id'], array('value' => $data['right_promo_header']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enter right sidebar title."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Right sidebar content'); ?></td>
                                                <td><?php echo $this->MyForm->textarea($data['right_promo_body']['id'], array('value' => $data['right_promo_body']['value'], 'class' => 'span12 ckeditor')); ?></td>
                                            </tr>
                                        </table>
                                        <br /><br />

                                        <h3><?php echo __("Bottom promotion sidebar"); ?></h3>
                                        <table class="items" style="width: 65%">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Bottom active'); ?></td>
                                                <td>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="transition-value-toggle-button">
                                                                <?php echo $this->MyForm->input($data['bottom_promo_enabled']['id'], array('type' => 'checkbox', 'class' => 'toggle', 'checked' => $data['bottom_promo_enabled']['value'])); ?>
                                                            </div>
                                                            <span style="font-size: x-small; font-style: italic; padding-left: 10px; position: relative; bottom: 5px;"><?php echo __("Show (Yes) or hide (No) bottom promotion sidebar in front end."); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            <tr>
                                                <td><?php echo __('Bottom sidebar title'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['bottom_promo_header']['id'], array('value' => $data['bottom_promo_header']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enter bottom sidebar title."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Bottom sidebar content'); ?></td>
                                                <td><?php echo $this->MyForm->textarea($data['bottom_promo_body']['id'], array('value' => $data['bottom_promo_body']['value'], 'class' => 'span12 ckeditor')); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?php echo $this->MyForm->submit(__('Save', true), array('class' => 'btn')); ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both;"></div>
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