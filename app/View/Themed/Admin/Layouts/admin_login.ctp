<!DOCTYPE html>
<!--
Template Name: Admin Lab Dashboard build with Bootstrap v2.3.1
Template Version: 1.3
Author: Mosaddek Hossain
Website: http://thevectorlab.net/
-->

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>Login page</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <?php echo $this->Html->meta('icon', $this->Html->url('/favicon.ico')); ?>
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="/theme/Admin/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/theme/Admin/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <?php echo $this->MyHtml->css(array('style', 'style_responsive', 'style_default')); ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body id="login-body">
<div class="login-header">
    <!-- BEGIN LOGO -->
    <div id="logo" class="center">
        <a href="/admin">
            <?php /*echo $this->MyHtml->image('/img/logo.png', array('style' => 'position: relative; bottom: 8px;', 'class' => 'center', 'alt' => 'logo')); */ ?>
        </a>
    </div>
    <!-- END LOGO -->
</div>

<!-- BEGIN LOGIN -->
<div id="login">

    <!-- BEGIN LOGIN FORM -->
    <?php echo $this->MyForm->create($model, array('class' => 'form-vertical no-padding no-margin', 'id' => 'loginform')); ?>
    <form id="loginform" class="form-vertical no-padding no-margin" action="/admin" method="post">
        <div class="lock">
            <i class="icon-lock"></i>
        </div>
        <div class="control-wrap">
            <h4><?php echo __('Staff Login'); ?></h4>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-user"></i></span>
                        <?php echo $this->MyForm->input('username', array('id' => 'input-username', 'placeholder' => 'Username', 'label' => false, 'div' => false)); ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-key"></i></span>
                        <?php echo $this->MyForm->input('password', array('id' => 'input-password', 'placeholder' => 'Password', 'label' => false, 'div' => false)); ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-tags"></i></span>
                        <?php echo $this->MyForm->input('group_id', array('type' => 'select', 'options' => $groups, 'class' => 'dropbox', 'style' => 'width: 227px; height: 42px;', 'label' => false, 'div' => false)); ?>
                    </div>
                    <div class="clearfix space5"></div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-tags"></i></span>
                        <?php echo $this->MyForm->input('language_id', array('type' => 'select', 'options' => $languages, 'class' => 'dropbox', 'style' => 'width: 227px; height: 42px;', 'label' => false, 'div' => false)); ?>
                    </div>
                    <div class="clearfix space5"></div>
                </div>
            </div>

            <div style="display:none;"><input type="hidden" name="_method" value="POST"/></div>
        </div>

        <input type="submit" id="login-btn" class="btn btn-block login-btn" value="Login" />
    </form>

    <!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div id="login-copyright">
    
</div>


<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS -->
<?php echo $this->MyHtml->script(array('jquery-1.8.3.min.js')); ?>
<script src="/theme/Admin/assets/bootstrap/js/bootstrap.min.js"></script>
<?php echo $this->MyHtml->script(array('jquery.blockui.js', 'scripts.js')); ?>
<script>
    jQuery(document).ready(function() {
        App.initLogin();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>