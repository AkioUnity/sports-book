<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title><?php echo $title_for_layout; ?></title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <?php echo $this->MyHtml->css(array('style')); ?>
        <?php echo $this->MyHtml->script(array('jquery-1.8.3.min.js')); ?>
    </head>
    <body>
        <?php echo $content_for_layout; ?>

        <!-- END FOOTER -->
        <!-- BEGIN JAVASCRIPTS -->
        <!-- Load javascripts at bottom, this will reduce page load time -->
        <script src="/theme/Admin/assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="/theme/Admin/assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="/theme/Admin/assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
        <script src="/theme/Admin/assets/bootstrap/js/bootstrap.min.js"></script>

        <?php echo $this->MyHtml->script(array('jquery.blockui.js', 'jquery.cookie.js')); ?>

        <!-- ie8 fixes -->
        <!--[if lt IE 9]>
        <?php echo $this->MyHtml->script(array('excanvas.js', 'respond.js')); ?>
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
        <?php echo $this->MyHtml->script(array('excanvas.js', 'respond.js')); ?>
        <![endif]-->

        <script type="text/javascript" src="/theme/Admin/assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/uniform/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/clockface/js/clockface.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/jquery-tags-input/jquery.tagsinput.min.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-daterangepicker/date.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/fancybox/source/jquery.fancybox.pack.js"></script>

        <!--[if lte IE 8]>
        <script language="javascript" type="text/javascript" src="/theme/Admin/assets/flot/excanvas.min.js"></script>
        <![endif]-->

        <script type="text/javascript" src="/theme/Admin/assets/flot/jquery.flot.js"></script>
        <script type="text/javascript" src="/theme/Admin/assets/flot/jquery.flot.pie.js"></script>

        <?php echo $this->MyHtml->script(array('jquery.peity.min.js')); ?>
        <script type="text/javascript" src="/theme/Admin/assets/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <?php echo $this->MyHtml->script(array('scripts.js')); ?>
        <script>
            jQuery(document).ready(function() {
                // initiate layout and plugins
                App.setMainPage(true);
                App.init();
            });
        </script>
        <!-- END JAVASCRIPTS -->
    </body>
</html>