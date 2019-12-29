<!-- Header START -->
<div id="header">
    <div class="header-top">

        <a href="/" class="home left"></a>

        <p id="real-time-clock" class="left">
            <?php echo $this->element('layout-blocks/header-block/header-clock'); ?>
        </p>

        <!-- TOP menu START -->
        <ul class="right">
            <li></li>
            <li></li>
            <li></li>
        </ul>

        <!-- TOP menu END -->
        <div class="clear"></div>
    </div>
    <div class="header-bottom">
        <!-- LOGO START -->
        <a href="/">
            <?php echo $this->Html->image('logo.png', array('class' => 'logo left', 'alt' => 'ChalkPro')); ?>
        </a>
        <!-- LOGO END -->

        <!-- Info Box START -->
        <div class="rest-header-box">
            <div class="lhold1">
                <span class="title"><?php echo __('Requested at'); ?>:</span>
                <span class="content">(date) (time)</span>
            </div>
            <div class="lhold1">
                <span><?php echo __('Requested by'); ?>:</span>
                <span class="today-tickets"><?php echo '(user)'; ?></span>
            </div>
        </div>
        <!-- Info Box END -->

        <div class="clear"></div>
    </div>
</div>
<!-- Header END'-->

<!-- ContentSTART -->
<div id="content">

    <!-- Left side START -->
    <div class="side-left"></div>
    <!-- Left side END -->

    <!-- Main CONTENT START -->
    <div class="main-content">
        <!-- Page Content START -->
        <div class="mid-cent">
            <h3><?php echo __('Activity Column'); ?></h3>
            <div id="operator-panel-content" class="cent-txt txt-pad">
                <!-- Nothin' here :P -->
            </div>
        </div>
        <!-- Page Content END -->
        <div class="clear"></div>
    </div>
    <!-- Main CONTENT END -->

    <!-- Right side START -->
    <div class="side-right"></div>
    <!-- Right side END -->

    <div class="clear"></div>
</div>
<!-- Content END-->

<!-- Footer START -->
<div id="footer">

</div>