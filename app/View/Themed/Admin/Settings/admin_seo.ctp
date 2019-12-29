<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('SEO %s', $this->Admin->getPluralName()))))); ?>
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

                                        <?php
                                        $options = array(
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );

                                        echo $this->MyForm->create('Setting', $options);
                                        ?>

                                        <?php echo __('What Is <b>SEO</b> / Search Engine Optimization?'); ?>

                                        <br><br>

                                        <?php echo __('SEO stands for "search engine optimization." It is the process of getting traffic from the "free," "organic," "editorial" or "natural" listings on search engines. All major search engines such as Google, Yahoo and Bing have such results, where web pages and other content such as videos or local listings are shown and ranked based on what the search engine considers most relevant to users. Payment is not involved, as it is with paid search ads.'); ?>

                                        <br><br>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>

                                            </tr>

                                            <tr>
                                                <td><?php echo __('Title tag'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['defaultTitle']['id'], array('value' => $data['defaultTitle']['value'])) ; ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("A title tag is the main text that describes an online document. It appears in three key places: browsers, search engine results pages, and external websites."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Keywords'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['metaKeywords']['id'], array('value' => $data['metaKeywords']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Keywords are words or phrases which desribes your website content. Use comma for separation."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Description'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['metaDescription']['id'], array('value' => $data['metaDescription']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("This description would appear in search engines."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Reply to email'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['metaReplayTo']['id'], array('value' => $data['metaReplayTo']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Email which would be visible in search engines as part of SEO outputs."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Copyright:'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['metaCopyright']['id'], array('value' => $data['metaCopyright']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Owner of product which would appear in SEO outputs."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Bot content revisit time'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['metaRevisitTime']['id'], array('value' => $data['metaRevisitTime']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Write how often search engine bot should revisit you for new content."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Author'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['metaAuthor']['id'], array('value' => $data['metaAuthor']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Name of product creator."); ?></span></td>
                                            </tr>
                                        </table>
                                        <br />
                                        <?php echo $this->MyForm->submit(__('Save', true), array('class' => 'btn')); ?>
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