<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('General %s', $this->Admin->getPluralName()))))); ?>
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
                                        $yesNoOptions = array('1' => __('Yes'), '0' => __('No'));
                                        $feedTypes = array('nordicbet' => 'NordicBet', 'OddService' => 'OddService');
                                        $timezones = $this->TimeZone->getTimeZones();
                                        ?>

                                        <?php echo __('General settings controls some of the most basic configuration settings for your site: your site\'s title and location, who may register an account at your site, and how dates and times are calculated and displayed.'); ?>

                                        <br>
                                        <br>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Website name'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['websiteName']['id'], array('value' => $data['websiteName']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enter the name of your sportsbook here. Most themes will display this title, at the top of every page, and in the reader's browser titlebar."); ?> </span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Copyright'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['copyright']['id'], array('value' => $data['copyright']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Owner rights for product use."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Contact Email'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['contactMail']['id'], array( 'value' => $data['contactMail']['value']) ); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("All information through contact form will come to this email address."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Registration'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['registration']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['registration']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) registration on website."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Login'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['login']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['login']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) login function."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Password reset'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['passwordReset']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['passwordReset']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) password reset for users."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Default currency'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['defaultCurrency']['id'], array('type' => 'select', 'options' => $currencies, 'value' => $data['defaultCurrency']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Choose main currency on website. In order to add new please go to Settings -> Currencies."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Default timezone:'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['defaultTimezone']['id'], array('type' => 'select', 'options' => $timezones, 'value' => $data['defaultTimezone']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Select time zone for whole website."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Default language'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['defaultLanguage']['id'], array('type' => 'select', 'options' => $locales, 'value' => $data['defaultLanguage']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Select website main language. In order to add new language please contact ChalkPro support team."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Charset'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['charset']['id'], array('value' => $data['charset']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Charsets are identifiers used to describe a series of universal characters used in web and internet protocols such as HTML and Microsoft Windows. Default one is \"utf-8\"."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Items per page'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['itemsPerPage']['id'], array('value' => $data['itemsPerPage']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Number of maximum rows which will be displayed in one page (only in administration panel)"); ?></td>
                                            </tr>
<!--                                            <tr>-->
<!--                                                <td>--><?php //echo __('Referral system'); ?><!--</td>-->
<!--                                                <td>--><?php //echo $this->MyForm->input($data['referals']['id'], array('type' => 'select', 'options' => $yesNoOptions,'value' => $data['referals']['value'])); ?><!--<span style="font-size: x-small; font-style:italic; padding-left: 10px;">--><?php //echo __("Enable (Yes) or disable (No) referral system."); ?><!--</td>-->
<!--                                            </tr>-->
                                            <tr>
                                                <td><?php echo __('Percent of commissions from profit for agent'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['commission']['id'], array('value' => $data['commission']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Commissions in %'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Service fee'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['service_fee']['id'], array('value' => $data['service_fee']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Service fee in % from stake'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Weekly balance reset'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['weekly_balance_reset']['id'], array('type' => 'select', 'options' => array(0 => "No", 1 => "Yes"), 'default' => $data['weekly_balance_reset']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable weekly balance reset."); ?></span></td>

                                            </tr>
                                            <tr>
                                                <td><?php echo __('Weekly balance reset balance'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['weekly_balance_reset_balance']['id'], array('value' => $data['weekly_balance_reset_balance']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Weekly balance reset balance'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Weekly balance reset time / day'); ?></td>
                                                <td>
                                                    <?php echo $this->MyForm->input($data['weekly_balance_reset_day']['id'], array('type' => 'select', 'options' => array(7 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5=> 'Friday', 6 => 'Saturday'),  'default' => $data['weekly_balance_reset_day']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('The balance reset on set week day at midnight (server time UTC)'); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Balance format: number of decimal places'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['balance_decimal_places']['id'], array('value' => $data['balance_decimal_places']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Number n. For example when n=2 amounts will be displayed in: 1.00. When n=0: 1;"); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Reservation ticket'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['reservation_ticket_mode']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['reservation_ticket_mode']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) reservation ticket on website."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('User upload documentation email'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['user_doc_upload_email']['id'], array('value' => $data['user_doc_upload_email']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("User docs upload email used user panel on website."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Allow user upload documentation'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['user_doc_upload']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['user_doc_upload']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) user docs upload in user panel on website."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Import data feed live'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['feed_import_live']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['feed_import_live']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) to import live data from feed."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Import data feed prematch'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['feed_import_prematch']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['feed_import_prematch']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) to import prematch data from feed."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Feed update odds'); ?></td>
                                                <td><?php echo $this->MyForm->input($data['feed_update_odds']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['feed_update_odds']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Enable (Yes) or disable (No) to update odds from feed."); ?></span></td>
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