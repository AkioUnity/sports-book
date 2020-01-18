<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $title_for_layout; ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <?php if (in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Admin/assets/bootstrap-rtl/css/bootstrap-rtl.min.css?'); ?>"/>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Admin/assets/bootstrap-rtl/css/bootstrap-responsive-rtl.min.css?'); ?>"/>
        <link rel="stylesheet" type="text/css" href="<?= $this->Html->url('/theme/Admin/css-rtl/style.css?'); ?>"/>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Admin/css-rtl/style_responsive.css?'); ?>"/>
        <link rel="stylesheet" type="text/css" href="<?= $this->Html->url('/theme/Admin/css-rtl/style_gray.css?'); ?>"/>
    <?php else: ?>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Admin/assets/bootstrap/css/bootstrap.min.css?'); ?>"/>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Admin/assets/bootstrap/css/bootstrap-responsive.min.css?'); ?>"/>
        <?php echo $this->MyHtml->css(array('style', 'style_responsive', 'style_gray')); ?>
    <?php endif; ?>

    <link href="/theme/Admin/assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link href="/theme/Admin/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet"/>
    <link href="/theme/Admin/assets/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="/theme/Admin/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet"/>
    <link href="/theme/Admin/assets/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="/theme/Admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
    <link href="/theme/Admin/assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css"
          rel="stylesheet" type="text/css"/>

    <?php if (in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
        <script src="<?= $this->Html->url('/theme/Admin//js-rtl/jquery-1.8.3.min.js'); ?>"></script>
    <?php else: ?>
        <script src="<?= $this->Html->url('/theme/Admin//js/jquery-1.8.3.min.js'); ?>"></script>
    <?php endif; ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
<!-- BEGIN HEADER -->
<div id="header" class="navbar navbar-inverse navbar-fixed-top">
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="navbar-inner">
        <div class="container-fluid">
            <!-- BEGIN LOGO -->
            <a class="brand" href="/admin">
                <img src="/theme/Admin/img/logo.png" alt="">
            </a>
            <!-- END LOGO -->

            <div id="top_menu" class="nav notify-row">
                <!-- BEGIN NOTIFICATION -->
                <?php /**
                 * <ul class="nav top-menu">
                 * <!-- BEGIN SETTINGS -->
                 *
                 * <li class="dropdown">
                 * <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'settings')); ?>" data-original-title="<?php echo __('Settings', true); ?>">
                 * <i class="icon-cog"></i>
                 * </a>
                 * </li>
                 * <!-- END SETTINGS -->
                 * </ul>
                 */ ?>
            </div>
            <!-- END  NOTIFICATION -->

            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse"
               data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="arrow"></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->


            <div class="top-nav ">
                <ul class="nav pull-right top-menu">
                    <li>
                        <div id="user-time" style="color: #FFF; margin-top: 15px;"><?=date("H:i:s", strtotime($this->TimeZone->convertTime(time(), 'Y-m-d H:i:s')));?> <?=$this->TimeZone->timeZone();?></div>
                        <?php $today = getdate(strtotime($this->TimeZone->convertTime(time(), 'Y-m-d H:i:s'))); ?>
                        <script>
                            var d = new Date(<?php echo $today['year'].",".$today['mon'].",".$today['mday'].",".$today['hours'].",".$today['minutes'].",".$today['seconds']; ?>);
                            setInterval(function() {
                                d.setSeconds(d.getSeconds() + 1);
                                var  hours = ("0" + d.getHours()).slice(-2);
                                var  minutes = ("0" + d.getMinutes()).slice(-2);
                                var  seconds = ("0" + d.getSeconds()).slice(-2);
                                var  timezone = "<?=$this->TimeZone->timeZone();?>";
                                $('#user-time').text((hours +':' + minutes + ':' + seconds  + ' ' + timezone ));
                            }, 1000);
                        </script>
                    </li>
                    <?php if (CakeSession::read('Auth.User.group_id') == Group::OPERATOR_GROUP): ?>
                        <li>
                            <a target="_blank"
                               href="<?php echo $this->Html->url(array('admin' => false, 'language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'users', 'action' => 'operator_panel')); ?>">
                                <img src="/theme/Admin/img/operator-panel-btn.png" alt="">
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?php echo $this->MyHtml->image('/img/user-avatar.png'); ?>
                            <span class="username">
                                    <?php echo $this->Session->read('Auth.User.username'); ?>
                                </span>
                        </a>
                    </li>
                    <li class="">


                        <a href="<?php echo $this->Html->url(array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'users', 'action' => 'logout')); ?>"><?php echo $this->MyHtml->image('/img/door-exit.png'); ?>


                        </a>


                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
                <!-- END TOP NAVIGATION MENU -->
            </div>
        </div>
    </div>
    <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->

<div id="container" class="row-fluid">

    <!-- BEGIN SIDEBAR -->
    <?php echo $this->element('sidebar'); ?>
    <!-- END SIDEBAR -->

    <!-- BEGIN PAGE -->
    <div id="main-content">
        <!-- BEGIN PAGE CONTAINER-->
        <?php echo $content_for_layout; ?>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div id="footer">
    The platform is created and powered by planet1x2.com
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS -->
<!-- Load javascripts at bottom, this will reduce page load time -->
<script src="/theme/Admin/assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/theme/Admin/assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/theme/Admin/assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>

<?php if (in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
    <script src="<?= $this->Html->url('/theme/Admin/assets/bootstrap-rtl/js/bootstrap.min.js'); ?>"></script>
<?php else: ?>
    <script src="<?= $this->Html->url('/theme/Admin/assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
<?php endif; ?>

<?php echo $this->MyHtml->script(array('jquery.blockui.js')); ?>
<?php echo $this->MyHtml->script(array('jquery.cookie.js')); ?>
<!-- ie8 fixes -->
<!--[if lt IE 9]>
<?php echo $this->MyHtml->script(array('excanvas.js')); ?>
<?php echo $this->MyHtml->script(array('respond.js')); ?>
<![endif]-->
<script src="/theme/Admin/assets/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<script src="/theme/Admin/assets/jquery-knob/js/jquery.knob.js"></script>

<!-- ie8 fixes -->
<!--[if lt IE 9]>
<?php echo $this->MyHtml->script(array('excanvas.js')); ?>
<?php echo $this->MyHtml->script(array('respond.js')); ?>
<![endif]-->

<?php if (in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
    <script src="<?= $this->Html->url('/theme/Admin//js-rtl/jquery.peity.min.js'); ?>"></script>
<?php else: ?>
    <script src="<?= $this->Html->url('/theme/Admin//js/jquery.peity.min.js'); ?>"></script>
<?php endif; ?>

<script type="text/javascript" src="/theme/Admin/assets/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?= $this->Html->url('/theme/Admin//js/scripts.js'); ?>"></script>

<script type="text/javascript" src="/theme/Admin/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/uniform/jquery.uniform.min.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/clockface/js/clockface.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
<script type="text/javascript"
        src="/theme/Admin/assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-daterangepicker/date.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/fancybox/source/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

<!--[if lte IE 8]>
<script language="javascript" type="text/javascript" src="/theme/Admin/assets/flot/excanvas.min.js"></script>
<![endif]-->

<script type="text/javascript" src="/theme/Admin/assets/flot/jquery.flot.js"></script>
<script type="text/javascript" src="/theme/Admin/assets/flot/jquery.flot.pie.js"></script>


<script>
    jQuery(document).ready(function () {
        // initiate layout and plugins
        App.setMainPage(true);
        App.init();
    });
</script>

</body>
<!-- END BODY -->
</html>
